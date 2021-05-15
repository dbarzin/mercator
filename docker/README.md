# Présentation

Ce dossier contient les éléments permettant de dockeriser l'application Mercator avec docker-compose.

# Utilisation

## Initialisation

copier le dépot source

    git clone https://github.com/dbarzin/mercator

Aller dans le répertoire mercator

    cd mercator

Recopier le fichier de configuration ../.env.example

    cp  ../.env.example .env

Toutes les valeurs du fichier peuvent être modifiées selon votre convenance, mais celle-ci doivent être fixées :

URL du service d'un point de vue externe (en dehors du reverse-proxy)

    APP_URL=https://mercator.mydomain.com/
    ASSET_URL=https://mercator.mydomain.com/

Base de données Mysql

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=mercator
    DB_USERNAME=mercator
    DB_PASSWORD=mercator

- la valeur pour `APP_URL` doit correspondre au nom du service dans le `docker-compose.yml`
- de même, le nom du service est db dans ce même fichier (`DB_HOST`)

## Reverse-proxy

Deux modèles de configuration du reverse-proxy sont fournis dans le répertoires assets pour Apache et Nginx. D'autre part, la commande permettant de générer le certificat let's Encrypt sera de la forme:

    certbot --nginx --non-interactive --agree-tos --email admin@mydomain.com --no-eff-email --domain mercator.mydomain.com

## Start

Avant le démarrage de l'application, il est nécessaire de procéder à un build du container ; ensuite, l'ordre habituel des commandes docker-compose s'enchainent.

Aller dans le répertoire d'exploitation docker

    cd docker

Recopier le fichier docker-compose.yml.tmp afin d'apporter vos propres adaptations

    cp docker-compose.yml.tmpl docker-compose.yml

Build du container mercator en local

    docker-compose build

Démarrage de l'application:
 - app : service docker lié à l'application mercator
 - db : base de données
    docker-compose up -d

Pour observer les logs applicatifs

    docker-compose logs -ft

## Restart

Aller dans le répertoire d'exploitation docker

    cd docker

Arret des 2 services db et app

    docker-compose down

Redémarrage de l'application:

    docker-compose up -d

Il est nécessaire de faire un restart à chaque fois :

- que le code source est modifié
- que la configuration est modifiée
- que l'environnement d'exécution est modifié en particulier `entrypoint.sh`

## Debug

Il est possible d'ouvrir un shell intéractif sur l'un des deux services app ou db

ouverture d'un shell ; app ou db

    docker-compose exec app /bin/bash

# Exploitation

## Backup

- les seules données à sauvegarder résident dans la base Mysql. Des éléments sont fournis sur dans la procédure d'[installation](https://github.com/dbarzin/mercator/blob/master/INSTALL.md) de Mercator.
- le script `./bin/mysql/backup` permet de réaliser l'opération de backup simplement ; il est monté dans le container docker par un `bind` (primitive `volumes` dans le docker-compose.yml)
- pour l'activer il suffit d'invoquer : `docker-compose exec db /app/backup`
- la base de données  est sauvegardée dans un volume local : `./data/backup/sql/mercator.sql.gz`

## Restore

fixme.

# FIXME

- donner des indications concernant la procédure de MAJ applicative.
- le fonctionnement de l'application derrière un reverse-proxy ne fonctionne pas ; voir [ticket](https://github.com/mqu/mercator/issues/1) :
  - bug résiduel concernant le formulaire d'authentification qui passe en HTTP(80) au lieu de HTTPS(443). Le reste du paramétrage semble OK.


