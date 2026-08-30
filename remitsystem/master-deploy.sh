#!/bin/sh
set -e

echo "Deploying application ..."

source ~/.bashrc

# Enter maintenance mode
(php artisan down --secret="bypass") || true

    # Update codebase
    git fetch origin master-deploy
    git reset --hard origin/master-deploy

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    php artisan migrate --force

    # Clear cache
    php artisan optimize:clear


# Exit maintenance mode
php artisan up

echo "Application deployed!"
