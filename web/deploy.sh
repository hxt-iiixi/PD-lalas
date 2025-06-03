#!/bin/bash
set -e

echo "📦 Clearing config cache..."
php artisan config:clear

echo "🔄 Running fresh migrations..."
php artisan migrate:fresh --force

echo "✅ Deployment script finished."
