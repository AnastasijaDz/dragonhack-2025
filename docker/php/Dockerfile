FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app source
COPY . .

# Install PHP dependencies
RUN composer install

# Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Generate app key (if .env exists and no key is set)
RUN if [ -f .env ]; then php artisan key:generate --ansi || true; fi

# Expose port
EXPOSE 9000
