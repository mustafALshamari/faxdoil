echo "start of logging: `date "+%Y-%m-%d-%H-%M"`" >> /var/www/api/storage/logs/laravel.log
chown -R root:www-data /var/www/api
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
chgrp -R www-data /var/www/api/storage /var/www/api/bootstrap/cache
chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
chown -R www-data:www-data /srv
php /var/www/api/artisan migrate
php /var/www/api/artisan cache:clear
php /var/www/api/artisan view:clear
php /var/www/api/artisan config:cache
php /var/www/api/artisan optimize
php /var/www/api/artisan route:cache

#remove out current crontab
crontab -r
#write out current crontab
crontab -l > www-data #ubuntu
#echo new cron into cron file
echo "* * * * * /usr/local/bin/php /var/www/api/artisan schedule:run >> /srv/cron-`date "+%Y-%m-%d-%H-%M"`.log 2>&1" >> www-data #ubuntu
#install new cron file
crontab -u www-data www-data #ubuntu

