# CCTV Streaming Dashboard - Production Ready

## 🎯 Overview

This is a production-ready CCTV and VMS streaming dashboard built with Laravel 11. The application provides real-time video streaming capabilities with a professional, responsive interface designed for toll road monitoring systems.

## ✨ Features

### Core Features

-   **Real-time CCTV Streaming**: HLS video streaming with auto-play
-   **VMS Integration**: Variable Message Sign monitoring
-   **Responsive Design**: Mobile and desktop optimized
-   **Dark/Light Mode**: User preference with persistence
-   **Pagination**: 10 streams per page for optimal performance
-   **User Authentication**: Secure login system
-   **Professional UI**: Glass morphism design with BOCIMI branding

### Security Features

-   **CSRF Protection**: Built-in Laravel CSRF tokens
-   **Security Headers**: XSS, clickjacking, and content-type protection
-   **Session Encryption**: Encrypted session storage
-   **Input Validation**: Server-side validation for all inputs
-   **SQL Injection Protection**: Eloquent ORM protection
-   **Authentication Middleware**: Protected routes

### Performance Features

-   **Database Caching**: 5-minute cache for pagination
-   **Lazy Loading**: Video streams load on demand
-   **Optimized Queries**: Efficient database queries
-   **Static Asset Optimization**: Minified CSS/JS
-   **Session Management**: Database-based sessions

## 🚀 Quick Start

### Prerequisites

-   PHP 8.2+
-   MySQL 8.0+
-   Composer
-   Web Server (Apache/Nginx)

### Installation

1. **Clone/Upload** the application files
2. **Run deployment script**:
    ```bash
    chmod +x deploy.sh
    ./deploy.sh
    ```
3. **Configure .env** file with your production settings
4. **Set up web server** configuration
5. **Test the application**

### Environment Configuration

Copy `env.production.template` to `.env` and update:

-   Database credentials
-   Application URL
-   Mail settings (optional)
-   Security keys

## 📁 Project Structure

```
streaming-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Authentication logic
│   │   │   └── DashboardController.php # CCTV/VMS data
│   │   └── Middleware/
│   │       ├── EnsureUserIsAuthenticated.php
│   │       └── SecurityHeaders.php     # Security middleware
│   ├── Models/
│   │   ├── Cctv.php                    # CCTV model
│   │   ├── Vms.php                     # VMS model
│   │   └── User.php                    # User model
│   └── Exceptions/
│       └── ProductionExceptionHandler.php
├── database/
│   ├── migrations/
│   │   ├── create_cctvs_table.php
│   │   ├── create_vms_table.php
│   │   └── create_users_table.php
│   └── seeders/
├── public/
│   └── css/
│       └── variables.css               # CSS variables for theming
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login_simple.blade.php  # Login page
│       ├── errors/
│       │   ├── 404.blade.php           # Custom error pages
│       │   └── 500.blade.php
│       ├── cctv.blade.php              # CCTV dashboard
│       └── vms.blade.php               # VMS dashboard
├── routes/
│   └── web.php                         # Application routes
├── deploy.sh                           # Deployment script
├── PRODUCTION_DEPLOYMENT.md            # Detailed deployment guide
└── env.production.template             # Environment template
```

## 🔧 Configuration

### Database Setup

```sql
CREATE DATABASE cctv_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cctv_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON cctv_live.* TO 'cctv_user'@'localhost';
```

### Sample Data

```sql
-- CCTV Data
INSERT INTO cctvs (lokasi, status, lastupdate, http_link, ip) VALUES
('AKSES CIAWI', 1, NOW(), 'https://your-stream-url.m3u8', '172.61.0.201');

-- VMS Data
INSERT INTO vms (lokasi, status, lastupdate, http_link, ip) VALUES
('VMS TOLL GATE', 1, NOW(), 'https://your-vms-url.m3u8', '192.168.1.100');

-- Admin User
INSERT INTO users (name, email, password, email_verified_at) VALUES
('Administrator', 'admin@tollroad.com', '$2y$10$...', NOW());
```

## 🎨 Customization

### Color Scheme

Edit `public/css/variables.css` to customize colors:

```css
:root {
    --primary-green: #10b981; /* Primary green */
    --primary-dark-green: #059669; /* Dark green */
    --bg-primary: #000000; /* Background */
    /* ... more variables */
}
```

### Logo and Branding

-   Update logo URLs in views
-   Modify CSS variables for colors
-   Customize header text and styling

## 🔒 Security

### Implemented Security Measures

-   ✅ CSRF Protection
-   ✅ XSS Prevention
-   ✅ SQL Injection Protection
-   ✅ Security Headers
-   ✅ Session Encryption
-   ✅ Input Validation
-   ✅ Authentication Required
-   ✅ Rate Limiting Ready

### Additional Recommendations

-   Use HTTPS in production
-   Regular security updates
-   Monitor access logs
-   Implement firewall rules
-   Regular backups

## 📊 Performance

### Optimizations Applied

-   Database query caching
-   Lazy loading for videos
-   Pagination (10 items/page)
-   Optimized asset loading
-   Session database storage
-   View and route caching

### Monitoring

-   Check application logs: `storage/logs/laravel.log`
-   Monitor database performance
-   Track memory usage
-   Monitor video stream performance

## 🛠️ Maintenance

### Regular Tasks

-   **Daily**: Monitor logs and performance
-   **Weekly**: Clear caches, check disk space
-   **Monthly**: Update dependencies, security review
-   **Quarterly**: Full backup test, performance audit

### Backup Strategy

-   Database: Daily automated backups
-   Application: Weekly full backups
-   Configuration: Version control

## 📞 Support

### Troubleshooting

1. Check logs: `tail -f storage/logs/laravel.log`
2. Verify database connection
3. Check file permissions
4. Test video stream URLs
5. Verify SSL certificate

### Common Issues

-   **Video not loading**: Check stream URL and network connectivity
-   **Login issues**: Verify user credentials and database
-   **Performance**: Check server resources and caching
-   **SSL errors**: Verify certificate configuration

## 📈 Monitoring

### Key Metrics to Monitor

-   Response times
-   Memory usage
-   Database performance
-   Video stream availability
-   User authentication success rate
-   Error rates

### Log Files

-   Application: `storage/logs/laravel.log`
-   Web server: `/var/log/nginx/` or `/var/log/apache2/`
-   System: `/var/log/syslog`

## 🔄 Updates

### Updating the Application

1. Backup current version
2. Update code files
3. Run migrations: `php artisan migrate`
4. Clear caches: `php artisan cache:clear`
5. Test functionality
6. Monitor for issues

### Version Control

-   Use Git for version control
-   Tag releases for easy rollback
-   Document changes in CHANGELOG

---

## 📋 Production Checklist

Before going live, ensure:

-   [ ] Environment configured for production
-   [ ] Database properly set up with sample data
-   [ ] SSL certificate installed and working
-   [ ] Security headers implemented
-   [ ] Backup strategy in place
-   [ ] Monitoring configured
-   [ ] Error pages customized
-   [ ] Performance tested
-   [ ] Security tested
-   [ ] Documentation updated

---

**Version**: 1.0.0  
**Last Updated**: $(date)  
**Status**: Production Ready ✅
