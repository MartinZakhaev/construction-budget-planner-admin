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

# INSTALL BASH (fix: bash not found)
RUN apt-get update && apt-get install -y bash

WORKDIR /app

# Ekstensi Laravel + Filament
RUN apt-get update && apt-get install -y \
    git unzip libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
    --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        gd \
        intl \
        bcmath \
        opcache \
        mbstring \
        exif \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1

# Platform lock (ngurangin error dependency php 8.4)
RUN composer config platform.php 8.2.0

COPY composer.json composer.lock ./

# Debug info (optional)
RUN php -v && php -m && composer --version

# Install vendor
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv \
 || (composer diagnose && exit 1)

COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader -vvv \
 || (composer diagnose && exit 1)


# =====================================================================================
# STAGE 3 — Runtime: PHP-FPM final image
# =====================================================================================
FROM php:8.4-fpm-bookworm

# INSTALL BASH (fix exec: bash not found)
RUN apt-get update && apt-get install -y bash && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Ekstensi runtime
RUN apt-get update && apt-get install -y \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev --no-install-recommends \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Opcache
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy App
COPY --from=phpdeps /app /var/www/html

# Copy Vite build result
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

CMD ["php-fpm", "-F"]
