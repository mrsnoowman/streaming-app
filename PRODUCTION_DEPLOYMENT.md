# CCTV Streaming Dashboard - Production Deployment Guide

## 🚀 Production Checklist

### 1. Environment Configuration

#### Create Production .env File

```bash
# Copy .env.example to .env.production
cp .env.example .env.production
```

#### Production .env Configuration

```env
APP_NAME="CCTV Streaming Dashboard"
APP_ENV=production
APP_KEY=base64:YOUR_32_CHARACTER_SECRET_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=cctv_live
DB_USERNAME=your-db-username
DB_PASSWORD=your-secure-password

# Session & Cache Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=true
CACHE_STORE=database

# Logging Configuration
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="CCTV Dashboard"

# Queue Configuration (Optional)
QUEUE_CONNECTION=database
```

### 2. Server Requirements

#### Minimum Requirements

-   **PHP**: 8.2 or higher
-   **MySQL**: 8.0 or higher
-   **Web Server**: Apache 2.4+ or Nginx 1.18+
-   **Memory**: 2GB RAM minimum
-   **Storage**: 10GB free space

#### Recommended Requirements

-   **PHP**: 8.3
-   **MySQL**: 8.0
-   **Web Server**: Nginx 1.24+
-   **Memory**: 4GB RAM
-   **Storage**: 20GB SSD

### 3. PHP Extensions Required

```bash
php-mysql
php-curl
php-gd
php-mbstring
php-xml
php-zip
php-bcmath
php-intl
php-fileinfo
php-openssl
php-tokenizer
```

### 4. Deployment Steps

#### Step 1: Upload Application Files

```bash
# Upload all files to server
scp -r ./streaming-app/* user@server:/var/www/cctv-dashboard/
```

#### Step 2: Set Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/cctv-dashboard/
sudo chmod -R 755 /var/www/cctv-dashboard/

# Set storage permissions
sudo chmod -R 775 /var/www/cctv-dashboard/storage
sudo chmod -R 775 /var/www/cctv-dashboard/bootstrap/cache
```

#### Step 3: Install Dependencies

```bash
cd /var/www/cctv-dashboard/
composer install --no-dev --optimize-autoloader
```

#### Step 4: Generate Application Key

```bash
php artisan key:generate
```

#### Step 5: Run Database Migrations

```bash
php artisan migrate --force
```

#### Step 6: Create Cache Tables

```bash
php artisan cache:table
php artisan session:table
php artisan queue:table
php artisan migrate --force
```

#### Step 7: Clear and Cache Configuration

```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Web Server Configuration

#### Nginx Configuration

```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name your-domain.com;
    root /var/www/cctv-dashboard/public;

    # SSL Configuration (if using HTTPS)
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Main location block
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
}
```

#### Apache Configuration (.htaccess)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Hide sensitive files
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

### 6. Database Setup

#### Create Database and User

```sql
CREATE DATABASE cctv_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cctv_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON cctv_live.* TO 'cctv_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Sample Data Insertion

```sql
-- Insert sample CCTV data
INSERT INTO cctvs (lokasi, status, lastupdate, http_link, ip) VALUES
('AKSES CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/327294924686013390782781.m3u8', '172.61.0.201'),
('PTZ SS CIAWI', 1, NOW(), 'https://www.tjt-info.co.id/LiveApp/streams/357514173892609384751207.m3u8', '172.61.0.204');

-- Insert sample VMS data
INSERT INTO vms (lokasi, status, lastupdate, http_link, ip) VALUES
('VMS TOLL GATE', 1, NOW(), 'https://your-vms-stream-url.m3u8', '192.168.1.100'),
('VMS PLAZA', 1, NOW(), 'https://your-vms-stream-url2.m3u8', '192.168.1.101');

-- Create admin user
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES
('Administrator', 'admin@tollroad.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
```

### 7. Monitoring and Maintenance

#### Set Up Log Rotation

```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/cctv-dashboard
```

```bash
/var/www/cctv-dashboard/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

#### Set Up Cron Jobs

```bash
# Add to crontab
crontab -e
```

```bash
# Clear application cache daily
0 2 * * * cd /var/www/cctv-dashboard && php artisan cache:clear

# Clear route cache weekly
0 3 * * 0 cd /var/www/cctv-dashboard && php artisan route:clear

# Clear view cache weekly
0 4 * * 0 cd /var/www/cctv-dashboard && php artisan view:clear
```

#### Performance Monitoring

```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Monitor application logs
tail -f /var/www/cctv-dashboard/storage/logs/laravel.log

# Monitor system resources
htop
```

### 8. Security Checklist

-   ✅ **Environment**: Set `APP_ENV=production`
-   ✅ **Debug Mode**: Set `APP_DEBUG=false`
-   ✅ **Application Key**: Generate unique `APP_KEY`
-   ✅ **Database**: Use strong passwords
-   ✅ **Session Encryption**: Enabled
-   ✅ **HTTPS**: Configure SSL certificate
-   ✅ **Security Headers**: Implemented
-   ✅ **File Permissions**: Properly set
-   ✅ **Sensitive Files**: Protected (.env, logs)
-   ✅ **Rate Limiting**: Consider implementing
-   ✅ **Firewall**: Configure UFW or iptables

### 9. Backup Strategy

#### Database Backup

```bash
#!/bin/bash
# backup-db.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u cctv_user -p cctv_live > /backup/cctv_db_$DATE.sql
gzip /backup/cctv_db_$DATE.sql
```

#### Application Backup

```bash
#!/bin/bash
# backup-app.sh
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf /backup/cctv_app_$DATE.tar.gz /var/www/cctv-dashboard/
```

### 10. Troubleshooting

#### Common Issues

1. **Permission Denied**: Check file ownership and permissions
2. **Database Connection**: Verify database credentials and connectivity
3. **Memory Limit**: Increase PHP memory limit if needed
4. **Upload Limit**: Configure PHP upload limits for large video streams
5. **SSL Issues**: Ensure SSL certificate is valid and properly configured

#### Performance Optimization

1. **Enable OPcache**: Configure PHP OPcache for better performance
2. **Use CDN**: Consider using CDN for static assets
3. **Database Indexing**: Add indexes to frequently queried columns
4. **Redis**: Consider using Redis for session and cache storage
5. **Load Balancing**: Implement load balancing for high traffic

### 11. Post-Deployment Testing

#### Functional Tests

-   [ ] Login functionality works
-   [ ] CCTV streams load and play
-   [ ] VMS streams load and play
-   [ ] Pagination works correctly
-   [ ] Theme switching works
-   [ ] Mobile responsiveness
-   [ ] Error pages display correctly

#### Performance Tests

-   [ ] Page load times < 3 seconds
-   [ ] Video streams start within 5 seconds
-   [ ] Concurrent user support (test with multiple users)
-   [ ] Memory usage monitoring
-   [ ] Database query performance

### 12. Support and Maintenance

#### Regular Maintenance Tasks

-   **Weekly**: Review logs, check disk space, update dependencies
-   **Monthly**: Security updates, performance optimization review
-   **Quarterly**: Full backup restoration test, security audit

#### Emergency Procedures

-   **Database Recovery**: Restore from latest backup
-   **Application Rollback**: Deploy previous stable version
-   **Security Incident**: Follow incident response plan

---

## 📞 Support Contact

For technical support or questions about this deployment:

-   **Email**: support@your-domain.com
-   **Documentation**: [Link to full documentation]
-   **Emergency**: [Emergency contact details]

---

**Last Updated**: $(date)
**Version**: 1.0.0
**Deployment Guide Version**: 1.0
