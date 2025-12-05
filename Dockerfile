# ---- Stage 1: build assets (Vite/Filament) ----
FROM node:22-bullseye AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Stage 2: composer deps ----
FROM composer:2 AS composerbuild
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-plugins
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---- Stage 3: runtime PHP-FPM 8.4 ----
FROM php:8.4-fpm-bookworm
WORKDIR /var/www/html

# Ext yang umum untuk Laravel/Filament + Postgres
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache \
 && rm -rf /var/lib/apt/lists/*

# Opcache DEV (auto-reload file changes)
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# (Opsional) Xdebug untuk debug dev — aktifkan bila perlu
# RUN pecl install xdebug && docker-php-ext-enable xdebug
# COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy app & assets
COPY --from=composerbuild /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data

CMD ["php-fpm", "-F"]
