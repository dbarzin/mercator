# Feuille de route

Ce document reprend les évolutions prévues sur Mercator sur l'année 2022.

## Evolutions majeures

- [x] Croiser les noms et versions des applications avec des flux d'informations sur les CVE
- [x] Authentifier les utilisateurs via un active directory avec [LDAPRecord](https://ldaprecord.com/)
- [ ] Revoir le modèle des pages web
- [ ] Ajouter une vue de l'adressage réseau [Hilbert Map of IPv4 address space](https://bl.ocks.org/vasturiano/8aceecba58f115c81853879a691fd94f), [Measuring the use of IPv4 space with Heatmaps](https://www.caida.org/archive/arin-heatmaps/) identifier le nombre de périphériques par sous-réseau.
- [ ] Ajouter un objet "lien physique" (câble) et dessiner un plan de l'infrastructure réseau
- [x] Développer une REST API pour peupler la cartographie
- [x] Créer une vue multi-domaine avec vis.js (carte dynamique)
- [ ] Intégration des données de la cartographie dans syslog

## Evolutions mineurs

- [x] Améliorer la page des logs (nom de l'utilisateur / action / lien vers l'objet concerné)
- [ ] Packaging des librairies javascript avec npm
- [ ] Dessiner un nouveu jeu d'icônes en SVG
- [x] Revoir les pages de gestion des droits d'accès
- [ ] Améliorer la documentation, notemment les niveaux de maturité pour chaque objet.
- [x] Mise à jour du framework Laravel vers la version suivante
- [ ] Améliorer la documentation de l'API (https://nordicapis.com/5-examples-of-excellent-api-documentation/)
- [ ] Nature des flux - ajouter un champ permettant d'indiquer de quelle manière s'effectue l'échange: par exemple "saisie manuelle", "transfert de fichier", "partage de fichier", "api", "réplication de base de donnée"
- [ ] Ajouter un lien entre une opération et une entité afin d'identifier l'identité qui réalise cette opération
- [ ] Ajouter un lien entre application et poste de travail

## Petites évolutions

- [ ] Améliorer les tests Dusk
- [ ] Publier une VM Docker sur [dockerHub](https://hub.docker.com/)
- [ ] Documenter une procédure de déploiement sous Debian
- [ ] Dark Theme

## Evolution 2023

- [ ] Maintenir le registre des traitements
- [ ] Généraliser la notion de cartographe à d'autres objets
- [ ] Générer les cartographes dans la gestion des utilisateurs
- [ ] Ajouter des fonctions de manipulation des graphes d'objets
- [ ] schémas de l'infrastructure réseau physiqiue
- [ ] Exploiter les logs - recherche et affihcer tout les changements d'un objet
- [ ] Carte des actifs par rack, bâtiment/salle et site

