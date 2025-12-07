FROM node:22-slim AS node_builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM serversideup/php:8.4-fpm-nginx

# Switch to root to install extra extensions if needed (usually standard ones are covered)
# USER root
# RUN install-php-extensions ...

# Switch back to webuser for app installation
USER webuser
WORKDIR /var/www/html

# Copy application files
COPY --chown=webuser:webgroup . .

# Copy built assets from node_builder
COPY --chown=webuser:webgroup --from=node_builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run Laravel optimizations (can also be done in entrypoint, but doing here saves boot time)
# Note: Some cannot be run without DB connection (like verify). config:cache might fail if env vars aren't present.
# It is safer to NOT run config:cache in build if env vars are dynamic (which they are in Dokploy).
# We will rely on running these at runtime or via AUTORUN_LARAVEL_... env vars.
