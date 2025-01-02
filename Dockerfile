FROM php:8.2-fpm

# Environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_PROCESS_TIMEOUT=2000
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV DDEV_PHP_VERSION=8.2

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# NPM configuration
RUN npm config set cache /var/cache/npm

# Application setup
WORKDIR /var/www/html
COPY . .
COPY .env.example /var/www/html/.env
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data storage bootstrap/cache

