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

# initialisation de la base de données si elle n'existe pas
if [ ! -s ./sql/db.sqlite ]
then
    php artisan --no-interaction migrate --seed

    # génération des clés
    php artisan key:generate

    # vider le cache
    php artisan config:clear

    # configurer Passport for Mercator API : https://dbarzin.github.io/mercator/api/
    php artisan passport:install
fi

# start application
php artisan serve --host=0.0.0.0
