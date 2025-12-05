# =========================
# 1) Node builder: Vite build -> public/build
# =========================
FROM node:22-bookworm AS nodebuild
WORKDIR /app

# Pasang dependencies dulu (lebih cepat build cache-nya)
COPY package*.json ./
RUN npm ci

# Copy seluruh project (lebih aman untuk Filament 4)
# Pastikan .dockerignore mengecualikan node_modules, vendor, dll
COPY . .

# Build Vite
ENV NODE_ENV=production
RUN mkdir -p public
RUN npm run build


# =========================
# 2) Composer vendor
# =========================
FROM composer:2 AS phpdeps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts


# =========================
# 3) PHP-FPM runtime (PHP 8.4)
# =========================
FROM php:8.4-fpm-bookworm AS phpruntime
WORKDIR /var/www/html

# Packages untuk Laravel + Filament
RUN apt-get update && apt-get install -y \
    bash git unzip \
    libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy kode Laravel
COPY . .

# Copy vendor dari stage composer
COPY --from=phpdeps /app/vendor ./vendor

# Permission Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php -v || exit 1


# =========================
# 4) Nginx runtime (serve /public & Vite build)
# =========================
FROM nginx:1.27-alpine AS nginximage
WORKDIR /var/www/html

# Copy folder public Laravel
COPY ./public ./public

# Copy hasil Vite build
COPY --from=nodebuild /app/public/build ./public/build

# Copy Nginx config (dari docker/nginx/default.conf)
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Untuk debugging
RUN apk add --no-cache bash

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD wget -qO- http://127.0.0.1/ >/dev/null 2>&1 || exit 1
