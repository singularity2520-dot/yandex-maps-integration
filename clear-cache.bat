@echo off
echo Очистка всех кешей Laravel...
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
echo Очистка завершена!
pause