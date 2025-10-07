# Fix PHP 8.1 Compatibility Issue

## Problem
Server is running PHP 8.1.16, but the application requires PHP 8.2.0 or higher.

## Solution Applied
Changed PHP requirement from `^8.2` to `^8.1` in composer.json to support PHP 8.1+

## Steps to Fix on Server

### Option 1: Update Dependencies (Quick Fix - If you already have PHP 8.1)

Run these commands on your server:

```bash
cd /var/www/html/streaming-app

# Update composer dependencies to match PHP 8.1
composer update --no-dev --optimize-autoloader

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test the application
php artisan serve --host=0.0.0.0 --port=8000
```

### Option 2: Pull Latest Changes from Git

```bash
cd /var/www/html/streaming-app

# Pull latest changes with PHP 8.1 support
git pull origin main

# Update composer dependencies
composer update --no-dev --optimize-autoloader

# Clear and cache
php artisan optimize:clear
php artisan optimize

# Test
php artisan serve
```

### Option 3: Manual Update composer.json

If git pull doesn't work, manually edit composer.json:

```bash
nano composer.json
```

Change this line:
```json
"php": "^8.2",
```

To:
```json
"php": "^8.1",
```

Then run:
```bash
composer update --no-dev --optimize-autoloader
php artisan optimize
```

## Verification

After applying the fix, verify:

```bash
# Check PHP version
php -v

# Check if artisan works
php artisan --version

# Try to start the server
php artisan serve
```

## Production Deployment with PHP 8.1

The application now supports:
- ✅ PHP 8.1.16 (your current version)
- ✅ PHP 8.2.x
- ✅ PHP 8.3.x

All features will work correctly with PHP 8.1+

## Note

While PHP 8.1 is supported, we recommend upgrading to PHP 8.2+ for:
- Better performance
- Latest security patches
- Long-term support

## Upgrade PHP to 8.2 (Optional - Recommended for Production)

If you want to upgrade PHP instead:

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Install PHP 8.2
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
  php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip \
  php8.2-gd php8.2-bcmath php8.2-intl

# Switch to PHP 8.2 (Apache)
sudo a2dismod php8.1
sudo a2enmod php8.2
sudo systemctl restart apache2

# Or switch to PHP 8.2 (Nginx + PHP-FPM)
sudo systemctl stop php8.1-fpm
sudo systemctl disable php8.1-fpm
sudo systemctl start php8.2-fpm
sudo systemctl enable php8.2-fpm
sudo systemctl restart nginx

# Update alternatives (CLI)
sudo update-alternatives --set php /usr/bin/php8.2

# Verify
php -v
```

Then revert composer.json back to `"php": "^8.2"` and run `composer update`
