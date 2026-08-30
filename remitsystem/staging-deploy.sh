#!/bin/sh
set -e

echo "Deploying application ..."

source ~/.bashrc

# Enter maintenance mode
(php83 artisan down --secret="bypass") || true

    # Update codebase
    git fetch origin staging-deploy
    git reset --hard origin/staging-deploy

    # Install dependencies based on lock file
    composer83 install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    php83 artisan migrate --force

    # Clear cache
    php83 artisan optimize:clear


# Exit maintenance mode
php83 artisan up

echo "Application deployed!"
