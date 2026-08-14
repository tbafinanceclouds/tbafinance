#!/bin/bash

echo "========================================="
echo "🚀 TBA Finance Cloud - Starting..."
echo "========================================="

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "========================================="
echo "✅ Starting Apache..."
echo "========================================="

# Start Apache
apache2-foreground