# Module BPMN 2.0

## Introduction

Le module BPMN (Business Process Model and Notation) disponible dans la version Enterprise de Mercator
permet de créer, éditer et visualiser des diagrammes de processus métier selon la norme BPMN 2.0.

Ce module offre une intégration complète avec la cartographie du système d'information, permettant de relier
les éléments graphiques BPMN aux entités de Mercator.

## Association avec la cartographie Mercator

Les diagrammes BPMN permettent de modéliser des processus métier en les reliant aux éléments de la cartographie
Mercator : processus, macro-processus, activités et acteurs.

Ces liens bidirectionnels entre les diagrammes BPMN et les objets de cartographie offrent une traçabilité complète. Un
processus Mercator peut être associé à un ou plusieurs diagrammes BPMN qui en détaillent l'exécution, tandis qu'un
diagramme BPMN peut référencer plusieurs processus, activités ou acteurs de la cartographie.

Cette intégration facilite la compréhension des flux métier en permettant de naviguer entre la vue cartographique (qui
fait quoi) et la vue processus (comment c'est fait), tout en maintenant la cohérence des informations au sein de
l'organisation.

### Vue du système d'information

Les éléments BPMN s'intègrent aux objets de la vue du système d'information de Mercator selon les correspondances
suivantes :

- **Activités** : Les activités métier de Mercator correspondent aux sous-processus BPMN de haut niveau
- **Opérations** : Les opérations détaillées peuvent être modélisées comme des sous-processus BPMN spécifiques
- **Tâches** : Les tâches BPMN sont directement liées aux tâches opérationnelles de la cartographie
- **Acteurs** : Les rôles et acteurs sont représentés dans les couloirs (lanes/pools), permettant d'identifier
  clairement les responsabilités
- **Informations** : Les objets de données BPMN peuvent être associés aux informations de la vue écosystème

[<img src="/mercator/images/bpmn-1.png" width="500">](/mercator/images/bpmn-1.png)

Cette intégration maintient une cohérence entre la modélisation des processus métier (BPMN) et
la cartographie technique du SI. Elle permet de :

- Tracer les flux de données à travers l'organisation
- Identifier les dépendances entre la vue métier et l'infrastructure technique
- Aligner les processus métier avec les composants techniques qui les supportent

### Conversations BPMN

Les diagrammes BPMN dans Mercator supportent les éléments de conversation définis dans la spécification BPMN 2.0. Les
conversation links permettent de représenter les échanges de messages entre participants (pools), matérialisés
visuellement par des lignes doubles parallèles.

Les conversations regroupent logiquement plusieurs conversation links pour modéliser un ensemble cohérent d'échanges
entre participants. Elles peuvent être représentées soit comme des conversations standards (hexagone simple) pour les
interactions basiques, soit comme des sub-conversations (hexagone double bordure) lorsqu'elles encapsulent des échanges
plus complexes nécessitant une décomposition.

[<img src="/mercator/images/bpmn-2.png" width="400">](/mercator/images/bpmn-2.png)

Cette notation permet de documenter les protocoles de communication entre différents acteurs métier ou systèmes, en
complément de la modélisation des processus internes à chaque participant. Les conversations BPMN offrent ainsi une vue
architecturale des interactions inter-organisationnelles ou inter-départementales.

## Éléments du diagramme BPMN

Le module BPMN propose une palette complète d'éléments conformes à la norme BPMN 2.0 :

### Événements (Events)

- **Événement de début** : Point de départ d'un processus
- **Événement intermédiaire** : Événement survenant durant le processus
- **Événement de fin** : Point de terminaison d'un processus

  [<img src="/mercator/images/bpmn-events.png" width="300">](/mercator/images/bpmn-events.png)

### Activités (Activities)

- **Tâche (Task)** : Unité de travail atomique
- **Sous-processus (Sub-Process)** : Processus décomposable en tâches
- **Tâche utilisateur** : Tâche nécessitant une interaction humaine
- **Tâche de service** : Tâche automatisée
- **Tâche de script** : Exécution de code

  [<img src="/mercator/images/bpmn-tasks.png" width="500">](/mercator/images/bpmn-tasks.png)

### Passerelles (Gateways)

- **Passerelle exclusive** : Choix d'un seul chemin parmi plusieurs
- **Passerelle parallèle** : Exécution simultanée de plusieurs chemins
- **Passerelle inclusive** : Activation d'un ou plusieurs chemins
- **Passerelle événementielle** : Attente d'événements multiples

  [<img src="/mercator/images/bpmn-gateways.png" width="400">](/mercator/images/bpmn-gateways.png)

### Flux et connexions

- **Flux de séquence** : Définit l'ordre d'exécution des activités dans un processus.
- **Flux de message** : Représente la communication entre différents participants (pools) d'un processus.
- **Flux conditionnel** : Flux de séquence accompagné d'une condition d'activation.
- **Flux par défaut** : Flux de séquence utilisé lorsqu'aucune condition des autres flux sortants n'est satisfaite.
- **Association** : Lien non directionnel reliant des artefacts (annotations, objets de données) aux éléments du
  processus.
-

[<img src="/mercator/images/bpmn-flows.png" width="400">](/mercator/images/bpmn-flows.png)

### Artefacts

- **Objet de données (Data Object)** : Représentation d'informations
- **Stockage de données (Data Store)** : Lieu de persistance des données

  [<img src="/mercator/images/bpmn-data.png" width="300">](/mercator/images/bpmn-data.png)

- **Annotation** : Commentaires et notes explicatives

  [<img src="/mercator/images/bpmn-annotation.png" width="150">](/mercator/images/bpmn-annotation.png)

## Lanes (couloirs)

Les lanes permettent d'organiser les responsabilités dans un processus :

### Orientation

- **Lanes horizontales** : Organisation traditionnelle, les acteurs sont listés verticalement
- **Lanes verticales** : Organisation alternative, les acteurs sont listés horizontalement

### Association aux acteurs

Chaque lane peut être associée à un acteur de la cartographie Mercator :

- **Entités organisationnelles** : Départements, services, équipes
- **Rôles** : Fonctions et responsabilités
- **Acteurs externes** : Partenaires, fournisseurs, clients

L'association des lanes aux acteurs permet d'identifier clairement les responsabilités et
de générer des matrices RACI automatiquement.

## Annotations BPMN

Les annotations permettent d'enrichir la documentation du processus :

- **Notes explicatives** : Clarifications sur des étapes complexes
- **Règles métier** : Conditions et contraintes applicables
- **Références** : Liens vers des documents externes ou normes
- **Commentaires** : Remarques pour la maintenance et l'amélioration

Les annotations sont positionnées librement sur le diagramme et reliées aux éléments concernés par des associations.

## Vue et navigation

### Mode visualisation

Le mode visualisation offre une expérience interactive :

- **Navigation par clic** : Cliquer sur un élément BPMN associé redirige vers la fiche correspondante dans la
  cartographie
- **Informations contextuelles** : Survol des éléments pour afficher les détails
- **Zoom et déplacement** : Navigation fluide dans les diagrammes complexes
- **Vue d'ensemble** : Minimap pour se repérer dans les grands processus

Cette intégration bidirectionnelle entre BPMN et cartographie facilite l'analyse d'impact et la traçabilité.

### Accès depuis la cartographie

Depuis les fiches processus, activités et tâches de Mercator, un bouton permet d'accéder directement au
diagramme BPMN associé.

## Conversations et sous-processus

### Association de conversations

Les flux de message BPMN représentent des conversations pouvant pointer vers :

- **Autres diagrammes BPMN** : Processus collaboratifs et échanges inter-processus
- **Processus externes** : Interactions avec des systèmes tiers
- **Sous-processus détaillés** : Navigation hiérarchique dans les processus complexes

Cette fonctionnalité permet de modéliser des architectures de processus distribuées tout en maintenant
la lisibilité de chaque diagramme.

### Navigation entre diagrammes

Un clic sur un flux de message associé à un autre diagramme BPMN ouvre ce dernier,
permettant une exploration intuitive des processus interconnectés.

## Import et Export

### Format BPMN 2.0 XML

**Import** :

- Support complet de la norme BPMN 2.0
- Préservation des waypoints et du positionnement
- Récupération des associations existantes si les identifiants correspondent

**Export** :

- Génération de fichiers BPMN 2.0 conformes
- Conservation des métadonnées et associations Mercator
- Compatible avec les outils BPMN standard (Camunda, Bonita, etc.)

### Export SVG

L'export en SVG permet :

- **Documentation** : Insertion dans des documents Word, PDF
- **Présentation** : Qualité vectorielle pour les slides
- **Publication** : Intégration dans des intranets et wikis
- **Archivage** : Format ouvert et pérenne

Le SVG généré préserve la mise en forme, les couleurs et la lisibilité du diagramme original.

## Bonnes pratiques

### Modélisation

1. **Simplicité** : Privilégier la clarté à l'exhaustivité
2. **Cohérence** : Utiliser les mêmes conventions de nommage que la cartographie
3. **Association systématique** : Lier tous les éléments BPMN aux entités Mercator
4. **Documentation** : Utiliser les annotations pour les règles métier complexes

### Organisation

1. **Découpage** : Créer des sous-processus pour les workflows >20 éléments
2. **Lanes** : Une lane par acteur principal, éviter la multiplication excessive
3. **Données** : Positionner les data objects près des activités qui les manipulent
4. **Navigation** : Utiliser les conversations pour lier les processus interconnectés

### Maintenance

1. **Versioning** : Exporter régulièrement en BPMN 2.0 pour historisation
2. **Révision** : Synchroniser les modifications avec la cartographie
3. **Validation** : Vérifier que les associations restent valides après réorganisation
4. **Archivage** : Conserver les exports SVG des versions majeures

## Cas d'usage

### Analyse d'impact

Identifier rapidement les systèmes et acteurs impactés par un changement de processus grâce aux associations.

### Conformité

Démontrer la traçabilité entre processus métier et contrôles techniques pour les audits RGPD, ISO 27001, etc.

### Onboarding

Faciliter la compréhension du SI par les nouveaux collaborateurs via des diagrammes visuels reliés à la cartographie
détaillée.

### Transformation digitale

Cartographier l'état actuel (AS-IS) et modéliser l'état cible (TO-BE) avec comparaison visuelle.

