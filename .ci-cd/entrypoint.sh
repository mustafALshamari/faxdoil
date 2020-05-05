#!/bin/bash

#Permission 
permission () {
    chown -R root:www-data /var/www/api
    chmod -R 775 /var/www/api/storage /var/www/api/bootstrap/cache
    chgrp -R www-data /var/www/api/storage /var/www/api/bootstrap/cache
}

php_action () {
    php /var/www/api/artisan key:generate
    php /var/www/api/artisan passport:install
    php /var/www/api/artisan migrate:refresh
    php /var/www/api/artisan passport:client --personal
    php /var/www/api/artisan db:seed --class=AdminTableDataSeeder
    php /var/www/api/artisan l5-swagger:generate
    php /var/www/api/artisan artisan cache:clear 
    php /var/www/api/artisan artisan view:clear 
    php /var/www/api/artisan artisan route:cache
}

entrypoint () { 
    php-fpm -D && nginx -g 'daemon off;'
}

main () {
    permission
    php_action
    entrypoint
}

main

