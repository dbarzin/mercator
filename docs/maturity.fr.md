## Niveaux de maturité

### Niveaux

Les niveaux de maturité représentent le pourcentage d'exhaustivité de la cartographie.
C’est un indicateur de l’effort restant à fournir pour atteindre une cartographie complète.
Ce pourcentage est calculé en divisant le nombre d'éléments conformes par le nombre total d'élements.

[<img src="images/maturity.png" width="600">](images/maturity.png)

Cette maturité est divisée en trois niveaux correspondant aux trois niveaux de granularité :

* La granularité minimale de niveau 1 qui contient les informations indispensables à la cartographie ;
* La granularité intermédiaire de niveau 2 qui contient les informations importantes à la cartographie ;
* La granularité fine de niveau 3 qui contient informations utiles à la gestion de la sécurité du système d'information.

### Règles de calcul

Un objet est conforme si toutes les informations sur l'objet ont été fournies.
S'il manque des informations, l'objet est non-conforme.

Cela peut être l'absence d'un attribut : une application sans description, l'absence d'un point de contact pour une entité, 
la criticité qui n'est pas spécifiés... ou l'absence d'une relation avec d'autres objets : un serveur sans applications, un processus sans acteurs, 
une application qui ne sert aucun processus...

Les règles appliquées à ce calcul sont les suivantes :

* Un élément peut être conforme au niveau n, et ne pas être conforme au niveau n+1. L'inverse n'est pas vrais.

* S'il 'y a pas de nouveaux attributs qui entre en compte pour passer du niveau n au niveau n+1, l'objet conforme au niveau n reste conforme au niveau n+1.

En fonction du nombre d'objets pris en compte dans le calcul de conformité, à chaque niveau,
le pourcentage de conformité du niveau n peut être inférieur, égal ou suppérieur au pourcentage du niveau n+1

### Exigences de conformité


| Objet | Niveau | Exigences |
|---    |:-:     |---              |
| **Écosystème** | | |
| Entité | 1 | doit avoir une description, un niveau de sécurité, un point de contact et supporter au moins un processus |
| Relation | 1 | doit avoir un type et une description |
| Relation | 2 | doit avoir une importance |
| **Système d'Information** | | |
| Macro-Processus | 2 | doit avoir une description, des éléments d'entrée/sortie et un besoins de sécurité (CIDT) |
| Macro-Processus | 3 | doit avoir un propriétaire |
| Processus | 1 | doit avoir une description, des éléments d'entrée/sortie, un propriétaire  |
| Processus | 2 | doit avoir un niveau de sécurité et faire partie d'un macro-processus |
| Activité | 2 | doit avoir une desription |
| Opération | 1 | doit avoir une description |
| Opération | 2 | doit avoir un acteur |
| Opération | 3 | doit avoir une tâche |
| Tâche | 3 | doit avoir une description et faire partie d'une opération |
| Acteur | 2 | doit avoir un contact, une nature et un type |
| Information | 1 | doit avoir une description, un propriétaire et un mode de stockage |
| Information | 2 | doit avoir un niveau de sécurité et de sensibilité |
| **Vue applicative** | | |
| Block applicatif | 2 | doit avoir une description |
| Application | 1 | doit avoir une description, un responsable, une technologie, un type des utilisateurs et servir un processus |
| Application | 2 | doit avoir une entité responsable et un besoin de sécurité |
| Application | 3 | doit être identifiées par une CPE |
| Service applicatif | 2 | doit avoir une description et une application |
| Module applicatif | 2 | doit avoir une description |
| Base de données | 1 | doit avoir une description, une entité responsable, un responsable et un type |
| Base de données | 2 | doit avoir un niveau de sécurité |
| Flux applicatif | 1 | doit avoir une description, une source et une destination |
| **Vue de l'administration** | | |
| Zone d'administration | 1 | doit avoir une description |
| Service d'annuaire | 1 | doit avoir une description, une solution et faire partie d'une zone |
| Forêt active directory / LDAP | 1 | doit avoir une description et faire partie d'une zone |
| Domains active directory / LDAP | 1 | doit avoir une description, une nombre de domain contrôleur, un nombre d'utilisateurn un nombre de machines et des relations inter-domaine |
| **Vue logique** | | |
| Réseaux | 1 | doit avoir un type de protocol, un responsable, un responsable sécurité et des besoins de sécurité |
| Sous-réseau | 1 | doit avoir une description, une adresse IP, une passerelle par défaut, un type d'allocation d'adresse IP, un responsable d'exploitation et un VLAN |
| Passerelle | 1 | doit avoir une description, un tupe d'authentification et une adresse IP |
| Entité externe connectée | 1 | doit avoir un type et un contact |

