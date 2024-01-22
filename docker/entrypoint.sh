#!/bin/bash

_wait_for_db_ready() {
        echo "waiting for ${DB_CONNECTION} to be ready ..."
        while ! echo "" | nc -q 1 -w 1 ${DB_HOST} ${DB_PORT} > /dev/null 2>&1; do
          echo -n "."
          sleep 1
        done
        echo "done."
}

cd /var/www/mercator
. .env

_wait_for_db_ready

# start application
php artisan serve --host=0.0.0.0
