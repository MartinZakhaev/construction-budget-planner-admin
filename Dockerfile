FROM node:22-slim AS node_builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM serversideup/php:8.4-fpm-nginx

# Stay as root for build steps
USER root

# Install required PHP extensions
RUN install-php-extensions intl

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built assets from node_builder
COPY --from=node_builder /app/public/build ./public/build

# Install PHP dependencies as root
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# The base image will handle switching to the appropriate user at runtime
