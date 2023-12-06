#!/bin/sh
set -e


if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    composer install
    chown -R www-data:"$(whoami)" *
    chmod +x bin/console
fi

exec "$@"