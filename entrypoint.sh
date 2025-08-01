#!/bin/bash

# Run Laravel setup commands
php artisan config:cache
php artisan migrate --force

# Start PHP-FPM
exec php-fpm
