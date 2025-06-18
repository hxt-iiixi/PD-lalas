#!/bin/bash
set -e

echo "📦 Clearing config cache..."
php artisan config:clear
php artisan config:cache

echo "🔄 Running migrations..."
php artisan migrate --force

echo "✅ Deployment script finished."
