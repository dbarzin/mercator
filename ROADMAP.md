# Feuille de route

Ce document reprend les changements de l'application Mercator.

Changements prévus en 2024 :

## Evolutions majeures

- [ ] Lien avec Monarc : générer un modèle d'analyse de risques pour Monarc
- [ ] Ajouter une vue de l'adressage réseau [Hilbert Map of IPv4 address space](https://bl.ocks.org/vasturiano/8aceecba58f115c81853879a691fd94f), [Measuring the use of IPv4 space with Heatmaps](https://www.caida.org/archive/arin-heatmaps/) identifier le nombre de périphériques par sous-réseau.
- [ ] Ajouter des champs personnalisés aux objets de la cartographie
- [ ] Générer un annuaire de crise
- [x] Améliorer la recherche des CVE en assignat un CPE [Common Plateform Enumeration](https://nvd.nist.gov/products/cpe) aux objets de la cartographie.
- [ ] Identifier les chemins critiques
- [ ] Exploiter les logs - recherche et afficher tout les changements d'un objet
- [ ] Utiliser des [Accessor pour les Model](https://laravel.com/docs/9.x/eloquent-mutators#defining-a-mutator)
- [ ] Généraliser la notion de cartographe à d'autres objets (cf.: https://laravel.com/docs/10.x/authorization)
- [ ] Générer les cartographes dans la gestion des utilisateurs
- [ ] Intégration des données de la cartographie dans syslog
- [ ] Revoir le modèle des pages web avec Intertia.js (https://laracasts.com/series/build-modern-laravel-apps-using-inertia-js)
- [ ] Utiliser un modèle de document pour les rapports

## Evolutions mineurs

- [ ] Remplacer le champ libre éditeur par un lien vers la table entités et migrer la base de données
- [ ] Packaging des librairies JavaScript avec [Laravel Mix](https://laravel-mix.com/).
- [ ] Dessiner un nouveau jeu d'icônes en SVG
- [ ] Améliorer la documentation, notamment les niveaux de maturité pour chaque objet.
- [ ] Améliorer la documentation de l'API (https://nordicapis.com/5-examples-of-excellent-api-documentation/)
- [ ] Nature des flux - ajouter un champ permettant d'indiquer de quelle manière s'effectue l'échange: par exemple "saisie manuelle", "transfert de fichier", "partage de fichier", "api", "réplication de base de donnée"
- [ ] Ajouter un lien entre une opération et une entité afin d'identifier l'identité qui réalise cette opération
- [ ] Restaurer les objets supprimés sur base des logs (ex: Flight::withTrashed()->where('id', 777)->restore(); )
- [x] Renseigner les ports utilisables lors de la définition d'un équipement (https://github.com/dbarzin/mercator/issues/410)
- [x] Ajout des clusters
- [ ] Afficher l'historique des changements d'un objet

## Petites évolutions

- [ ] Améliorer les tests Dusk
- [ ] Documenter une procédure de déploiement sous Debian
- [ ] Dark Theme

Changements réalisés en 2023 :

## Evolutions majeures

- [x] Maintenir le registre des traitements
- [x] Intégrer [CPE Guesser](https://github.com/cve-search/cpe-guesser)
- [x] schémas de l'infrastructure réseau physique
- [x] Lien entre router physique et logique ainsi que les commutateurs logiques et physiques
- [x] Carte des actifs par rack, bâtiment/salle et site
- [x] Ajouter un objet "lien physique" (câble) et dessiner un plan de l'infrastructure réseau

## Evolutions mineurs

- [x] Support des adresses IPv6
- [x] Mise à jour du framework vers [Laravel 10.x](https://laravel.com/docs/10.x)
- [x] Lien entre bases de données et serveurs logiques
- [x] Publier une VM Docker sur [GitHub](https://ghcr.io)
- [x] Ajouter l'objet cluster de serveurs logiques
- [x] Dans l'explorer, afficher les objets du menu déroulant en se basant sur le filtre de la vue  

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
