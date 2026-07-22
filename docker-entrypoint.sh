#!/bin/sh
set -e

# Ensure .env file exists
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Ensure APP_KEY is generated
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Ensure database file exists if using sqlite
mkdir -p database
touch database/database.sqlite

# Run migrations and seed the database
echo "Running database migrations..."
php artisan migrate

echo "Seeding the database..."
php artisan migrate:fresh --seed

# Execute the container command (php artisan serve)
exec "$@"
