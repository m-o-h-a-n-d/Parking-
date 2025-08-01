#!/bin/bash

echo "⏳ Waiting for DB to be ready..."

until nc -z -v -w30 "$DB_HOST" "$DB_PORT"
do
  echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
  sleep 5
done

echo "✅ DB is up!"

php artisan config:cache
php artisan migrate --force

exec php-fpm
