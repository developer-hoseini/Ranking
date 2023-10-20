#!/bin/bash

# Generate application key and run migrations and seeders
cd /var/www/html && php artisan migrate && php artisan db:seed && php artisan optimize:clear

# Start supervisord
/usr/bin/supervisord
