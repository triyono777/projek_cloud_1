#!/usr/bin/env sh
set -eu

cd /var/www

export APP_URL="${APP_URL:-${RAILWAY_PUBLIC_DOMAIN:+https://${RAILWAY_PUBLIC_DOMAIN}}}"
export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-${MYSQLHOST:-}}"
export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-}}"
export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-}}"
export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-}}"

if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -n "${DB_HOST:-}" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  until nc -z "${DB_HOST}" "${DB_PORT:-3306}"; do
    sleep 2
  done
fi

php artisan config:clear >/dev/null 2>&1 || true
php artisan migrate --force
php artisan db:seed --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
