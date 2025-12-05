#!/bin/bash
set -e

# Function to wait for database
wait_for_db() {
    if [ -n "$DB_HOST" ]; then
        echo "Waiting for database connection..."
        until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" >/dev/null 2>&1; do
            echo "Database is unavailable - sleeping"
            sleep 2
        done
        echo "Database is ready!"
    fi
}

# Function to run Laravel commands
run_laravel_commands() {
    echo "Running Laravel optimizations..."
    
    # Clear and cache configurations
    php artisan config:clear
    php artisan config:cache
    
    # Cache routes for production
    php artisan route:clear
    php artisan route:cache
    
    # Cache views for production
    php artisan view:clear
    php artisan view:cache
    
    # Run migrations if needed (optional - comment out if you prefer manual migrations)
    # php artisan migrate --force
    
    # Optimize composer autoloader
    composer dump-autoload --optimize
    
    echo "Laravel optimizations completed!"
}

# Main execution
echo "Starting Laravel application..."

# Wait for database if configured
wait_for_db

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel optimizations
run_laravel_commands

# Start supervisor
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/laravel.conf