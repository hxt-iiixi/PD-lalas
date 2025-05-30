FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy files
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=10000