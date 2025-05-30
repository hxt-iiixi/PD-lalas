FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Ensure correct permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ✅ Install dependencies first (must come BEFORE artisan commands)
RUN composer install --optimize-autoloader --no-dev

# ✅ Now safe to clear cache/config
RUN php artisan config:clear \
    && php artisan cache:clear

# Start Laravel dev server
EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000
