FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port
EXPOSE 10000

# ❌ REMOVE artisan commands from build step
# ✅ Run artisan at container startup
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan serve --host=0.0.0.0 --port=10000
# Run Laravel migrations automatically
RUN php artisan migrate --force