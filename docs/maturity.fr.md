# Niveaux de maturité

🇬🇧 [Read in English](/mercator/maturity)

Les niveaux de maturité représentent le pourcentage d'exhaustivité de la cartographie. C'est un indicateur de l'effort
restant à fournir pour atteindre une cartographie complète, conformément aux recommandations
du [guide de cartographie du système d'information de l'ANSSI](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/).

Cette maturité est divisée en trois niveaux :

- la **granularité minimale de niveau 1** qui contient les informations indispensables à la cartographie ;
- la **granularité intermédiaire de niveau 2** qui contient les informations importantes à la cartographie ;
- la **granularité fine de niveau 3** qui contient les informations utiles à la gestion de la sécurité du système
  d'information.

[<img src="/mercator/images/maturity.png" width="600">](images/maturity.png)

### Signification des champs marqués d'un cardinal

Certains libellés de champs sont suivis d'un symbole **#** de couleur orange. Ces marqueurs indiquent que le champ
contribue au calcul du niveau de maturité de la cartographie, selon le niveau concerné :

| Marqueur | Signification                                                         |
|----------|-----------------------------------------------------------------------|
| `#`      | Champ contribuant au niveau de maturité 1 — granularité minimale      |
| `##`     | Champ contribuant au niveau de maturité 2 — granularité intermédiaire |
| `###`    | Champ contribuant au niveau de maturité 3 — granularité fine          |

### Calcul du niveau de maturité

Un élément est considéré comme **conforme** lorsque tous les champs marqués `#` correspondant au niveau visé sont
renseignés et que les liens attendus avec d'autres éléments de la cartographie sont établis.

Un élément est considéré comme **non conforme** lorsque :

- un champ marqué `#` est vide ou non renseigné (ex. : absence de description ou de responsable),
- un lien attendu vers un autre élément est manquant (ex. : une application qui ne soutient aucun processus métier, un
  serveur non rattaché à une application).

Le niveau de maturité est calculé selon la formule suivante :

```text
Niveau de maturité (%) = (Nombre d'éléments conformes / Nombre total d'éléments) × 100
```

### Identification visuelle des éléments non conformes

Dans les listes, les entrées **non conformes** sont surlignées en **jaune**, signalant qu'au moins un champ marqué `#`
n'a pas été renseigné. Cela permet d'identifier rapidement les éléments qui nécessitent une attention particulière pour
progresser vers une cartographie complète.

### Elements de conformité

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
| VLans                            | 1      | Description                                                                                            |
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

## Besoins de sécurité

Les besoins de sécurité de l'information sont exprimés en termes de confidentialité, intégrité, disponibilité et
traçabilité avec l'échelle suivante :

| Niveau | Description  |                 Couleur                  |
|:------:|:------------:|:----------------------------------------:|
|   0    | Insignifiant |                  Blanc                   |
|   1    |    Faible    |  <span style="color:green">Vert</span>   |
|   2    |    Moyen     | <span style="color:yellow;">Jaune</span> |
|   3    |     Fort     | <span style="color:orange">Orange</span> |
|   4    |  Très fort   |   <span style="color:red">Rouge</span>   |
    



