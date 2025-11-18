#!/bin/bash

# Laravel Cache Clearing Script
echo "Clearing Laravel caches..."

cd /Users/macossinema/Desktop/cariloker

# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Clear compiled files
php artisan clear-compiled

# Optimize (optional - rebuilds cache)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

echo "All caches cleared!"
echo ""
echo "If you still have issues, also try:"
echo "1. Clear browser cache (Ctrl+Shift+Delete or Cmd+Shift+Delete)"
echo "2. Check Laravel logs: storage/logs/laravel.log"
echo "3. Make sure you're logged in as admin user"
echo "4. Check browser console for JavaScript errors"

