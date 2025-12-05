# =====================================================================================
# ---- Stage 1: Build frontend assets (Node 22)
# =====================================================================================
FROM node:22-bookworm AS nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# =====================================================================================
# ---- Stage 2: Build PHP dependencies + Composer (with full extensions)
# =====================================================================================
FROM php:8.4-fpm-bookworm AS phpdeps
WORKDIR /app

# Install all required libs for building PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip pkg-config \
    libonig-dev \               # required for mbstring (oniguruma)
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

# Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1

# If PHP 8.4 compatibility issues occur → uncomment this
# RUN composer config platform.php 8.3.0

# Copy composer files early for caching
COPY composer.json composer.lock ./

# Diagnostics
RUN php -v && php -m | sort && composer --version && composer validate -n

# First vendor install
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv --no-progress \
 || (echo '--- COMPOSER DIAG ---' && php -v && php -m | sort && composer diagnose && exit 1)

# Copy full source code
COPY . .

# Second vendor install (usually fast)
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv --no-progress \
 || (echo '--- COMPOSER DIAG (after copy) ---' && php -v && php -m | sort && composer diagnose && exit 1)


# =====================================================================================
# ---- Stage 3: Runtime (Final Image)
# =====================================================================================
FROM php:8.4-fpm-bookworm
WORKDIR /var/www/html

# Install only runtime dependencies
RUN apt-get update && apt-get install -y \
    pkg-config \
    libonig-dev \
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

# Opcache recommended dev settings
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy vendor + app files from PHP build stage
COPY --from=phpdeps /app /var/www/html

# Copy built Vite assets
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Laravel storage permission
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

USER www-data

CMD ["php-fpm", "-F"]
