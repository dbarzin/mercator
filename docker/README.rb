# présentation

ce dossier contient les éléments permettant de dockeriser l'application Mercator avec docker-compose.



# utilisation

## initialisation

```
# copier le dépot source
git clone https://github.com/dbarzin/mercator
# recopier le fichier de configuration ../.env.example
cp  ../.env.example .env
```

toutes les valeurs du fichier peuvent être modifiées selon votre convenance, mais celle-ci doivent être fixées :

```
APP_URL=http://app:8000/
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=mercator
DB_USERNAME=mercator
DB_PASSWORD=mercator
```

- la valeur pour `APP_URL` doit correspondre au nom du service dans le `docker-compose.yml`
- de même, le nom du service est db dans ce même fichier (`DB_HOST`)

## start

avant le démarrage de l'application, il est nécessaire de procéder à un build du container ; ensuite, l'ordre habituel des commandes docker-compose s'enchainent.

```
# le répertoire d'exploitation docker
cd docker

# build du container mercator en local
docker-compose build

# démarrage de l'application:
# - app : service docker lié à l'application mercator
# - db : base de données
docker-compose up -d

# pour observer les logs applicatifs
docker-compose logs -ft
```

## restart

```
# le répertoire d'exploitation docker
cd docker

# arret des 2 services db et app
docker-compose down

# redémarrage de l'application:
docker-compose up -d
```

il est nécessaire de faire un restart à chaque fois :

- que le code source est modifié
- que la configuration est modifiée
- que l'environnement d'exécution est modifié en particulier `entrypoint.sh`

## debug

il est possible d'ouvrir un shell intéractif sur l'un des deux services app ou db

```
# ouverture d'un shell ; app ou db
docker-compose exec app /bin/bash
```

# exploitation

## backup

- les seules données à sauvegarder résident dans la base Mysql. Des éléments sont fournis sur dans la procédure d'[installation](https://github.com/dbarzin/mercator/blob/master/INSTALL.md) de Mercator.
- le script `./bin/mysql/backup` permet de réaliser l'opération de backup simplement ; il est monté dans le container docker par un `bind` (primitive `volumes` dans le docker-compose.yml)
- pour l'activer il suffit d'invoquer : `docker-compose exec db /app/backup`
- la base de données  est sauvegardée dans un volume local : `./data/backup/sql/mercator.sql.gz`

## restore

fixme.

# FIXME

- donner des indications concernant la procédure de MAJ applicative.
