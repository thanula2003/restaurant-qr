#!/usr/bin/env bash
# Creates a fresh Laravel app and drops the restaurant site into it.
# Usage:  bash setup.sh [app-folder-name]
set -e
SRC="$(cd "$(dirname "$0")" && pwd)"
APP="${1:-restaurant}"

composer create-project laravel/laravel "$APP"

for d in app config public resources routes; do
  cp -R "$SRC/$d" "$APP/"
done

cd "$APP"
echo ""
echo "Done. Starting the site at http://127.0.0.1:8000  (Ctrl+C to stop)"
php artisan serve
