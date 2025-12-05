# =========================
# 1) Node builder: build Vite assets -> public/build
# =========================
FROM node:22-bookworm AS nodebuild
WORKDIR /app

# 1) deps
COPY package*.json ./
RUN npm ci

# 2) configs Vite/Tailwind/PostCSS/TS (yang tidak ada akan di-skip oleh Docker)
COPY vite.config.ts ./
COPY vite.config.js ./
COPY postcss.config.js ./
COPY postcss.config.cjs ./
COPY tailwind.config.js ./
COPY tailwind.config.cjs ./
COPY tsconfig.json ./

# 3) source assets
COPY resources ./resources

# 4) pastikan direktori output ada, lalu build
RUN mkdir -p public
ENV NODE_ENV=production
RUN npm run build


# =========================
# 2) Composer deps (vendor)
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

# Libs untuk ekstensi Laravel umum
RUN apt-get update && apt-get install -y \
    bash git unzip \
    libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy source aplikasi (pastikan .dockerignore kamu bersih dari node_modules, vendor, dll)
COPY . .

# Copy vendor dari stage composer
COPY --from=phpdeps /app/vendor ./vendor

# Permission minimal untuk Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php -v || exit 1


# =========================
# 4) Nginx runtime (serve /public + build)
# =========================
FROM nginx:1.27-alpine AS nginximage
WORKDIR /var/www/html

# Copy folder public (index.php, dll)
COPY ./public ./public

# Ambil hasil build Vite dari stage nodebuild -> /public/build
COPY --from=nodebuild /app/public/build ./public/build

# Nginx config berada di docker/nginx/default.conf (sesuai request)
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Bash untuk debug
RUN apk add --no-cache bash

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD wget -qO- http://127.0.0.1/ >/dev/null 2>&1 || exit 1
