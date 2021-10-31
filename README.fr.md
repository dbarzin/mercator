# Mercator

Mercator est une application Web permettant de gérer la cartographie d’un système d’information 
comme décrit dans le [Guide pour la cartographie du Système d’information](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/) de l’[ANSSI](https://www.ssi.gouv.fr/). 

[![Latest Release](https://img.shields.io/github/release/dbarzin/mercator.svg?style=flat-square)](https://github.com/dbarzin/mercator/releases/latest)
![License](https://img.shields.io/github/license/dbarzin/mercator.svg?style=flat-square)
![Contributors](https://img.shields.io/github/contributors/dbarzin/mercator.svg?style=flat-square)
![Stars](https://img.shields.io/github/stars/dbarzin/mercator?style=flat-square)

Read this in other languages: [English](README.md)

## Introduction

Les attaques informatiques interviennent dans un environnement en constante évolution. 
Pour répondre à ces enjeux, il est nécessaire de mettre en place une approche globale de 
gestion des risques au sein de son organisation. 

La cartographie du Système d’Information permet d’avoir une vue globale de l’ensemble 
des éléments qui constituent le système d’information pour d’obtenir une meilleure lisibilité, 
et donc un meilleur contrôle. 

L’élaboration d’une cartographie participe à la protection, à la défense et à la résilience 
du système d’information. C’est un outil indispensable à la maitrise de son système d’information 
(SI) et est une obligation pour les Opérateurs d’importance vitale (OIV) et qui s’intègre dans une 
démarche globale de gestion des risques.

## Fonctions majeures
- Gestion des vues (écosystème, système d’information, administration, logique, applications, et physique)
- Génération du rapport d'Architecture du Système d'Informtion
- Dessin des schémas de cartographie
- Calcul des niveaux de conformité
- Extraction en Excel, CSV, PDF … de toutes les listes
- Multi utilisateurs avec gestion de rôles
- Multilingue

## Ecrans

Page principale

[<img src="public/screenshots/mercator1.png" width="400" height="300">](public/screenshots/mercator1.fr.png)
[<img src="public/screenshots/mercator2.png" width="400" height="300">](public/screenshots/mercator2.fr.png)

Niveaux de conformité

[<img src="public/screenshots/mercator3.png" width="400">](public/screenshots/mercator3.fr.png)

Ecran de saisie

[<img src="public/screenshots/mercator4.png" width="400" height="200">](public/screenshots/mercator4.fr.png)
[<img src="public/screenshots/mercator5.png" width="400" height="200">](public/screenshots/mercator5.fr.png)

Dessin de la cartographie

[<img src="public/screenshots/mercator6.png" width="400" height="300">](public/screenshots/mercator6.fr.png)
[<img src="public/screenshots/mercator7.png" width="400" height="300">](public/screenshots/mercator7.fr.png)

Modèle de données

[<img src="public/screenshots/mercator8.png" width="400">](public/screenshots/mercator8.fr.png)


## Technologies
- PHP, Javascript, Laravel
- Base de données supportées : MySQL, Postgres, SQLite, SQL Server
- WebAssembly + Graphviz
- ChartJS

## TODO
- documentation utilisateur
- développer des REST API pour peupler la base de données

## Installation

- Procédure d'[installation](https://github.com/dbarzin/mercator/blob/master/INSTALL.fr.md) 
- Déploiement sous [Docker](https://github.com/dbarzin/mercator/blob/master/docker/README.fr.md)

## Changelog

Tous les changements notables apportés à ce projet sont [documentés](https://github.com/dbarzin/mercator/blob/master/CHANGELOG.md).

## License

Mercator est un logiciel open source distribué sous [GPL](https://www.gnu.org/licenses/licenses.fr.html).


