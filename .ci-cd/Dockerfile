FROM php:7.2-fpm
WORKDIR /var/www/api

EXPOSE 80

RUN apt-get update -y \
    && apt-get install -y vim git nginx zip curl \
    && apt-get install libicu-dev -y \
    && apt-get remove libicu-dev icu-devtools -y \
    && mkdir -p /run/nginx && \
        curl --silent --show-error https://getcomposer.org/installer | php && \
        mv ./composer.phar /usr/bin/composer

# Copy nginx config (may be frequenlty changed becuase of changing of URLs or params)
COPY .ci-cd/default.conf /etc/nginx/sites-enabled/default

# Copy files
COPY ./ ./

# PHP dependecies
COPY composer* ./
COPY database ./database

RUN composer install

RUN sh ".ci-cd/migration_access.sh"

CMD php-fpm -D && nginx -g 'daemon off;'

