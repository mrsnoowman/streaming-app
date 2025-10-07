# Laravel 10 + PHP 8.1 Server Setup Guide

## Problem Solved

Application has been fully migrated to **Laravel 10** for **PHP 8.1 compatibility**.

Previous issues:

-   Laravel 11 requires PHP 8.2+
-   Laravel 12 requires PHP 8.2+
-   Several packages required PHP 8.2+

**Solution**: Downgrade to Laravel 10 (fully supports PHP 8.1.16)

## Steps to Fix on Server

### Step 1: Clean Up Server Directory

```bash
cd /var/www/html/streaming-app-main

# Remove vendor and composer.lock
rm -rf vendor
rm composer.lock

# Remove any misplaced Kernel.php file
rm -f app/Http/Middleware/Kernel.php

# Pull latest changes from GitHub
git pull origin main
```

### Step 2: Install Fresh Dependencies

```bash
# Install with PHP 8.1
composer install --no-dev --optimize-autoloader

# This should now work without errors!
```

### Step 3: Configure Environment

```bash
# Copy environment template if not exists
cp env.production.template .env

# Edit with your configuration
nano .env
```

Configure these values:

```env
APP_NAME="CCTV Streaming Dashboard"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://your-server-ip

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cctv_live
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_STORE=database
```

### Step 4: Generate Key and Run Migrations

```bash
# Generate application key
php artisan key:generate

# Create required tables
php artisan cache:table
php artisan session:table
php artisan queue:table

# Run migrations
php artisan migrate --force
```

### Step 5: Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/html/streaming-app-main

# Set permissions
sudo chmod -R 755 /var/www/html/streaming-app-main
sudo chmod -R 775 /var/www/html/streaming-app-main/storage
sudo chmod -R 775 /var/www/html/streaming-app-main/bootstrap/cache
```

### Step 6: Optimize for Production

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Test Application

```bash
# Test artisan commands
php artisan --version

# Should output: Laravel Framework 10.x.x

# Test server
php artisan serve --host=0.0.0.0 --port=8000
```

## What Changed

### composer.json

-   **PHP**: `^8.1` (support PHP 8.1.16+)
-   **Laravel Framework**: `^10.0` (was ^12.0)
-   **Laravel Tinker**: `^2.8` (was ^2.10.1)
-   **Laravel Pint**: `^1.13` (was ^1.24)
-   **Laravel Sail**: `^1.26` (was ^1.41)
-   **Collision**: `^7.10` (was ^8.6)
-   **PHPUnit**: `^10.5` (compatible version)
-   **Removed**: `laravel/pail` (requires PHP 8.2+)

### New Laravel 10 Structure Files

**bootstrap/app.php**

-   Traditional Laravel 10 application bootstrap
-   Creates and configures Application instance

**app/Http/Kernel.php**

-   HTTP middleware configuration
-   Global middleware stack
-   Route middleware groups
-   Middleware aliases

**app/Console/Kernel.php**

-   Console command scheduling
-   Command registration

**app/Exceptions/Handler.php**

-   Exception handling
-   Error reporting configuration

**artisan**

-   Laravel 10 console entry point
-   Traditional kernel-based command handling

**app/Http/Middleware/TrustProxies.php**

-   Standard Laravel middleware for proxy handling

## Verification Checklist

After setup, verify:

-   [ ] `composer install` completes without errors
-   [ ] `php artisan --version` shows Laravel 10.x
-   [ ] `php -v` shows PHP 8.1.16
-   [ ] Database migrations run successfully
-   [ ] Application loads in browser
-   [ ] Login page works
-   [ ] CCTV streams display
-   [ ] VMS page accessible
-   [ ] Pagination works
-   [ ] Dark/Light mode toggles
-   [ ] No console errors

## Troubleshooting

### Error: "Class App\Http\Kernel located in ./app/Http/Middleware/Kernel.php"

**Solution:**

```bash
# Remove misplaced file
rm -f app/Http/Middleware/Kernel.php

# Re-run composer
composer dump-autoload
```

### Error: "There are no commands defined in the package namespace"

**Solution:**

```bash
# Clear composer cache
composer clear-cache

# Reinstall
rm -rf vendor composer.lock
composer install --no-dev
```

### Error: "Method Application::configure does not exist"

**Solution:**

-   Make sure you pulled latest code with updated `bootstrap/app.php`
-   File should have traditional Laravel 10 format, not Laravel 11 `configure()` method

### Still Getting PHP Version Errors

**Verify versions:**

```bash
# Check composer.json
grep -A 5 '"require"' composer.json

# Should show:
# "php": "^8.1",
# "laravel/framework": "^10.0",
```

If still showing Laravel 11 or 12:

```bash
git pull origin main
rm -rf vendor composer.lock
composer install --no-dev
```

## Database Setup

```sql
-- Create database
CREATE DATABASE cctv_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'cctv_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON cctv_live.* TO 'cctv_user'@'localhost';
FLUSH PRIVILEGES;

-- Insert sample data
USE cctv_live;

-- CCTV data
INSERT INTO cctvs (lokasi, status, lastupdate, http_link, ip) VALUES
('AKSES CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/327294924686013390782781.m3u8', '172.61.0.201');

-- Admin user (password: password123)
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES
('Administrator', 'admin@tollroad.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
```

## Production Deployment

1. ✅ Pull code from GitHub
2. ✅ Run `composer install --no-dev`
3. ✅ Configure `.env` file
4. ✅ Run migrations
5. ✅ Set permissions
6. ✅ Cache configuration
7. ✅ Configure web server (Nginx/Apache)
8. ✅ Test all functionality

## Support

**Repository**: https://github.com/mrsnoowman/streaming-app
**Laravel Version**: 10.x
**PHP Version**: 8.1.16+
**Status**: Production Ready ✅

---

**Last Updated**: October 2025  
**Migration**: Laravel 12 → Laravel 10  
**PHP Support**: 8.1.16+
