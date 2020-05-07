#!/bin/bash

info () {
    lgreen='\033[1;32m'
    nc='\033[0m'
    printf "${lgreen}[info] ${@}${nc}\n"
}

set_env () {
    info "Set environment"
    path_to_api="/var/www/api"
    path_to_storage="${path_to_api}/storage"
    path_to_cache="${path_to_api}/bootstrap/cache"

}

permission () {
    info "Configuration permission"
    chown -R www-data:www-data "${path_to_api}"
    chmod -R 775 "${path_to_storage} ${path_to_cache}"
}

php_action () {
    info "Php commands execution"
    php_command=( 'key:generate'
    		  'passport:install'
		  'migrate:fresh'
		  "passport:client --personal"
		  "db:seed --class=AdminTableDataSeeder"
		  'l5-swagger:generate'
		  'cache:clear'
		  'view:clear'
		  'route:cache'
    )

    path_to_artisan="/var/www/api/artisan"

    for action in "${php_command[@]}"; do
        info "php ${path_to_artisan} ${action}"
        php ${path_to_artisan} ${action}
    done
}

entrypoint () {
    info "Launch of services php-fpm and nginx"
    php-fpm -D && nginx -g 'daemon off;'
}

main () {
    set_env
    php_action
    permission
    entrypoint
}

main

