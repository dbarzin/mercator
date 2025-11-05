## Rapports

### Rapport de cartographie

Le rapport de cartographie contient l’ensemble des objets composant la cartographie et les liens entre eux.

[<img src="/mercator/images/report.png" width="600">](/mercator/images/report.png)

C'est un document au format Word qui contient les informations de tous les objets des vues sélectionnées au niveau de
granularité souhaité.

### Listes

Mercator permet d'extraire un ensemble d’informations sous forme de listes :

#### Entités et applications supportées

Génère la liste des entités du système d'information et de leurs applications supportées.

Cette liste permet de dresser l'inventaire des entités responsables des différentes applications du système
d’information avec les responsables et leurs points de contact.

#### Applications par groupe applicatif

Liste des applications par groupe applicatif

Cette liste permet d’avoir une vue de toutes les applications du système d’information classées par groupe applicatif.
Cette liste peut être utilisée pour mener une veille technologique sur les vulnérabilités des applications du système
d’information.

#### Serveurs logiques

Liste des serveurs logiques par application et leurs responsables.

Cette liste permet d'identifier les responsables des serveurs logiques et les applications qu’ils servent.

#### Analyse des besoins de sécurité

Liste des besoins de sécurité entre macro-processus, processus, applications, bases de données et informations.

Cette liste permet d’analyser la cohérence du plan de classification de l’information en termes de confidentialité,
intégrité, disponibilité et traçabilité entre les processus, les applications, les bases de données et les informations
qu’elles contiennent.

#### Configuration des serveurs logiques

Liste de la configuration des serveurs logiques.

Cette liste permet d’analyser la configuration des serveurs logiques.

Cette liste peut être utilisée pour effectuer l’analyse de la capacité nécessaire pour faire fonctionner le système
d’information et permet d’effectuer des projections d’une année sur l’autre.

#### Inventaire de l'infrastructure physique

Liste des équipements par site/local

Cette liste permet de faire une revue de l’inventaire physique des équipements du système d’information.

Annuellement, il est recommandé d’imprimer cette liste et de vérifier si le matériel présent dans l’inventaire
correspond à ce qui est réellement présent dans les locaux, sites et baies correspondants.

### Audit

#### Niveaux de maturité

Cette liste contient le détail des niveaux de maturité de chaque type d'objet de la cartographie du système d'
information

#### Mises à jour

Cette liste permet d'auditer les changements réalisés sur la cartographie.

Une cartographie qui ne change jamais n’est pas à jour. Ce rapport permet d’identifier les changements (créations,
suppressions et modifications) par types d'objets réalisés sur la cartographie sur une année.

### Conformité

Le calcul des niveaux de conformité pour chaque objet de la cartographie est basé sur la présence des éléments
suivants :

| Objet                            | Niveau | Elements requis                                                                                        |
|----------------------------------|--------|--------------------------------------------------------------------------------------------------------|
| **Ecosystème**                   |        |                                                                                                        |
| Entités                          | 1      | Description, niveau de sécurité, point de contact, au moins un processus                               |
| Relations                        | 1      | Description, type                                                                                      |
| Relations                        | 2      | Importance                                                                                             |
| **Métier**                       |        |                                                                                                        |
| Macro-processus                  | 2      | Description, niveaux de sécurité                                                                       |
| Macro-processus                  | 3      | Responsable                                                                                            |
| Processus                        | 1      | Description, entrée-sorties, responsable                                                               |  
| Processus                        | 2      | Macro-processus, besoins de sécurité                                                                   |
| Activités                        | 2      | Description                                                                                            |
| Opérations                       | 1      | Description                                                                                            |
| Opérations                       | 2      | Acteurs                                                                                                |
| Opérations                       | 3      | Tâches                                                                                                 |
| Tâches                           | 3      | Description                                                                                            |
| Acteur                           | 2      | Contact, nature, type                                                                                  |
| Informations                     | 1      | Description, propriétaire, administrateur, stockage                                                    |
| Informations                     | 2      | Besoins de sécurité, sensibilité                                                                       |
| **Système d'information**        |        |                                                                                                        |
| Bloc applicatif                  | 2      | Description, responsable, applications                                                                 |
| Applications                     | 1      | Description, technologie, type, utilisateurs, processus                                                |
| Applications                     | 2      | Responsable, niveaux de sécurité                                                                       |
| Application Services             | 2      | Description, applications                                                                              |
| Application Modules              | 2      | Description                                                                                            |
| Base de données                  | 1      | Description, type, entité responsable, responsable                                                     |
| Base de données                  | 2      | Besoins de sécurité                                                                                    |
| Flux                             | 1      | Description, source, destination                                                                       |
| **Administration**               |        |                                                                                                        |
| Zones                            | 1      | Description                                                                                            |
| Annuaires                        | 1      | Description, solution, zone d'administration                                                           |
| Forêt                            | 1      | Description, zone d'administration                                                                     |
| Domaines                         | 1      | Description, Contrôleur de domaine, nombre d'utilisateurs, nombre de machines, relation inter domaine  |
| **Logique**                      |        |                                                                                                        |
| Réseaux                          | 1      | Description, responsable, responsable sécurité, besoins de sécurité                                    |
| Sous-réseaux                     | 1      | Description, adresse, passerelle par défaut, type d'allocation IP, DMZ, WiFi, VLAN                     |
| Passerelles                      | 1      | Description, authentification, range IP                                                                |
| Entités externes connectées      | 2      | Type, contacts                                                                                         |
| Commutateurs                     | 1      | Description                                                                                            |
| Routeurs                         | 1      | Description                                                                                            |
| Dispositifs de sécurité          | 1      | Description                                                                                            |
| Clusters                         | 1      | Description, type                                                                                      |  
| Serveurs logiques                | 1      | Description, OS, environnement, adresse IP, applications, serveurs physique ou clusters                |
| Certificats                      | 2      | Description, Type, date de début de validité, date de fin de validité, applications ou serveur logique |
| **Infrastructure physique**      |        |                                                                                                        |
| Sites                            | 1      | Description                                                                                            |
| Buildings                        | 1      | Description                                                                                            |
| Baies                            | 1      | Description                                                                                            |
| Serveurs physique                | 1      | Description, configuration, site, building, responsable                                                |
| Poste de travail                 | 1      | Description, site, building                                                                            |
| Téléphones                       | 1      | Description, site, building                                                                            |
| Stockage                         | 1      | Description, site, building                                                                            |
| Périphériques                    | 1      | Description, site, building, responsable                                                               |
| Commutateurs physique            | 1      | Description, type, site, building                                                                      |
| Routeurs physique                | 1      | Description, type, site, building                                                                      |
| Terminaux WiFi                   | 1      | Description, type, site, building                                                                      |
| Dispositifs de sécurité physique | 1      | Description, type, site, building                                                                      |
| LANs                             | 1      | Description                                                                                            |
| VLans                            | 1      | Description                                                                                            |
