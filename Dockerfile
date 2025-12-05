###############################################
# 1) Node Builder: compile Vite assets
###############################################
FROM node:22-bookworm AS nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
ENV NODE_ENV=production
RUN npm run build

###############################################
# 2) Composer dependencies
###############################################
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --prefer-dist --no-progress --no-interaction --no-scripts \
    --ignore-platform-req=ext-intl

###############################################
# 3) Final runtime: PHP-FPM + Nginx in one container
###############################################
FROM php:8.4-fpm-bookworm AS app
WORKDIR /var/www/html

# System packages, PHP extensions, and nginx
RUN apt-get update && apt-get install -y \
    nginx \
    bash git unzip supervisor \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy application code
COPY . .

# Vendor + built assets
COPY --from=vendor /app/vendor ./vendor
COPY --from=nodebuild /app/public/build ./public/build

# Configure nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default || true

# Permissions for storage/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

EXPOSE 80

CMD ["bash", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
