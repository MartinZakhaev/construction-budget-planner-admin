# ---- Stage 1: build assets (Node 22) ----
FROM node:22-bookworm AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Stage 2: PHP deps + Composer (dengan ekstensi lengkap) ----
FROM php:8.4-fpm-bookworm AS phpdeps
WORKDIR /app

# Ekstensi yang umum dibutuhkan Laravel/Filament
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1

# === Fallback jika ada kompatibilitas 8.4 (UNCOMMENT jika perlu) ===
# RUN composer config platform.php 8.3.0

# Bawa file composer* dulu supaya cache efektif
COPY composer.json composer.lock ./

# Diagnostik sebelum install
RUN php -v && php -m | sort && composer --version && composer validate -n

# Install vendor (verbose + no-progress biar log jelas tapi tidak terlalu noise)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv --no-progress \
 || (echo '--- COMPOSER DIAG ---' && php -v && php -m | sort && composer diagnose && exit 1)

# Salin source lalu jalankan lagi (biasanya no-op)
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv --no-progress \
 || (echo '--- COMPOSER DIAG (after source copy) ---' && php -v && php -m | sort && composer diagnose && exit 1)

# ---- Stage 3: runtime (PHP-FPM 8.4) ----
FROM php:8.4-fpm-bookworm
WORKDIR /var/www/html

# Runtime extensions (samakan dengan stage phpdeps)
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Opcache - dev friendly
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy app (vendor dari phpdeps) + assets Vite dari nodebuild
COPY --from=phpdeps  /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Permission Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data

CMD ["php-fpm", "-F"]
