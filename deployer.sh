set -e

echo "Project Deploing..."

(php artisan down --message 'The app is being updated')

    git pull origin master

php artisan up

echo "Application Deployed"