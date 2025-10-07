# Server Setup Guide - PHP 8.1 Compatibility

## ✅ PHP 8.1 Compatibility Update

Aplikasi telah di-update untuk support PHP 8.1.16+

**Changes:**

-   PHP requirement: `^8.2` → `^8.1`
-   Laravel framework: `^12.0` → `^11.0`

## 🚀 Setup di Server (PHP 8.1.16)

### Step 1: Pull Update Terbaru dari GitHub

```bash
cd /var/www/html/streaming-app

# Pull perubahan terbaru
git pull origin main

# Atau jika fresh install
git clone git@github.com:mrsnoowman/streaming-app.git
cd streaming-app
```

### Step 2: Reinstall Dependencies

```bash
# Hapus vendor lama dan composer.lock
rm -rf vendor
rm composer.lock

# Install dependencies dengan PHP 8.1
composer install --no-dev --optimize-autoloader

# Atau jika ada error, gunakan:
composer update --no-dev --optimize-autoloader
```

### Step 3: Setup Environment

```bash
# Copy environment template
cp env.production.template .env

# Edit .env dengan configurasi production
nano .env
```

**Configure .env:**

```env
APP_NAME="CCTV Streaming Dashboard"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cctv_live
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=true
CACHE_STORE=database

LOG_CHANNEL=daily
LOG_LEVEL=error
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Run Migrations

```bash
# Create cache and session tables
php artisan cache:table
php artisan session:table
php artisan queue:table

# Run all migrations
php artisan migrate --force
```

### Step 6: Set Permissions

```bash
# Set ownership (ganti www-data dengan user web server Anda)
sudo chown -R www-data:www-data /var/www/html/streaming-app
sudo chmod -R 755 /var/www/html/streaming-app

# Set storage permissions
sudo chmod -R 775 /var/www/html/streaming-app/storage
sudo chmod -R 775 /var/www/html/streaming-app/bootstrap/cache
```

### Step 7: Optimize for Production

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 8: Test Application

```bash
# Test if application runs
php artisan serve --host=0.0.0.0 --port=8000

# Or test with web server
curl http://localhost
```

## 🔧 Web Server Configuration

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/streaming-app/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Restart Nginx:**

```bash
sudo nginx -t
sudo systemctl restart nginx
```

### Apache Configuration

Enable required modules:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**.htaccess** sudah included di `public/.htaccess`

## 🗄️ Database Setup

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE cctv_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cctv_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON cctv_live.* TO 'cctv_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 📊 Insert Sample Data

```sql
# Login to database
mysql -u cctv_user -p cctv_live

# Insert sample CCTV
INSERT INTO cctvs (lokasi, status, lastupdate, http_link, ip) VALUES
('AKSES CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/327294924686013390782781.m3u8', '172.61.0.201'),
('PTZ SS CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/357514173892609384751207.m3u8', '172.61.0.204');

# Insert sample VMS
INSERT INTO vms (lokasi, status, lastupdate, http_link, ip) VALUES
('VMS TOLL GATE', 1, NOW(), 'https://your-vms-stream.m3u8', '192.168.1.100'),
('VMS PLAZA', 1, NOW(), 'https://your-vms-stream2.m3u8', '192.168.1.101');

# Create admin user (password: password123)
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES
('Administrator', 'admin@tollroad.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
```

**Login Credentials:**

-   Email: `admin@tollroad.com`
-   Password: `password123`

## 🔍 Troubleshooting

### Issue: "Class not found" errors

```bash
composer dump-autoload
php artisan clear-compiled
php artisan cache:clear
```

### Issue: Permission denied

```bash
sudo chown -R www-data:www-data /var/www/html/streaming-app
sudo chmod -R 775 storage bootstrap/cache
```

### Issue: Database connection error

```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u cctv_user -p

# Check .env credentials
nano .env
```

### Issue: 500 Internal Server Error

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/error.log
# or
tail -f /var/log/apache2/error.log

# Enable debug mode temporarily
nano .env
# Set APP_DEBUG=true (ONLY for debugging, set back to false!)
```

## 📝 Quick Commands

```bash
# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list

# Check application status
php artisan about

# Test database connection
php artisan migrate:status

# View logs
tail -f storage/logs/laravel.log
```

## ✅ Verification Checklist

-   [ ] PHP 8.1.16 installed and working
-   [ ] Composer dependencies installed
-   [ ] .env configured correctly
-   [ ] Database created and connected
-   [ ] Migrations run successfully
-   [ ] Permissions set correctly
-   [ ] Web server configured
-   [ ] Application accessible via browser
-   [ ] Login page works
-   [ ] CCTV streams display
-   [ ] VMS page accessible

## 🚀 Access Application

After successful setup:

-   **Login Page**: http://your-domain.com/login
-   **CCTV Dashboard**: http://your-domain.com/
-   **VMS Dashboard**: http://your-domain.com/vms

---

**Server**: PHP 8.1.16 Compatible ✅  
**Status**: Ready for Production  
**Last Updated**: $(date)
