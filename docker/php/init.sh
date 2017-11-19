#!/bin/sh

set -e

cd /var/www && composer -n install

cd /var/www && php bin/console game:install

docker-php-entrypoint php-fpm