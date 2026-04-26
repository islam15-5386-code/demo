#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "Clearing config cache..."
php artisan config:clear

echo "Clearing application cache..."
php artisan cache:clear

echo "Refreshing database with seed data..."
php artisan migrate:fresh --seed

echo "Database reset completed."
