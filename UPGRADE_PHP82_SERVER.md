# Upgrade Server ke PHP 8.2 - Complete Guide

## 🎯 Overview

Aplikasi telah dikembalikan ke **Laravel 11** dengan requirement **PHP 8.2+** untuk performa dan security terbaik.

**Current Server**: PHP 8.1.16  
**Required**: PHP 8.2.0+  
**Recommended**: PHP 8.2 atau PHP 8.3

## 🚀 Upgrade PHP di Ubuntu/Debian Server

### Step 1: Add Ondrej PHP Repository

```bash
# Update package list
sudo apt update

# Install software-properties-common
sudo apt install software-properties-common -y

# Add Ondrej PHP PPA (trusted repository for latest PHP)
sudo add-apt-repository ppa:ondrej/php -y

# Update package list again
sudo apt update
```

### Step 2: Install PHP 8.2 dan Extensions

```bash
# Install PHP 8.2 CLI dan FPM
sudo apt install php8.2 php8.2-fpm php8.2-cli -y

# Install required extensions
sudo apt install \
    php8.2-common \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-readline \
    php8.2-opcache \
    php8.2-sqlite3 \
    -y
```

### Step 3: Switch PHP Version

#### For Apache

```bash
# Disable PHP 8.1
sudo a2dismod php8.1

# Enable PHP 8.2
sudo a2enmod php8.2

# Restart Apache
sudo systemctl restart apache2
```

#### For Nginx + PHP-FPM

```bash
# Stop PHP 8.1 FPM
sudo systemctl stop php8.1-fpm

# Disable PHP 8.1 FPM autostart
sudo systemctl disable php8.1-fpm

# Start PHP 8.2 FPM
sudo systemctl start php8.2-fpm

# Enable PHP 8.2 FPM autostart
sudo systemctl enable php8.2-fpm

# Restart Nginx
sudo systemctl restart nginx
```

#### Update Nginx Configuration

```bash
# Edit Nginx site configuration
sudo nano /etc/nginx/sites-available/default
# atau
sudo nano /etc/nginx/sites-available/cctv-dashboard
```

**Change this line:**
```nginx
fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
```

**To:**
```nginx
fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
```

**Save and test:**
```bash
sudo nginx -t
sudo systemctl restart nginx
```

### Step 4: Set PHP 8.2 as Default CLI

```bash
# Update alternatives for CLI
sudo update-alternatives --set php /usr/bin/php8.2

# Or if not set, configure it:
sudo update-alternatives --config php
# Select PHP 8.2 from the list
```

### Step 5: Verify PHP Version

```bash
# Check CLI version
php -v
# Expected: PHP 8.2.x

# Check FPM version
php-fpm8.2 -v
# Expected: PHP 8.2.x

# Check web server PHP version (create test file)
echo "<?php phpinfo();" | sudo tee /var/www/html/info.php
# Access: http://your-server-ip/info.php
# Should show PHP 8.2.x
# REMEMBER TO DELETE AFTER: sudo rm /var/www/html/info.php
```

## 📦 Deploy Application dengan PHP 8.2

### Step 1: Clean Installation

```bash
cd /var/www/html/streaming-app-main

# Pull latest code
git pull origin main

# Remove old vendor
rm -rf vendor composer.lock

# Install with PHP 8.2
composer install --no-dev --optimize-autoloader
```

### Step 2: Setup Application

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Set permissions
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 3: Test Application

```bash
# Test artisan
php artisan --version
# Expected: Laravel Framework 11.x.x

# Test commands
php artisan list

# Start test server
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 4: Access Application

Open browser: **http://your-server-ip:8000**

You should see:
- ✅ Professional CCTV Dashboard
- ✅ Login page
- ✅ All features working

## 🔧 Nginx Production Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/streaming-app-main/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Enable and restart:**
```bash
sudo ln -s /etc/nginx/sites-available/cctv-dashboard /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 🗄️ Database Setup

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE cctv_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cctv_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON cctv_live.* TO 'cctv_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Insert sample data:**
```sql
mysql -u cctv_user -p cctv_live

-- CCTV Data
INSERT INTO cctvs (lokasi, status, lastupdate, http_link, ip) VALUES
('AKSES CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/327294924686013390782781.m3u8', '172.61.0.201'),
('PTZ SS CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/357514173892609384751207.m3u8', '172.61.0.204');

-- Admin User (password: password123)
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES
('Administrator', 'admin@tollroad.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
```

**Login Credentials:**
- Email: `admin@tollroad.com`
- Password: `password123`

## ✅ Verification Checklist

After upgrade:

- [ ] `php -v` shows PHP 8.2.x
- [ ] `php artisan --version` shows Laravel 11.x
- [ ] `composer install` completes without errors
- [ ] Database migrations successful
- [ ] Application loads in browser
- [ ] Login works
- [ ] CCTV streams display and auto-play
- [ ] VMS page accessible
- [ ] Pagination works (10 per page)
- [ ] Dark/Light mode toggles
- [ ] Mobile responsive
- [ ] No console errors

## 🎯 Benefits of PHP 8.2

- ⚡ **Performance**: 10-20% faster than PHP 8.1
- 🔒 **Security**: Latest security patches
- 🚀 **Laravel 11**: Access to latest Laravel features
- 📦 **Compatibility**: All modern packages supported
- 🔧 **Maintenance**: Long-term support

## 📊 Alternative: Use PHP 8.3 (Latest)

If you want the absolute latest:

```bash
# Install PHP 8.3 instead
sudo apt install php8.3 php8.3-fpm php8.3-cli \
    php8.3-mysql php8.3-xml php8.3-mbstring \
    php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath \
    php8.3-intl php8.3-opcache -y

# Switch to PHP 8.3
sudo update-alternatives --set php /usr/bin/php8.3

# Update Nginx
# Change: fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;

# Test
php -v
```

Application works with PHP 8.2, 8.3, and newer!

## 🆘 Troubleshooting

### Issue: PHP 8.2 not available in repository

```bash
# Ensure PPA is added correctly
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Search for available PHP versions
apt-cache search php8.2
```

### Issue: Multiple PHP versions conflict

```bash
# List installed PHP versions
dpkg -l | grep php | grep -v common

# Remove old PHP 8.1 (optional)
sudo apt remove php8.1* -y
sudo apt autoremove -y
```

### Issue: PHP-FPM not starting

```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check logs
sudo tail -f /var/log/php8.2-fpm.log

# Restart service
sudo systemctl restart php8.2-fpm
```

## 📞 Support

**Repository**: https://github.com/mrsnoowman/streaming-app  
**Laravel**: 11.x  
**PHP**: 8.2+ Required  
**Status**: Production Ready with PHP 8.2+ ✅

---

**Last Updated**: October 2025  
**Action Required**: Upgrade server to PHP 8.2+  
**Estimated Time**: 15-30 minutes
