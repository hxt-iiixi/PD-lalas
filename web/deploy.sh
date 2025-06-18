#!/bin/bash
set -e

echo "📦 Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🔧 Optimizing Laravel..."
php artisan config:clear
php artisan config:cache

echo "🧱 Running migrations..."
php artisan migrate --force
