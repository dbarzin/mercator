# Feuille de route

Ce document reprend les changements de l'application Mercator.

Changements réalisés en 2022 :

## Evolutions majeures

- [x] Croiser les noms et versions des applications avec des flux d'informations sur les CVE
- [x] Authentifier les utilisateurs via un active directory avec [LDAPRecord](https://ldaprecord.com/)
- [x] Développer une REST API pour peupler la cartographie
- [x] Créer une vue multi-domaine avec vis.js (carte dynamique)
- [x] Ajouter des fonctions de manipulation des graphes d'objets dans l'explorateur

## Evolutions mineurs
- [x] Améliorer la page des logs (nom de l'utilisateur / action / lien vers l'objet concerné)
- [x] Revoir les pages de gestion des droits d'accès
- [x] Ajouter un lien entre application et poste de travail
- [x] Mise à jour du framework Laravel vers la version suivante

Changements prévus en 2023 :

## Evolutions majeures

- [ ] Maintenir le registre des traitements
- [ ] Générer un annuaire de crise
- [ ] Ajouter des champs personnalisés aux objets de la cartographie
- [ ] Lien avec Monarc : identifier les assets qui peuvent être sujet d'une analyse de risques 
- [ ] Améliorer la recherche des CVE en utilisant CPE (Common Plateform Enumeration)
- [ ] Généraliser la notion de cartographe à d'autres objets
- [ ] Générer les cartographes dans la gestion des utilisateurs
- [x] schémas de l'infrastructure réseau physique
- [ ] Exploiter les logs - recherche et affihcer tout les changements d'un objet
- [x] Lien entre router physique et logique ainsi que les commutateurs logiques et physiques 
- [x] Carte des actifs par rack, bâtiment/salle et site
- [ ] Utiliser des Accessor pour les Model : https://laravel.com/docs/9.x/eloquent-mutators#defining-a-mutator
- [x] Ajouter un objet "lien physique" (câble) et dessiner un plan de l'infrastructure réseau
- [ ] Ajouter une vue de l'adressage réseau [Hilbert Map of IPv4 address space](https://bl.ocks.org/vasturiano/8aceecba58f115c81853879a691fd94f), [Measuring the use of IPv4 space with Heatmaps](https://www.caida.org/archive/arin-heatmaps/) identifier le nombre de périphériques par sous-réseau.
- [ ] Intégration des données de la cartographie dans syslog
- [ ] Revoir le modèle des pages web

## Evolutions mineurs

- [ ] Packaging des librairies javascript avec npm
- [ ] Mise à jour du framework Laravel vers la version suivante
- [ ] Dessiner un nouveu jeu d'icônes en SVG
- [ ] Améliorer la documentation, notemment les niveaux de maturité pour chaque objet.
- [ ] Améliorer la documentation de l'API (https://nordicapis.com/5-examples-of-excellent-api-documentation/)
- [ ] Nature des flux - ajouter un champ permettant d'indiquer de quelle manière s'effectue l'échange: par exemple "saisie manuelle", "transfert de fichier", "partage de fichier", "api", "réplication de base de donnée"
- [ ] Ajouter un lien entre une opération et une entité afin d'identifier l'identité qui réalise cette opération
- [x] Lien entre bases de données et serveurs logiques

## Petites évolutions

- [ ] Améliorer les tests Dusk
- [ ] Publier une VM Docker sur [dockerHub](https://hub.docker.com/)
- [ ] Documenter une procédure de déploiement sous Debian
- [ ] Dark Theme

