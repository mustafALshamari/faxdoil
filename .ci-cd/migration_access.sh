#!/bin/bash

chown -R root:www-data /var/www/api
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
chgrp -R www-data /var/www/api/storage /var/www/api/bootstrap/cache
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache

php /var/www/api/artisan key:generate
php /var/www/api/artisan passport:install
php /var/www/api/artisan migrate
php /var/www/api/artisan passport:client --personal
php /var/www/api/artisan db:seed --class=AdminTableDataSeeder
php /var/www/api/artisan l5-swagger:generate
