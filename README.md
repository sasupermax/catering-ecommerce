## GENERAR LINK STORAGE DESPUES DE DEPLOY 
rm -rf public/storage
php artisan storage:link


## LIMPIADOR DE CACHE:
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
