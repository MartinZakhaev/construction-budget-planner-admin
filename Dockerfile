# ============ Stage 1: Build frontend assets (Node 22) ============
FROM node:22-bookworm AS nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ============ Stage 2: PHP deps + Composer (full extensions) ============
FROM php:8.4-fpm-bookworm AS phpdeps
WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    pkg-config \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libssl-dev \
    libxml2-dev \
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

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1

# Jika ada isu kompatibilitas PHP 8.4, boleh aktifkan baris ini:
# RUN composer config platform.php 8.3.0

COPY composer.json composer.lock ./
RUN php -v && php -m | sort && composer --version && composer validate -n

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv \
    --no-progress \
 || (echo '--- COMPOSER DIAG ---' && php -v && php -m | sort && composer diagnose && exit 1)

COPY . .
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv \
    --no-progress \
 || (echo '--- COMPOSER DIAG (after copy) ---' && php -v && php -m | sort && composer diagnose && exit 1)


# ============ Stage 3: Runtime (PHP-FPM 8.4) ============
FROM php:8.4-fpm-bookworm
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    pkg-config \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libssl-dev \
    libxml2-dev \
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

RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

COPY --from=phpdeps /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

USER www-data

CMD ["php-fpm", "-F"]
