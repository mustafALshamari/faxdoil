#!/bin/bash

chown -R root:www-data /var/www/api
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
chgrp -R www-data /var/www/api/storage /var/www/api/bootstrap/cache
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache

php /var/www/api/artisan key:generate
