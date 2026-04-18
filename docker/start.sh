#!/usr/bin/env sh
set -eu

cd /var/www

if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -f .env ] && [ -n "${DB_HOST:-}" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  until nc -z "${DB_HOST}" "${DB_PORT:-3306}"; do
    sleep 2
  done
fi

php artisan config:clear >/dev/null 2>&1 || true
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
