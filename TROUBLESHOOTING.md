# Troubleshooting Guide - CCTV Streaming Dashboard

## Common Issues and Solutions

### 1. BadMethodCallException: Method hasPages does not exist

**Error:**

```
BadMethodCallException
Method Illuminate\Support\Collection::hasPages does not exist.
```

**Cause:**
This error occurs when pagination objects are cached using `Cache::remember()`. The cached pagination object loses its pagination methods after serialization.

**Solution:**
✅ **Already Fixed** in the latest version. Pull the latest changes:

```bash
git pull origin main
php artisan config:clear
php artisan cache:clear
```

**Technical Explanation:**

-   Pagination objects (`LengthAwarePaginator`) can't be serialized properly in cache
-   After deserialization, only base `Collection` methods remain
-   Pagination methods like `hasPages()`, `previousPageUrl()`, `nextPageUrl()` are lost

---

### 2. PHP Version Compatibility Error

**Error:**

```
Your Composer dependencies require a PHP version ">= 8.2.0". You are running 8.1.16.
```

**Solution:**
✅ **Already Fixed**. Application now supports PHP 8.1+

```bash
git pull origin main
rm -rf vendor composer.lock
composer install --no-dev --optimize-autoloader
```

See [FIX_PHP81_COMPATIBILITY.md](FIX_PHP81_COMPATIBILITY.md) for details.

---

### 3. Database Connection Error

**Error:**

```
SQLSTATE[HY000] [2002] Connection refused
```

**Solutions:**

**Check MySQL is running:**

```bash
sudo systemctl status mysql
# or
sudo systemctl start mysql
```

**Verify .env credentials:**

```bash
nano .env
```

Check these values:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cctv_live
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

**Test connection:**

```bash
mysql -u your_user -p
```

---

### 4. Permission Denied Errors

**Error:**

```
The stream or file "storage/logs/laravel.log" could not be opened: failed to open stream: Permission denied
```

**Solution:**

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/html/streaming-app

# Set proper permissions
sudo chmod -R 755 /var/www/html/streaming-app
sudo chmod -R 775 /var/www/html/streaming-app/storage
sudo chmod -R 775 /var/www/html/streaming-app/bootstrap/cache
```

---

### 5. 404 Not Found on Routes

**Error:**
Routes return 404 error

**Solutions:**

**Clear route cache:**

```bash
php artisan route:clear
php artisan route:cache
```

**Check web server configuration:**

For **Apache**, ensure `.htaccess` in `public/` directory exists and `mod_rewrite` is enabled:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

For **Nginx**, ensure proper configuration:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

### 6. Video Streams Not Loading

**Issues:**

-   Videos show "Loading..." indefinitely
-   Black screen on video players
-   "Camera Offline" message

**Solutions:**

**Check stream URLs:**

```bash
# Test stream URL directly
curl -I https://your-stream-url.m3u8
```

**Check database records:**

```sql
SELECT lokasi, status, http_link FROM cctvs;
SELECT lokasi, status, http_link FROM vms;
```

**Verify status field:**

-   Ensure `status = 1` for active cameras
-   Update status: `UPDATE cctvs SET status = 1 WHERE id = X;`

**Check network connectivity:**

```bash
# Test connection to stream server
ping stream-server-domain.com
```

**Browser console:**

-   Open browser DevTools (F12)
-   Check Console tab for JavaScript errors
-   Check Network tab for failed requests

---

### 7. Session/Login Issues

**Error:**

-   Can't login / session expires immediately
-   CSRF token mismatch

**Solutions:**

**Clear session data:**

```bash
php artisan session:table
php artisan migrate
php artisan cache:clear
```

**Check session configuration:**

```bash
nano .env
```

Ensure:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=true
```

**Clear browser cache:**

-   Clear cookies for the domain
-   Use incognito/private window to test

---

### 8. 500 Internal Server Error

**Error:**
Generic 500 error page

**Solutions:**

**Enable debug mode (temporarily):**

```bash
nano .env
```

Set:

```env
APP_DEBUG=true
```

**Check Laravel logs:**

```bash
tail -f storage/logs/laravel.log
```

**Check web server logs:**

```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

**Check PHP errors:**

```bash
tail -f /var/log/php8.1-fpm.log
# or
tail -f /var/log/php8.2-fpm.log
```

**Common fixes:**

```bash
# Clear all caches
php artisan optimize:clear

# Regenerate autoload files
composer dump-autoload

# Check file permissions
sudo chown -R www-data:www-data /var/www/html/streaming-app
```

**Remember to disable debug after fixing:**

```env
APP_DEBUG=false
```

---

### 9. Composer Dependencies Issues

**Error:**

```
Your requirements could not be resolved to an installable set of packages.
```

**Solutions:**

**Update Composer:**

```bash
composer self-update
```

**Clear Composer cache:**

```bash
composer clear-cache
```

**Reinstall dependencies:**

```bash
rm -rf vendor composer.lock
composer install --no-dev --optimize-autoloader
```

**If still failing:**

```bash
composer update --no-dev --optimize-autoloader --with-all-dependencies
```

---

### 10. Migration Errors

**Error:**

```
SQLSTATE[42S01]: Base table or view already exists
```

**Solutions:**

**Check migration status:**

```bash
php artisan migrate:status
```

**Reset migrations (CAUTION: This will delete all data):**

```bash
php artisan migrate:fresh
```

**Or rollback and remigrate:**

```bash
php artisan migrate:rollback
php artisan migrate
```

**Specific table migration:**

```bash
php artisan migrate --path=/database/migrations/2025_10_07_024933_create_cctvs_table.php
```

---

### 11. Performance Issues

**Issues:**

-   Slow page load
-   High memory usage
-   Timeout errors

**Solutions:**

**Enable OPcache:**

```bash
# Edit PHP configuration
sudo nano /etc/php/8.1/fpm/php.ini
```

Enable:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
```

**Restart PHP-FPM:**

```bash
sudo systemctl restart php8.1-fpm
```

**Optimize Laravel:**

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Database optimization:**

```sql
-- Add indexes to frequently queried columns
ALTER TABLE cctvs ADD INDEX idx_lokasi (lokasi);
ALTER TABLE cctvs ADD INDEX idx_status (status);
```

**Limit concurrent streams:**

-   Pagination already limits to 10 streams per page
-   Consider reducing if still slow

---

### 12. Authentication Loop (Redirects back to login)

**Issue:**
User logs in successfully but gets redirected back to login

**Solutions:**

**Check middleware:**

```bash
php artisan route:list
```

Verify routes have correct middleware.

**Clear session:**

```bash
php artisan cache:clear
php artisan config:clear
```

**Check users table:**

```sql
SELECT * FROM users WHERE email = 'admin@tollroad.com';
```

**Recreate admin user:**

```sql
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
VALUES ('Administrator', 'admin@tollroad.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
```

---

## Quick Diagnostic Commands

```bash
# Check application status
php artisan about

# Check routes
php artisan route:list

# Check migrations
php artisan migrate:status

# Check configuration
php artisan config:show

# Clear everything
php artisan optimize:clear

# View logs
tail -f storage/logs/laravel.log
```

## Getting More Help

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Check web server logs**: `/var/log/nginx/` or `/var/log/apache2/`
3. **Enable debug mode**: Set `APP_DEBUG=true` in `.env` (temporarily)
4. **Check GitHub issues**: https://github.com/mrsnoowman/streaming-app/issues

---

**Last Updated**: October 2025  
**Version**: 1.0.0
