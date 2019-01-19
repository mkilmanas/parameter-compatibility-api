#!/usr/bin/env bash

DEFAULT_APP_DIR='/var/www'

APP_DIR=${1:-$DEFAULT_APP_DIR}

/sbin/start-stop-daemon \
    --start \
    --quiet \
    --pidfile /tmp/symfony-webserver.pid \
    --make-pidfile \
    --background \
    --user www-data \
    --startas /bin/bash \
    -- -c "exec php7.2 $APP_DIR/bin/console --env=dev server:run 0.0.0.0:8080 > $APP_DIR/var/logs/webserver.log 2>&1"

