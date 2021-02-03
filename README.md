# Mercator

Mercator est une application Web permettant de gérer la cartographie d’un système d’information 
comme décrit dans le [Guide pour la cartographie du Système d’information](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/) de l’[ANSSI](https://www.ssi.gouv.fr/). 

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
- Dessin du schéma de la cartographie avec GraphViz
- Calcul du niveau de conformité
- Extraction en Excel, CSV,… de toutes les listes
- Multi utilisateurs avec gestion de rôles
- Multilingue

## Ecrans
- [Page principale](http://www.barzin.be/mercator/images/mercator1.png)
- [Vue de l’architecture physique](http://www.barzin.be/mercator/images/mercator2.png)
- [Niveau de conformité](http://www.barzin.be/mercator/images/mercator3.png)
- [Liste des processus](http://www.barzin.be/mercator/images/mercator4.png)
- [Ajouter une application](http://www.barzin.be/mercator/images/mercator5.png)

## Technologies
- PHP, Javascript, Laravel
- Base de données supportées : MySQL, Postgres, SQLite, SQL Server
- WebAssembly + Graphviz
- ChartJS

## TODO
- générer un rapport d’architecture
- Export Excel de toutes des vues
- vérifier la cohérence des informations utilisées entre les bases de données et les applications
- calcul des chemins critiques
- développer de REST API pour peupler la base de données

## Installation

- Procédure d'[installation](https://github.com/dbarzin/mercator/blob/master/INSTALL.md)

## License

Mercator est un logiciel open source distribué sous [GPL](https://www.gnu.org/licenses/licenses.fr.html).







