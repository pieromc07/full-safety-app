#!/usr/bin/env bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

php artisan key:generate --force --ansi || true
php artisan jwt:secret --force --ansi || true

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache || true

exec "$@"
