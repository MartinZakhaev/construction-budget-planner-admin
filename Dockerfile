# ---- Stage 1: build assets (Node 22) ----
FROM node:22-bookworm AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Stage 2: PHP deps + Composer (punya ext-intl) ----
FROM php:8.4-fpm-bookworm AS phpdeps
WORKDIR /app

# Ext yang dibutuhin Laravel + Filament
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache \
 && rm -rf /var/lib/apt/lists/*

# Pakai composer binary dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Pasang vendor (pakai platform bila perlu)
# RUN composer config platform.php 8.3.0
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv

# Salin source & jalankan lagi (biasanya no-op)
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv

# ---- Stage 3: runtime (PHP-FPM 8.4) ----
FROM php:8.4-fpm-bookworm
WORKDIR /var/www/html

# Pasang ekstensi runtime yang sama
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache \
 && rm -rf /var/lib/apt/lists/*

# Opcache dev-friendly
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Bawa app (vendor sudah jadi di phpdeps), dan asset build dari nodebuild
COPY --from=phpdeps  /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data

CMD ["php-fpm", "-F"]
