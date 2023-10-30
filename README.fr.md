# Mercator

Mercator est une application Web permettant de gérer la cartographie d’un système d’information comme décrit dans le [Guide pour la cartographie du Système d’information](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/) de l’[ANSSI](https://www.ssi.gouv.fr/). 
La [documentation](https://dbarzin.github.io/mercator/index.fr/) et les [sources de l'application](https://dbarzin.github.io/mercator/) sont publiées sur GitHub.

Mercator a été présenté lors du [SSTIC 2023](https://www.sstic.org/2023/news/), une conférence francophone sur le thème de la sécurité de l'information qui s'est déroulerée à Rennes du 7 au 9 juin 2023. La présentation a été [enregistrée et est disponible en ligne](https://www.sstic.org/2023/presentation/mercator_-_la_cartographie_des_systmes_dinformation/).

[![Latest Release](https://img.shields.io/github/release/dbarzin/mercator.svg?style=flat-square)](https://github.com/dbarzin/mercator/releases/latest) ![License](https://img.shields.io/github/license/dbarzin/mercator.svg?style=flat-square) ![Contributors](https://img.shields.io/github/contributors/dbarzin/mercator.svg?style=flat-square) ![Stars](https://img.shields.io/github/stars/dbarzin/mercator?style=flat-square)

Read this in other languages: [English](README.md)

## Introduction

Les attaques informatiques interviennent dans un environnement en constante évolution. Pour répondre à ces enjeux, il est nécessaire de mettre en place une approche globale de gestion des risques au sein de son organisation. 

La cartographie du Système d’Information permet d’avoir une vue globale de l’ensemble des éléments qui constituent le système d’information pour d’obtenir une meilleure lisibilité, et donc un meilleur contrôle. 

L’élaboration d’une cartographie participe à la protection, à la défense et à la résilience du système d’information. C’est un outil indispensable à la maitrise de son système d’information (SI) et est une obligation pour les Opérateurs d’importance vitale (OIV) et qui s’intègre dans une démarche globale de gestion des risques.

## Fonctions majeures
- Gestion des vues (écosystème, système d’information, administration, logique, applications, et physique)
- Génération du rapport d'Architecture du Système d'Information
- Dessin des schémas de cartographie
- Calcul des niveaux de conformité
- Recherche de CVE avec [CVE-Search](https://github.com/cve-search/cve-search)
- Extraction en Excel, CSV, PDF … de toutes les listes
- REST API avec JSON
- Multi utilisateurs avec gestion de rôles
- Multilingue
- Connexion LDAP / Active Directory
- [CPE](https://nvd.nist.gov/products/cpe) - Common Platform Enumeration

## Ecrans

Page principale

[<img src="public/screenshots/mercator1.png" width="400" height="300">](public/screenshots/mercator1.fr.png) [<img src="public/screenshots/mercator2.png" width="400" height="300">](public/screenshots/mercator2.fr.png)

Niveaux de conformité

[<img src="public/screenshots/mercator3.png" width="400">](public/screenshots/mercator3.fr.png)

Ecran de saisie

[<img src="public/screenshots/mercator4.png" width="400" height="200">](public/screenshots/mercator4.fr.png) [<img src="public/screenshots/mercator5.png" width="400" height="200">](public/screenshots/mercator5.fr.png)

Dessin de la cartographie

[<img src="public/screenshots/mercator6.png" width="400" height="300">](public/screenshots/mercator6.fr.png) [<img src="public/screenshots/mercator7.png" width="400" height="300">](public/screenshots/mercator7.fr.png)

Explorer

[<img src="public/screenshots/mercator9.png" width="400">](public/screenshots/mercator9.fr.png)

Modèle de données

[<img src="public/screenshots/mercator8.png" width="400">](public/screenshots/mercator8.fr.png)


## Technologies
- PHP, Javascript, Laravel
- Base de données supportées : MySQL, Postgres, SQLite, SQL Server (cf.: [Laravel/Databases/introduction](https://laravel.com/docs/master/database#introduction) )
- WebAssembly + Graphviz
- ChartJS

## Installation

- Procédure d'[installation](https://github.com/dbarzin/mercator/blob/master/INSTALL.fr.md) 

### Docker

Téléchargez d'abord l'image Docker.

```Shell
docker pull ghcr.io/dbarzin/mercator:latest
```

Ensuite, vous pouvez lancer une instance locale éphémère en mode développement (i.e. http) :

```shell
docker run -it --rm --name mercator -e APP_ENV=development -p "127.0.0.1:8000":80 ghcr.io/dbarzin/mercator:latest
```

Par défaut, il utilise un backend SQLite. Si vous voulez rendre les données persistantes :

```Shell
touch ./db.sqlite && chmod a+w ./db.sqlite
docker run -it --rm --name mercator -e APP_ENV=development -p "127.0.0.1:8000":80 -v $PWD/db.sqlite:/var/www/mercator/db.sqlite ghcr.io/dbarzin/mercator:latest
```

Enfin, vous pouvez remplir la base de données avec des données de démonstration grâce à la variable d'environnement `USE_DEMO_DATA` :

```shell
touch ./db.sqlite && chmod a+w ./db.sqlite
docker run -it --rm \N --name mercator \N
           --name mercator \N- -e APP_ENV=de_sur_le_milieu \N
           -e APP_ENV=développement \N- -p "127.0.0.0.1" \N
           -p "127.0.0.1:8000":80 \
           -v $PWD/db.sqlite:/var/www/mercator/db.sqlite \N- -e USE_DEMO_DDISQUE
           -e USE_DEMO_DATA=1 \N- -e USE_DEMO_DATA=1 \N- -e USE_DEMO_DATA=1
           ghcr.io/dbarzin/mercator:latest
```

Visitez http://127.0.0.1:8000 !

Si vous recherchez un environnement plus robuste (https) et automatisé, jetez un oeil au dossier [docker-compose](docker-compose/).

## Changelog

Tous les changements notables apportés à ce projet sont [documentés](https://github.com/dbarzin/mercator/blob/master/CHANGELOG.md).

## Feuille de route

Une [feuille de route](https://github.com/dbarzin/mercator/blob/master/ROADMAP.md) reprend les évolutions prévues de l'application.

## License

Mercator est un logiciel open source distribué sous [GPL](https://www.gnu.org/licenses/licenses.fr.html).

