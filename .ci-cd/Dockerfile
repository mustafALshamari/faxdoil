FROM php:7.2-fpm
WORKDIR /var/www/api

EXPOSE 80

RUN apt-get update -y \
    && apt-get install -y vim git nginx zip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-install opcache \
    && apt-get install libicu-dev -y \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl \
    && apt-get remove libicu-dev icu-devtools -y \
    && mkdir -p /run/nginx && \
        curl --silent --show-error https://getcomposer.org/installer | php && \
        mv ./composer.phar /usr/bin/composer

RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/php-opocache-cfg.ini


# Copy nginx config (may be frequenlty changed becuase of changing of URLs or params)
COPY .ci-cd/default.conf /etc/nginx/sites-enabled/default

# Copy files
COPY ./ ./

# PHP dependecies
COPY composer* ./
COPY database ./database

RUN composer install

ENTRYPOINT [".ci-cd/entrypoint.sh"]

