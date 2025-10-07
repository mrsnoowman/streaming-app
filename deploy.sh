#!/bin/bash

# CCTV Streaming Dashboard - Production Deployment Script
# Run this script to deploy the application to production

set -e

echo "🚀 Starting CCTV Streaming Dashboard Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    print_error "Please do not run this script as root for security reasons"
    exit 1
fi

# Check if Laravel application directory exists
if [ ! -d "app" ] || [ ! -f "artisan" ]; then
    print_error "This doesn't appear to be a Laravel application directory"
    exit 1
fi

print_status "Checking system requirements..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
if [ "$(printf '%s\n' "8.2.0" "$PHP_VERSION" | sort -V | head -n1)" != "8.2.0" ]; then
    print_error "PHP 8.2 or higher is required. Current version: $PHP_VERSION"
    exit 1
fi
print_success "PHP version check passed: $PHP_VERSION"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    exit 1
fi
print_success "Composer is available"

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_warning ".env file not found. Creating from template..."
    if [ -f "env.production.template" ]; then
        cp env.production.template .env
        print_warning "Please edit .env file with your production configuration before continuing"
        print_warning "Run: nano .env"
        exit 1
    else
        print_error "No .env template found. Please create .env file manually"
        exit 1
    fi
fi

print_status "Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

print_status "Generating application key..."
php artisan key:generate --force

print_status "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

print_status "Running database migrations..."
php artisan migrate --force

print_status "Creating cache and session tables..."
php artisan cache:table --force
php artisan session:table --force
php artisan queue:table --force

print_status "Running migrations for new tables..."
php artisan migrate --force

print_status "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs

print_status "Creating storage symlink..."
php artisan storage:link

print_status "Clearing and warming up caches..."
php artisan cache:clear
php artisan config:cache

print_success "Deployment completed successfully!"

echo ""
echo "📋 Post-deployment checklist:"
echo "1. ✅ Update .env file with production values"
echo "2. ✅ Configure web server (Nginx/Apache)"
echo "3. ✅ Set up SSL certificate"
echo "4. ✅ Configure firewall"
echo "5. ✅ Set up database backup"
echo "6. ✅ Test all functionality"
echo "7. ✅ Monitor logs for any issues"
echo ""
echo "🔗 Important URLs:"
echo "- Dashboard: https://your-domain.com"
echo "- Login: https://your-domain.com/login"
echo "- VMS: https://your-domain.com/vms"
echo ""
echo "📚 For detailed configuration, see PRODUCTION_DEPLOYMENT.md"
echo ""
print_success "CCTV Streaming Dashboard is ready for production! 🎉"
