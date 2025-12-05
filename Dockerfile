# =====================================================================================
# STAGE 1 — Node 22 build untuk Vite
# =====================================================================================
FROM node:22-bookworm AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# =====================================================================================
# STAGE 2 — PHP 8.4 + Composer + vendor install
# =====================================================================================
FROM php:8.4-fpm-bookworm AS phpdeps
# bash supaya "exec bash" di Dokploy tidak error
RUN apt-get update && apt-get install -y bash
WORKDIR /app

# Ekstensi lengkap utk Laravel/Filament
RUN apt-get update && apt-get install -y \
    git unzip libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1

# Lock platform utk kompatibilitas dependency yang belum ready 8.4
RUN composer config platform.php 8.2.0

# Install vendor
COPY composer.json composer.lock ./
RUN php -v && php -m && composer --version
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv \
 || (composer diagnose && exit 1)

# Bawa source, rerun (biasanya no-op)
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv \
 || (composer diagnose && exit 1)

# =====================================================================================
# STAGE 3 — PHP runtime (php-fpm)
# =====================================================================================
FROM php:8.4-fpm-bookworm AS phpruntime
RUN apt-get update && apt-get install -y bash && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html

# Ekstensi runtime
RUN apt-get update && apt-get install -y \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Opcache tuning
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy app dari stage deps + hasil build Vite
COPY --from=phpdeps   /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Permission Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data
CMD ["php-fpm", "-F"]

# =====================================================================================
# STAGE 4 — Nginx image (untuk service nginx)
# =====================================================================================
FROM nginx:1.27-alpine AS nginximage
# bash opsional, biar "exec bash" di Dokploy bisa
RUN apk add --no-cache bash
# Konfigurasi nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# (opsional) copy public statics biar nginx bisa layani file statis langsung
# index.php tidak dieksekusi di sini; akan diforward ke php-fpm (service php).
# Kita cukup pastikan assets build Vite tersedia.
WORKDIR /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build
# Jika kamu punya favicon, robots.txt, dsb:
# COPY public/ /var/www/html/public/
