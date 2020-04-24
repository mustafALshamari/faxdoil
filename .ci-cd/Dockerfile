FROM php:7.2-fpm
WORKDIR /var/www/api


RUN apt-get update -y \
    && apt-get install -y cron vim nginx zip curl \
    && apt-get install libicu-dev -y \
    && apt-get remove libicu-dev icu-devtools -y \
    && mkdir -p /run/nginx && \
        curl --silent --show-error https://getcomposer.org/installer | php && \
        mv ./composer.phar /usr/bin/composer

# PHP dependecies
COPY composer* ./
COPY database ./database
RUN  composer install --no-plugins --no-scripts

# Copy nginx config (may be frequenlty changed becuase of changing of URLs or params)
COPY .ci-cd/default.conf /etc/nginx/sites-enabled/default
COPY .ci-cd/php.ini /usr/local/etc/php/conf.d/
# Copy left files
COPY ./ ./
# Block access to files bootstrap
RUN sh ".ci-cd/migration_access.sh"
CMD service cron start && php-fpm -D && nginx -g 'daemon off;'

