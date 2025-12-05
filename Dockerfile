# =========================
# Stage 1 — Build frontend (Node 22)
# =========================
FROM node:22-bookworm AS nodebuild
WORKDIR /app

# Lebih efektif buat cache
COPY package*.json ./
RUN npm ci

# Copy sumber yang relevan untuk Vite/Tailwind
COPY resources ./resources
COPY public ./public
# (opsional) kalau ada file konfigurasi, un-comment sesuai repo kamu
# COPY vite.config.* ./
# COPY tailwind.config.* ./
# COPY postcss.config.* ./
# COPY tsconfig*.json ./

# Build assets -> hasil ke public/build
RUN npm run build


# =========================
# Stage 2 — Composer deps (PHP 8.4) tanpa jalanin script
# =========================
FROM php:8.4-fpm-bookworm AS phpdeps
WORKDIR /app

# Tools dan lib yang diperlukan ext PHP
RUN apt-get update && apt-get install -y \
    bash git unzip \
    libonig-dev \ 
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
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

# pakai composer official
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1 \
    COMPOSER_NO_INTERACTION=1

# Copy composer files dulu biar cache maksimal
COPY composer.json composer.lock ./

# Validasi & install vendor (TANPA scripts agar tidak gagal di build time)
RUN php -v && php -m | sort && composer --version && composer validate -n
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts -vvv --no-progress

# Copy seluruh source (kecuali node_modules yg tak dipakai disini)
COPY . .

# (opsional) jika butuh autoload ulang setelah source lengkap
RUN composer dump-autoload -o --no-scripts


# =========================
# Stage 3 — Runtime (PHP-FPM 8.4)
# =========================
FROM php:8.4-fpm-bookworm AS phpruntime
WORKDIR /var/www/html

# Pasang paket runtime + bash (biar `docker exec ... bash` tidak error)
RUN apt-get update && apt-get install -y \
    bash \
    libonig-dev \
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libssl-dev libxml2-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
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

# Opcache basic tune
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
  echo 'opcache.max_accelerated_files=20000'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Copy app dari stage phpdeps (sudah ada vendor)
COPY --from=phpdeps /app /var/www/html

# Copy asset build dari nodebuild
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Permission Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data
CMD ["php-fpm", "-F"]


# =========================
# Stage 4 — Nginx (alpine) untuk serve static + proxy ke php
# =========================
FROM nginx:1.27-alpine AS nginximage

# Pasang bash supaya exec ke container pakai bash bisa
RUN apk add --no-cache bash

# Tulis default.conf langsung (tidak perlu file eksternal)
RUN mkdir -p /etc/nginx/conf.d && cat > /etc/nginx/conf.d/default.conf <<'NGINXCONF'
server {
    listen 80;
    server_name _;

    root /var/www/html/public;
    index index.php index.html;

    # serve Vite build assets
    location /build/ {
        try_files $uri =404;
        access_log off;
        expires 30d;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        # SAMAKAN dengan service php-fpm di docker-compose
        fastcgi_pass php:9000;
        fastcgi_index index.php;

        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        # kalau di belakang proxy/https
        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;
        fastcgi_param HTTP_X_FORWARDED_HOST  $http_x_forwarded_host;
        fastcgi_param HTTP_X_FORWARDED_PORT  $http_x_forwarded_port;
    }

    location ~ /\.ht {
        deny all;
    }

    gzip on;
    gzip_types text/plain text/css application/json application/javascript application/xml image/svg+xml;
    gzip_min_length 1024;
}
NGINXCONF

WORKDIR /var/www/html

# Hanya perlu assets di nginx (index.php akan diproses di php-fpm)
COPY --from=nodebuild /app/public/build /var/www/html/public/build
