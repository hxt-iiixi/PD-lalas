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

# Ensure permissions are set for Laravel logs and cache folders
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Clear config/cache before installing dependencies
RUN php artisan config:clear \
    && php artisan cache:clear \
    && composer install --optimize-autoloader --no-dev

# Expose port and start Laravel's dev server
EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000
