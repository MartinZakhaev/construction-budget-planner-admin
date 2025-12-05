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

# Install nginx and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    nginx bash git unzip supervisor \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy source, composer vendor tree, and built assets
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=nodebuild /app/public/build ./public/build

# Ensure cache / storage directories exist with safe permissions
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

# Copy nginx virtual host
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["bash", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
