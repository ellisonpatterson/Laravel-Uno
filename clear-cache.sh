composer dump-autoload
composer install --optimize-autoloader
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan laroute:generate