###############################################
# 1) Build Vite assets for Filament
###############################################
FROM node:22-bookworm AS nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm ci --only=production

COPY . .
ENV NODE_ENV=production
RUN npm run build

###############################################
# 2) Install Composer dependencies (without dev)
###############################################
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --prefer-dist --no-progress --no-interaction --no-scripts --optimize-autoloader \
    --ignore-platform-req=ext-intl

###############################################
# 3) Final runtime: PHP-FPM + Nginx on Debian
###############################################
FROM php:8.4-fpm-bookworm AS app
WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    bash git unzip curl postgresql-client nginx supervisor \
    libonig-dev libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    pdo pdo_pgsql pgsql zip gd intl bcmath opcache mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && sed -i 's/memory_limit = 128M/memory_limit = 512M/' "$PHP_INI_DIR/php.ini" \
 && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 25M/' "$PHP_INI_DIR/php.ini" \
 && sed -i 's/post_max_size = 8M/post_max_size = 25M/' "$PHP_INI_DIR/php.ini" \
 && sed -i 's/max_execution_time = 30/max_execution_time = 300/' "$PHP_INI_DIR/php.ini"

# Configure OPcache for production
RUN docker-php-ext-enable opcache \
 && echo 'opcache.memory_consumption=128' > "$PHP_INI_DIR/conf.d/99-opcache.ini" \
 && echo 'opcache.interned_strings_buffer=8' >> "$PHP_INI_DIR/conf.d/99-opcache.ini" \
 && echo 'opcache.max_accelerated_files=4000' >> "$PHP_INI_DIR/conf.d/99-opcache.ini" \
 && echo 'opcache.revalidate_freq=2' >> "$PHP_INI_DIR/conf.d/99-opcache.ini" \
 && echo 'opcache.fast_shutdown=1' >> "$PHP_INI_DIR/conf.d/99-opcache.ini"

# Copy application files
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=nodebuild /app/public/build ./public/build

# Set up Laravel directories and permissions
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

# Copy nginx and supervisor configurations
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisor/laravel.conf /etc/supervisor/conf.d/laravel.conf

# Remove default nginx site and configure logging
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf.default 2>/dev/null || true \
 && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/ \
 && ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log

# Create startup script
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port for reverse proxy
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://127.0.0.1:8080/ || exit 1

# Use supervisor to manage both nginx and php-fpm
CMD ["/usr/local/bin/docker-entrypoint.sh"]
