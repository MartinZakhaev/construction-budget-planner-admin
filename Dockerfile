###############################################
# 1) Build Vite assets for Filament
###############################################
FROM node:22-bookworm AS nodebuild
WORKDIR /app

COPY package*.json .
RUN npm ci

COPY . .
ENV NODE_ENV=production
RUN npm run build

###############################################
# 2) Install Composer dependencies (without dev)
###############################################
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock .
RUN composer install \
    --no-dev --prefer-dist --no-progress --no-interaction --no-scripts \
    --ignore-platform-req=ext-intl

###############################################
# 3) Final runtime: PHP-FPM + Nginx on Debian
###############################################
FROM php:8.4-fpm-bookworm AS app
WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    bash git unzip curl postgresql-client nginx \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo pdo_pgsql pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=nodebuild /app/public/build ./public/build

RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache \
 && ln -snf /var/www/html/storage/app/public /var/www/html/public/storage \
 && chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache \
 && mkdir -p /run/php \
 && chown www-data:www-data /run/php

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Remove default nginx site and send logs to stdout/stderr
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf.default 2>/dev/null || true \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

CMD ["bash", "-lc", "php-fpm -D && nginx -g 'daemon off;'"]
