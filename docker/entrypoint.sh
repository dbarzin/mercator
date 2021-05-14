#!/bin/bash

_wait_for_mysql_ready() {
        echo "waiting for ${DB_CONNECTION} to be ready ..."
        while ! echo "" | nc -q 1 -w 1 ${DB_HOST} ${DB_PORT} > /dev/null 2>&1; do 
          echo -n "." 
          sleep 1
        done
        echo "done."
}


cd /var/www/mercator
. .env

_wait_for_mysql_ready

# initialisation de la base de données
# FIXME: l'option --seed génère une erreur si on la relance plusieurs fois ; ne convient que pour la première fois
# php artisan --no-interaction migrate --seed 
php artisan --no-interaction migrate
php artisan --no-interaction migrate --seed 

# génération des clés
php artisan key:generate

# vider le cache
php artisan config:clear

# start application
php artisan serve --host=app
