#!/usr/bin/env bash

DEFAULT_APP_DIR='/var/www'

APP_DIR=${1:-$DEFAULT_APP_DIR}

apt-add-repository ppa:ondrej/php -y
apt-get update
apt-get install -y \
    php7.2-cli \
    php7.2-ctype \
    php7.2-iconv \
    php7.2-json \
    php7.2-mbstring \
    php7.2-mysql \
    php7.2-gmp \
    php7.2-xml

cd $APP_DIR
cp .env.dist .env
cp phpspec.yml.dist phpspec.yml
cp behat.yml.dist behat.yml
composer install

mkdir -p /dev/shm/symfony/cache/
chmod 777 /dev/shm/symfony/cache/

bin/console doctrine:database:create -n
bin/console doctrine:schema:update --force -n
bin/console doctrine:fixtures:load --group=example -n
