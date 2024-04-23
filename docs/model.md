## Data model

[<img src="/mercator/images/model.png" width="700">](/mercator/images/model.png)

### RGPD view

The RGPD view contains all the data required to maintain the data processing register, and provides a link with the processes, applications and information used by the information system.

This view is used to fulfill the obligations set out in article 30 of the RGPD.

#### Register

The register of processing activities contains the information required by article 30.1 of the RGPD.

Table *data_processing* :

| Field | Type | Description |
|:------------|:-------------|:---------------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Treatment name |
| description | longtext | Description of treatment |
| responsible | longtext | Responsable du traitement |
| purpose | longtext | Purposes of processing |
| categories | longtext | Categories of recipients |
| recipients | longtext | Data recipients |
| transfer | longtext | Data transfers |
| retention | longtext | Retention periods |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |


#### Security measures

This table identifies the security measures applied to processes and applications.

By default, this table is populated with the security measures of ISO 27001:2022.

Table *security_controls* :

| Field | Type | Description |
|:------------|:-------------|:---------------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | measure name |
| description | longtext | measure description |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |


### Ecosystem view

The ecosystem view describes all the entities or systems that revolve around the information system considered in the mapping.

[<img src="/mercator/images/ecosystem.png" width="600">](/mercator/images/ecosystem.png)

This view not only delimits the scope of the mapping, but also provides an overall view of the ecosystem without being limited to the individual study of each entity.


#### Entities

Entities are a part of the organization (e.g.: subsidiary, department, etc.) or related to the information system to be mapped.

Entities are departments, suppliers, partners with whom information is exchanged through relationships.

Table *entities* :

| Field             | Type         | Description |
|:------------------|:-------------|:-----------------|
| id                | int unsigned | Unique identifier of the entity |
| name              | varchar(255) | Name of entity |
| entity_type       | varchar(255) | Type of entity |
| attributes        | varchar(255) | Attributes (#tag...) |
| reference         | varchar(255) | Reference number of the entity (billing) |
| parent_entity_id  | int unsigned | Pointer to the parent entity |
| is_external       | boolean       | External entity |
| security_level    | longtext      | Security level |
| contact_point     | longtext      | Contact point |
| description       | longtext      | Entity description |
| created_at        | timestamp     | Date of creation |
| updated_at        | timestamp     | Date of update |
| deleted_at        | timestamp     | Date of deletion |

#### Relationships

Relationships represent a link between two entities or systems.

Relationships are contracts, service agreements, legal obligations... that have an influence on the information system.

Table *relations* :

| Field         | Type | Description |
|:--------------|:-------------|:-----------------|
| id            | int unsigned | auto_increment |
| name          | varchar(255) | Relationship name |
| type          | varchar(255) | Type of relationship |
| attributes      | varchar(255) | Attributes (#tag...) |
| description   | longtext | Description of relationship |
| source_id     | int unsigned | Reference to source entity |
| destination_id | int unsigned | Reference to destination entity |
| reference       | varchar(255) | Reference number of the relation (billing) |
| responsible     | varchar(255) | Responsible of the relation |
| order_number    | varchar(255) | Ordre number (billing) |
| active          | tinyint(1)   | Is the reation still active |
| start_date      | date         | Start date of the relation |
| end_date        | date         | End date of the relation |
| comments        | text         | Comment on the status of the relation |
| importance      | int          | Importance of relationship |
| security_need_c | int          | Confidentiality level need |
| security_need_i | int          | Integrity level need  |
| security_need_a | int          | Available level need |
| security_need_t | int          | Tracability level need  |
| created_at    | timestamp | Date of creation |
| updated_at    | timestamp | Date updated |
| deleted_at    | timestamp | Date of deletion |

### Business view of the information system

The business view of the information system describes all the organization's business processes and the players involved, independently of the technological choices made by the organization and the resources made available to it.

[<img src="/mercator/images/information_system.png" width="600">](/mercator/images/information_system.png)

The business view is essential, as it allows you to reposition technical elements in their business environment, and thus understand their context of use.

#### Macro-processes

Macro-processes represent sets of processes.

Table *macro_processuses* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Name of macro process |
| description | longtext | Description of macro-process |
| io_elements | longtext | Incoming and outgoing elements |
| security_need_c | int | Privacy |
| security_need_i | int | Integrity |
| security_need_a | int | Availability |
| security_need_t | int | Traceability |
| owner | varchar(255) | Owner |
| created_at | timestamp | Date de création |
| updated_at | timestamp | Date de mise à jour |
| deleted_at | timestamp | Date of deletion |


#### Processes

Processes are a set of activities designed to achieve an objective. The process produces value-added information (output) (in the form of deliverables) from information (input) produced by other processes.

Processes are made up of activities, the entities involved in the process, and the information processed by the process.

Table *processes* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| identifier | varchar(255) | Process name |
| description | longtext | Process description |
| owner | varchar(255) | Process owner |
| in_out | longtext | incoming and outgoing elements |
| security_need_c | int | Confidentiality |
| security_need_i | int | Integrity |
| security_need_a | int | Availability |
| security_need_t | int | Traceability |
| macroprocess_id | int unsigned | Reference to macro-process |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |

#### Activities

An activity is a step required to carry out a process. It corresponds to a speciﬁc know-how and not necessarily to an organizational structure of the company.

Table *activities* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | activity name |
| description | longtext | Activity description |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date de mise à jour |
| deleted_at | timestamp | Date of deletion |

#### Operations

An operation is made up of actors and tasks.

Table *operations* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Name of operation |
| description | longtext | Description of operation |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |


#### Tasks

A task is an elementary activity performed by an organizational function and constituting an indivisible unit of work in the value-added chain of a process.

Table *tasks* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Task name |
| description | longtext | Task description |
| created_at | timestamp | creation date |
| updated_at | timestamp | Date updated |
| deleted_at | timestamp | Date of deletion |

#### Actors

An actor is a representative of a business role who performs operations, uses applications and makes decisions within processes. This role can be carried by a person, a group of people or an entity.

Table *actors* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | actor's name |
| nature | varchar(255) | Nature of actor |
| type | varchar(255) | Type of actor |
| contact | varchar(255) | Actor contact |
| created_at | timestamp | Date created |
| updated_at | timestamp | Date updated |
| deleted_at | timestamp | Date of deletion |

#### Information

Information is data that is processed by a computer.

Table *information* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Name of information |
| description | longtext | Description of information |
| owner | varchar(255) | Owner of information |
| administrator | varchar(255) | Information administrator |
| storage | varchar(255) | Information storage |
| security_need_c | int | Confidentiality |
| security_need_i | int | Integrity |
| security_need_a | int | Availability |
| security_need_t | int | Traceability |
| sensitivity | varchar(255) | Sensitivity of information |
| constraints | longtext | Legal and regulatory constraints |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date de mise à jour |
| deleted_at | timestamp | Date de suppression |

### Application view

The application view is used to describe part of what is classically referred to as the "computer system".

[<img src="/mercator/images/applications.png" width="600">](/mercator/images/applications.png)

This view describes the technological solutions that support business processes, mainly applications.

#### Application block

An application block represents a set of applications.

An application block can be: office applications, management applications, analysis applications, development applications, etc.

Table *application_blocks* :

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Name of information |
| description | longtext | Description of application block |
| responsible | varchar(255) | Responsible for application block |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date de mise à jour |
| deleted_at | timestamp | Date of deletion |

#### Application

An application is a coherent set of IT objects (executables, programs, data, etc.). It is a grouping of application services.

An application can be deployed on one or more logical servers.

When there is no virtualized environment, there are not several logical servers per physical server, but one logical server per physical server.

Table *m_applications* :


| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id                  | int unsigned | auto_increment |
| name                | varchar(255) | Nom de l'application |
| version             | varchar(255) | Version de l'application |        
| description         | longtext     | Description de l'application |
| security_need_c     | int          | Confidentialité |
| security_need_i     | int          | Intégrité |
| security_need_a     | int          | Disponibilité |
| security_need_t     | int          | Traçabilité |
| type            | varchar(255) | Type d'application |
| technology      | varchar(255) | Technologie |
| external        | varchar(255) | Externe |
| users           | varchar(255) | Nombre d'utilisateurs et type |
| documentation   | varchar(255) | Lien vers la documentation |
| entity_resp_id  | int unsigned | Entité responsable de l'exploitation  |
| responsible         | varchar(255) | Responsable de l'application |
| application_block_id | int unsigned | Lien vers la bloc applicatif |
| install_date    | datetime    | Date d'installation de l'application |
| update_date     | datetime    | Date de mise à jour de l'application |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |


#### Service applicatif

Un service applicatif est un élément de découpage de l’application mis à disposition de l’utilisateur final dans le cadre de son travail.

Un service applicatif peut, une API, ...

Table *application_services* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom du service applicatif |
| description     | longtext     | Description du service applicatif |
| exposition      | varchar(255) | Exposition du service applicatif |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |


#### Module applicatif

Un module applicatif est un composant d’une application caractérisé par une cohérence fonctionnelle en matière d’informatique et une homogénéité technologique.

Table *application_modules* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom du service applicatif |
| description     | longtext     | Description du module applicatif |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |


#### Base de données

Une base de données est un ensemble structuré et ordonné d’informations destinées à être exploitées informatiquement.

Table *databases* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom du service applicatif |
| description     | longtext     | Description du module applicatif |
| responsible     | varchar(255) | Responsable de l'application |
| type            | varchar(255) | Responsable de l'application |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| external        | varchar(255) | Externe |
| entity_resp_id  | int unsigned | Entité responsable  |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Flux

Un flux est un échange d’informations entre un émetteur ou un récepteur (application, service applicatif, module applicatif ou base de données).

Un flux représente un échange d’information entre deux éléments du système d’information. Il faut éviter de représenter en termes de flux l’ensemble des règles de filtrage du firewall.

Par exemple, les requêtes DNS ou NTP ne devraient pas être représentées comme des flux.

Table *fluxes* :

| Champ                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Nom du flux |
| description           | longtext     | Description du flux |
| application_source_id | int unsigned | Lien vers l'application source |
| service_source_id     | int unsigned | Lien vers le service source |
| module_source_id      | int unsigned | Lien vers le module source |
| database_source_id    | int unsigned | Lien vars la base de données source |
| application_dest_id   | int unsigned | Lien vers l'application destinataire  |
| service_dest_id       | int unsigned | Lien vers le service destinataire |
| module_dest_id        | int unsigned | Lien vers le module destinataire |
| database_dest_id      | int unsigned | Lien vers la basede données destinataire |
| crypted               | tinyint(1)   | Le flux est chiffré (1=oui, O=non) |
| bidirectional         | tinyint(1)   | Le flux est bidirectionnel (1=oui, O=non)|
| created_at            | timestamp    | Date de création |
| updated_at            | timestamp    | Date de mise à jour |
| deleted_at            | timestamp    | Date de suppression |

### L’administration

La vue de l’administration répertorie l’administration des ressources, des annuaires et les niveaux de privilèges des utilisateurs du système d’information.

[<img src="/mercator/images/administration.png" width="400">](/mercator/images/administration.png)

Disposer d’annuaires et d’une centralisation des droits d’accès des utilisateurs est fortement recommandé pour les opérateurs d’importance vitale (OIV).

#### Zone d’administration

Une zone d’administration est un ensemble de ressources (personnes, données, équipements) sous la responsabilité d’un (ou plusieurs) administrateur(s).

Une zone d’administration est composée de services d’annuaires et de forêts Active Directory (AD) ou d’arborescences LDAP.

Table *zone_admins* :

| Champ                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Nom de la zone |
| description           | longtext     | Description de la zone |
| created_at            | timestamp    | Date de création |
| updated_at            | timestamp    | Date de mise à jour |
| deleted_at            | timestamp    | Date de suppression |

#### Service d’annuaire d’administration

Un service d’annuaire d’administration est une application regroupant les données sur les utilisateurs ou les équipements informatiques de l’entreprise et permettant leur administration.

Il peut s’agit d’un outil d’inventaire servant à la gestion des changements ou des tickets ou d’un outil de cartographie comme Mercator.

Table *annuaires*;

| Champ                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Nom de l'annuaire |
| description           | longtext     | Description de l'annuaire |
| solution              | varchar(255) | Solution technique |
| zone_admin_id         | int unsigned | Référence vers la zone d'administration |
| created_at            | timestamp    | Date de création |
| updated_at            | timestamp    | Date de mise à jour |
| deleted_at            | timestamp    | Date de suppression |

#### Forêt Active Directory / Arborescence LDAP

Ces objets représentent un regroupement organisé de domaines Active Directory ou d’arborescence LDAP.

Table *forest_ads* :

| Champ                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Nom de la forêt Active Directory ou de l'arborescence LDAP |
| description           | longtext     | Description de la forêt Active Directory ou de l'arborescence LDAP |
| zone_admin_id         | int unsigned | Référence vers la zone d'administration |
| created_at            | timestamp    | Date de création |
| updated_at            | timestamp    | Date de mise à jour |
| deleted_at            | timestamp    | Date de suppression |

### L’infrastructure logiques

La vue de l'infrastructure logique correspond à la répartition logique du réseau.

[<img src="/mercator/images/logical.png" width="400">](/mercator/images/logical.png)

Elle illustre le cloisonnement des réseaux et les liens logiques entre eux. En outre, elle répertorie les équipements réseau en charge du trafic.

#### Réseaux

Les réseaux sont un ensemble d’équipements reliés logiquement entre eux et qui échangent des informations.

Table *networks* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom du réseau |
| description     | longtext     | Description du réseau |
| protocol_type   | varchar(255) | Protocoles utilisés |
| responsible     | varchar(255) | Responsable de l'exploitation |
| responsible_sec | varchar(255) | Responsable de la sécurité |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Sous-réseaux

Les sous-réseaux sont une subdivision logique d’un réseau de taille plus importante.

table *subnetworks* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du réseau |
| description          | longtext     | Description du réseau |
| address              | varchar(255) | Range d'adresse du sous-réseau |
| default_gateway      | varchar(255) | Adresse de la passerelle par défaut |
| ip_allocation_type   | varchar(255) | Type d'allocation des adresses |
| responsible_exp      | varchar(255) | Responsable de l'exploitation |
| zone                 | varchar(255) | Nom de la zone firewall associée |
| dmz                  | varchar(255) | |
| wifi                 | varchar(255) | |
| connected_subnets_id | int unsigned | |
| gateway_id           | int unsigned | Lien vars la passerelle |
| vlan_id              | int unsigned | Lien vers le VLAN associé |
| network_id           | int unsigned | Lien vers le réseau associé |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Passerelles d’entrées depuis l’extérieur

Les passerelles sont des composants permettant de relier un réseau local avec l’extérieur.

Table *gateways* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de la passerelle |
| description          | longtext     | Description de la passerelle |
| ip                   | varchar(255) | Adress IP de la passerelle |
| authentification     | varchar(255) | Mode d'authentification |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### Entités extérieures connectées

Les entités extérieures connectées représentent les entités externes connectées au réseau.

Table *external_connected_entities* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de l'entité |
| description          | longtext     | Description de l'entié |
| responsible_sec      | varchar(255) | Responsable de la sécurité de l'entité |
| contacts             | varchar(255) | Contactes de l'entité|
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### Commutateurs réseau

Les commutateurs réseaux sont les composant gérant les connexions entre les différents serveurs au sein d’un réseau.

Table *network_switches* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du commutateur |
| description          | longtext     | Description du commutateur |
| ip                   | varchar(255) | Adresse IP du commutateur |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Routeurs logiques

Les routeurs logiques sont des composants logiques gérant les connexions entre différents réseaux.

Table *routers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du routeur |
| description          | longtext     | Description du routeur |
| rules                | longtext     | Règles de filtrage |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Équipements de sécurité

Les équipements de sécurité sont des composants permettant la supervision du réseau, la détection d’incidents, la protection des équipements ou ayant une fonction de sécurisation du système d’information.

Les équipements de sécurité sont des systèmes de détection d'intrusion (ou IDS : Intrusion Detection System), des systèmes de prévention d'intrusion (ou IPS : Intrustion Prevention System), des systèmes de surveillance des équipements.

Table *security_devices* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de l'équipement |
| description          | longtext     | Description de l'équipement |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Serveurs DHCP

Les serveurs DHCP sont des équipements physiques ou virtuel permettant la gestion des adresses IP d’un réseau.

Table *dhcp_servers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| description          | longtext     | Description du serveur |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Serveurs DNS

Les serveurs de noms de domaine (Domain Name System) sont des équipements physique ou virtuel permettant la conversion d’un nom de domaine en adresse IP.

Table *dnsservers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| description          | longtext     | Description du serveur |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Clusters

Les clusters représentent un ensemble de serveurs logiques hébergés sur un ou plusieurs serveurs physiques

Table *clusters* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| type                 | varchar(255) | Type de cluster |
| description          | longtext     | Description du cluster |
| address_ip           | varchar(255) | Adresses IP du cluster |


#### Serveurs logiques

Les serveurs logiques sont un découpage logique d’un serveur physique. Si le serveur physique n’est pas virtualisé, il est découpé en un seul serveur logique.


Table *logical_servers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| description          | longtext     | Description du serveur |
| net_services         | varchar(255) | Services réseau actifs |
| configuration        | longtext     | Configuration du serveur |
| operating_system     | varchar(255) | Système d'exploitation |
| address_ip           | varchar(255) | Adresses IP du serveur |
| cpu                  | varchar(255) | Nombre de CPU |
| memory               | varchar(255) | Quantité de mémémoire |
| environment          | varchar(255) | Environnement (prod, dev, test, ...) |
| disk                 | int          | Espace disque alloué |
| install_date         | datetime     | Date d'installation du serveur |
| update_date          | datetime     | Date de mise à jour du serveur |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Certificats

Les certificats électroniques sont utilisés pour identifier et authentifier des services, des personnes physiques ou morales, mais aussi pour chiffrer des échanges.  

Les certificats sont des clés SSL, des certificats HTTPS, … Ils sont associés à des serveurs logiques ou des applications.

Table *certificates* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du certificat |
| description          | longtext     | Description du certificat |
| type                 | varchar(255) | Type de certificat (SSL, HTTPS ...) |
| start_validity       | date         | Date de début de validité |
| end_validity         | date         | Date de fin de validité |
| status               | int          | Etat du certificat (RFC 6960) |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

* Note :
    * status = 0 : "Bon"
    * status = 1 : "Révoqué"
    * status = 2 : "Inconnu"

### L’infrastructure physique

La vue des infrastructures physiques décrit les équipements physiques qui composent le système d’information ou qui sont utilisés par celui-ci.

[<img src="/mercator/images/physical.png" width="700">](/mercator/images/physical.png)

Cette vue correspond à la répartition géographique des équipements réseaux au sein des différents sites.

#### Sites

Les sites sont des emplacements géographique rassemblant un ensemble de personnes et/ou de ressources.

Table *dnsservers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du site |
| description          | longtext     | Description du site |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Bâtiments / Salles

Les bâtiments ou salles représentent la localisation des personnes ou ressources à l’intérieur d’un site.

Table *buildings* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du site |
| description          | longtext     | Description du site |
| site_id              | int unsigned | Référence vers le site |
| camera               | tinyint(1)   | Le building / salle est protégé par une caméra |
| badge                | tinyint(1)   | Le building / salle nécessite un accès par badge |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Baies

Les baies sont des armoires techniques rassemblant des équipements de réseau informatique ou de téléphonie.

Table *bays* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de la baie |
| description          | longtext     | Description de la baie |
| room_id              | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Serveurs physiques

Les serveurs physiques sont des machines physiques exécutant un ensemble de services informatiques.

Table *physical_servers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| description          | longtext     | Description du serveur |
| type                 | varchar(255) | Type / modèle du serveur |
| responsible          | varchar(255) | Responsable du serveur |
| configuration        | longtext     | Configuration du serveur |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Postes de travail

Les postes de travail sont des machines physiques permettant à un utilisateur d’accéder au système d’information.

Table *workstations* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du poste de travail |
| description          | longtext     | Description du poste de travail |
| type                 | varchar(255) | Type / modèle du poste de travail |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### Infrastructures de stockage

Les infrastructures de stockage sont des supports physiques ou réseaux de stockage de données : serveur de stockage en réseau (NAS), réseau de stockage (SAN), disque dur…

Table *storage_devices* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de l'infrastructure de stockage |
| description          | longtext     | Description de l'infrastructure de stockage |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Périphériques

Les périphériques sont des composant physiques connectés à un poste de travail afin d’ajouter de nouvelles fonctionnalités (ex. : clavier, souris, imprimante, scanner, etc.)

Table *peripherals* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du périphérique |
| description          | longtext     | Description du périphérique |
| type                 | varchar(255) | Type / modèle du périphériques |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Téléphones

Les téléphones fixe ou portable appartenant à l’organisation.

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du téléphone |
| description          | longtext     | Description du téléphone |
| type                 | varchar(255) | Type / modèle du téléphone |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Commutateurs physiques

Les commutateurs physiques sont des composants physiques gérant les connexions entre les différents serveurs au sein d’un réseau.

Table *physical_switches* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du commutateur physique |
| description          | longtext     | Description du commutateur physique |
| type                 | varchar(255) | Type / modèle du commutateur physique |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Routeurs physiques

Les routeurs physiques sont des composants physiques gérant les connexions entre différents réseaux.

Table *physical_routers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du routeur physique |
| description          | longtext     | Description du routeur physique |
| type                 | varchar(255) | Type / modèle du routeur physique |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Bornes WiFi

Les bornes WiFi sont des équipements matériel permettant l’accès au réseau sans fil wifi.

Table *wifi_terminals* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de la borne wifi |
| description          | longtext     | Description de la bornes wifi |
| type                 | varchar(255) | Type / modèle de la bornes wifi |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### Équipements de sécurité physique

Les équipements de sécurité physique sont des composants permettant la supervision du réseau, la détection d’incidents, la protection des équipements ou ayant une fonction de sécurisation du système d’information

Les équipements de sécurité physique sont des sondes de températures, des caméras, des portes sécurisées, ...

Table *physical_security_devices* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de l'équipement de sécurité |
| description          | longtext     | Description de l'équipement de sécurité |
| type                 | varchar(255) | Type / modèle de l'équipement de sécurité |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### WAN

Les WAN (Wide Area Network) sont des réseaux informatiques reliant des équipements sur des distances importantes. Ils interconnectent généralement des MAN ou LAN entre eux.

Table *wans* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du WAN |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### MAN

Les MAN (Middle Area Network) sont des réseaux informatiques reliant des équipements sur des distances moyennement importantes. Ils interconnectent généralement des LAN entre eux.

Table *mans* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du MAN |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

#### LAN

Les LAN (Local Area Network) sont des réseaux informatiques reliant des équipements sur une aire géographique réduite.

Table *lans* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du LAN |
| description          | longtext     | Description du LAN |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### VLAN

Un VLAN (Virtual Local Area Network) ou réseau local (LAN) virtuel permettant de regrouper logiquement des équipements en s’affranchissant des contraintes physiques.

Table *vlans* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du VLAN |
| description          | longtext     | Description du VLAN |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |
