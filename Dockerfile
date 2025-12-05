###############################################
# 1) Node Builder (Vite build for Filament 4)
###############################################
FROM node:22-bookworm AS nodebuild
WORKDIR /app

# Install frontend dependencies
COPY package*.json ./
RUN npm ci

# Copy entire project (safe for Filament 4)
COPY . .

ENV NODE_ENV=production
RUN mkdir -p public
RUN npm run build


###############################################
# 2) Composer Vendor Builder
###############################################
FROM composer:2 AS phpdeps
WORKDIR /app
COPY composer.json composer.lock ./

# Ignore ext-intl requirement ONLY in builder stage
RUN composer install \
    --no-dev --prefer-dist --no-progress --no-interaction --no-scripts \
    --ignore-platform-req=ext-intl


###############################################
# 3) PHP Runtime (PHP 8.4 FPM)
###############################################
FROM php:8.4-fpm-bookworm AS php
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    bash git unzip \
    libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy Laravel source
COPY . .

# Copy vendor from composer stage
COPY --from=phpdeps /app/vendor ./vendor

# Set Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

EXPOSE 9000


###############################################
# 4) Nginx Runtime
###############################################
FROM nginx:1.27-alpine AS nginx
WORKDIR /var/www/html

# Copy Laravel public
COPY ./public ./public

# Copy Vite build from nodebuild stage
COPY --from=nodebuild /app/public/build ./public/build

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN apk add --no-cache bash

EXPOSE 80
