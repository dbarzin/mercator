## Modèle de données

[<img src="/mercator/images/model.fr.png" width="700">](/mercator/images/model.fr.png)

### Vue du RGPD

La vue du RGPD contient l'ensemble des données nécessaires au maintien du registre des traitements et fait le lien avec les processus, applications et informations utilisées par le système d'information.

Cette vue permet de remplir les obligations prévues à l’article 30 du RGPD.

#### Registre

Le registre des activités de traitement contient les informations prévues à l'article 30.1 du RGPD.

Table *data_processing* :

| Champ       | Type         | Description          |
|:------------|:-------------|:---------------------|
| id          | int unsigned | auto_increment |
| name        | varchar(255) | Nom du traitement |
| description | longtext     | Description du traitement |
| responsible | longtext     | Responsable du traitement |
| purpose     | longtext     | Finalités du traitement  |
| categories  | longtext     | Catégories de destinataires  |
| recipients  | longtext     | Destinataires des données  |
| transfert   | longtext     | Transferts de données  |
| retention   | longtext     | Durées de rétention  |
| created_at  | timestamp    | Date de création |
| updated_at  | timestamp    | Date de mise à jour |
| deleted_at  | timestamp    | Date de suppression |


#### Mesures de sécurité

Cette table permet d'identifier les mesures de sécurité appliquées aux processus et applications.

Par défaut cette table est complétée avec les mesures de sécurité de la norme ISO 27001:2022.

Table *security_controls* :

| Champ       | Type         | Description          |
|:------------|:-------------|:---------------------|
| id          | int unsigned | auto_increment |
| name        | varchar(255) | Nom de la mesure |
| description | longtext     | Description de la mesure |
| created_at  | timestamp    | Date de création |
| updated_at  | timestamp    | Date de mise à jour |
| deleted_at  | timestamp    | Date de suppression |


### Vue de l’écosystème

La vue de l’écosystème décrit l’ensemble des entités ou systèmes qui gravitent autour du système d’information considéré dans le cadre de la cartographie.

[<img src="/mercator/images/ecosystem.png" width="600">](/mercator/images/ecosystem.png)

Cette vue permet à la fois de délimiter le périmètre de la cartographie, mais aussi de disposer d’une vision d’ensemble de l’écosystème sans se limiter à l’étude individuelle de chaque entité.

#### Entités

Les entités sont une partie de l’organisme (ex. : filiale, département, etc.) ou en relation avec le système d’information qui vise à être cartographié.

Les entités sont des départements, des fournisseurs, des partenaires avec lesquels des informations sont échangées au travers de relations.

Table *entities* :

| Champ          | Type         | Description      |
|:---------------|:-------------|:-----------------|
| id             | int unsigned | auto_increment |
| name           | varchar(255) | Nom de l'entité |
| entity_type       | varchar(255) | Type d'entité |
| attributes        | varchar(255) | Attributs (#tag...) |
| description    | longtext     | Description de l'entité |
| reference         | varchar(255) | Numéro de référence de l'entité (facturation) |
| parent_entity_id  | int unsigned | Entité parente |
| is_external    | boolean      | Entité externe |
| security_level | longtext     | Niveau de sécurité |
| contact_point  | longtext     | Point de contact |
| created_at     | timestamp    | Date de création |
| updated_at     | timestamp    | Date de mise à jour |
| deleted_at     | timestamp    | Date de suppression |


#### Relations

Les relations représentent un lien entre deux entités ou systèmes.

Les relations sont des contrats, accords de services, des obligations légales... qui ont une influence sur le système d’information.

Table *relations* :

| Champ          | Type         | Description      |
|:---------------|:-------------|:-----------------|
| id             | int unsigned | auto_increment |
| name           | varchar(255) | Nom de la relation |
| type           | varchar(255) | Type de la relation |
| description    | longtext     | Description de la relation |
| source_id      | int unsigned | Référence vers l'entité source |
| destination_id | int unsigned | Référence vers l'entité destinataire |
| reference       | varchar(255) | Numéro de référence de la relation (facturation) |
| responsible     | varchar(255) | Responsable de la relation |
| order_number    | varchar(255) | Numéro de commande (facturation) |
| active          | tinyint(1)   | La relation est encore active |
| start_date      | date         | Début de la relation |
| end_date        | date         | Fin de la relation |
| comments        | text         | Commentaires sur l'état de la relation |
| importance      | int          | Importance de la relation |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| created_at     | timestamp    | Date de création |
| updated_at     | timestamp    | Date de mise à jour |
| deleted_at     | timestamp    | Date de suppression |


### Vue métier du système d’information

La vue métier du système d’information décrit l’ensemble des processus métiers de l’organisme avec les acteurs qui y participent, indépendamment des choix technologiques faits par l’organisme et des ressources mises à sa disposition.

[<img src="/mercator/images/information_system.png" width="600">](/mercator/images/information_system.png)

La vue métier est essentielle, car elle permet de repositionner les éléments techniques dans leur environnement métier et ainsi de comprendre leur contexte d’emploi.

#### Macro-processus

Les macro-processus représentent des ensembles de processus.

Table *macro_processuses* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom du macro processus |
| description     | longtext     | Description du macro-processus |
| io_elements     | longtext     | Elements entrant et sortants |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| owner           | varchar(255) | Propriétaire |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Processus

Les processus sont un ensemble d’activités concourant à un objectif. Le processus produit des informations (de sortie) à valeur ajoutée (sous forme de livrables) à partir d’informations (d’entrées) produites par d’autres processus.

Les processus sont composés d’activités, des entités qui participent à ce processus et des informations traitées par celui-ci.

Table *processes* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| identifiant     | varchar(255) | Nom du processus |
| description     | longtext     | Description du processus  |
| owner           | varchar(255) | Propriétaire du processus  |
| in_out          | longtext     | Elements entrant et sortants |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| macroprocess_id | int unsigned | Référence vers le macro-processus |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Activités

Une activité est une étape nécessaire à la réalisation d’un processus. Elle correspond à un savoir-faire spéciﬁque et pas forcément à une structure organisationnelle de l’entreprise.

Table *activities* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de l'activité |
| description     | longtext     | Description de l'activité |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Opérations

Une opération est composée d’acteurs et de tâches.

Table *operations* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de l'opération |
| description     | longtext     | Description de l'opération |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Tâches

Une tâche est une activité élémentaire exercée par une fonction organisationnelle et constituant une unité indivisible de travail dans la chaîne de valeur ajoutée d’un processus.

Table *tasks* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de la tâche |
| description     | longtext     | Description de tâche |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Acteurs

Un acteur est un représentant d’un rôle métier qui exécute des opérations, utilise des applications et prend des décisions dans le cadre des processus. Ce rôle peut être porté par une personne, un groupe de personnes ou une entité.

Table *actors* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de l'acteur |
| nature          | varchar(255) | Nature de l'acteur |
| type            | varchar(255) | Type d'acteur |
| contact         | varchar(255) | Contact de l'acteur |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Informations

Une information est une donnée faisant l’objet d’un traitement informatique.

Table *information* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de l'information |
| description     | longtext     | Description de l'information |
| owner           | varchar(255) | Propriétaire de l'information |
| administrator   | varchar(255) | Administrateur de l'information |
| storage         | varchar(255) | Stockage de l'information |
| security_need_c | int          | Confidentialité |
| security_need_i | int          | Intégrité |
| security_need_a | int          | Disponibilité |
| security_need_t | int          | Traçabilité |
| sensitivity     | varchar(255) | Sensibilité de l'information |
| constraints     | longtext     | Contraintes légales et réglementaires |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

### La vue des applications

La vue des applications permet de décrire une partie de ce qui est classiquement appelé le « système informatique ».

[<img src="/mercator/images/applications.png" width="600">](/mercator/images/applications.png)

Cette vue décrit les solutions technologiques qui supportent les processus métiers, principalement les applications.

#### Bloc applicatif

Un bloc applicatif représente un ensemble d’application.

Un bloc applicatif peut être : les applications bureautique, de gestion, d’analyse, de développement, ...

Table *application_blocks* :

| Champ           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Nom de l'information |
| description     | longtext     | Description du bloc applicatif |
| responsible     | varchar(255) | Responsable du bloc applicatif |
| created_at      | timestamp    | Date de création |
| updated_at      | timestamp    | Date de mise à jour |
| deleted_at      | timestamp    | Date de suppression |

#### Application

Une application est un ensemble cohérent d’objets informatiques (exécutables, programmes, données...). Elle constitue un regroupement de services applicatifs.

Une application peut être déployée sur un ou plusieurs serveurs logiques.

Lorsqu'il n'y a pas d'environnement virtualisé, il n'y a pas plusieurs serveurs logiques par serveur physique mais il y a un serveur logique par serveur physique.

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

Un service applicatif peut, par exemple, être un service dans le nuage (Cloud).

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
| database_source_id    | int unsigned | Lien vers la base de données source |
| application_dest_id   | int unsigned | Lien vers l'application destinataire  |
| service_dest_id       | int unsigned | Lien vers le service destinataire |
| module_dest_id        | int unsigned | Lien vers le module destinataire |
| database_dest_id      | int unsigned | Lien vers la base de données destinataire |
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

Il peut s’agir d’un outil d’inventaire servant à la gestion des changements ou des tickets ou d’un outil de cartographie comme Mercator.

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

### L’infrastructure logique

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
| address              | varchar(255) | Plage d'adresse du sous-réseau |
| default_gateway      | varchar(255) | Adresse de la passerelle par défaut |
| ip_allocation_type   | varchar(255) | Type d'allocation des adresses |
| responsible_exp      | varchar(255) | Responsable de l'exploitation |
| zone                 | varchar(255) | Nom de la zone firewall associée |
| dmz                  | varchar(255) | Zone démilitarisée |
| wifi                 | varchar(255) | Réseau WiFi |
| connected_subnets_id | int unsigned | Sous-réseaux connectés |
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
| contacts             | varchar(255) | Contacts de l'entité |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |


#### Commutateurs réseau

Les commutateurs réseau sont les composants gérant les connexions entre les différents serveurs au sein d’un réseau.

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

Les serveurs DHCP sont des équipements physiques ou virtuels permettant la gestion des adresses IP d’un réseau.

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

Les serveurs de noms de domaine (Domain Name System) sont des équipements physiques ou virtuels permettant la conversion d’un nom de domaine en adresse IP.

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
| memory               | varchar(255) | Quantité de mémoire |
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


### L’infrastructure physique

La vue des infrastructures physiques décrit les équipements physiques qui composent le système d’information ou qui sont utilisés par celui-ci.

[<img src="/mercator/images/physical.png" width="700">](/mercator/images/physical.png)

Cette vue correspond à la répartition géographique des équipements réseaux au sein des différents sites.

#### Sites

Les sites sont des emplacements géographiques rassemblant un ensemble de personnes et/ou de ressources.

Table *sites* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du site |
| icon_id              | int unsigned | Référence vers une image spécifique |
| description          | longtext     | Description du site |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Dans l'application, un bâtiment / salle peut être rattaché à un site depuis un objet bâtiment / salle.

#### Bâtiments / Salles

Les bâtiments ou salles représentent la localisation des personnes ou ressources à l’intérieur d’un site.

Table *buildings* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du site |
| icon_id              | int unsigned | Référence vers une image spécifique |
| type                 | varchar(255) | Type de salle / bâtiment |
| attributes           | varchar(255) | Attributs du building / salle |
| description          | longtext     | Description du site |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle (gestion parenté)|
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Dans l'application, un bâtiment / salle ou un site peut être rattaché à un bâtiment / salle depuis un objet bâtiment / salle.

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

Dans l'application, une baie peut être rattachée à un bâtiment/ baie depuis un objet baie. 

#### Serveurs physiques

Les serveurs physiques sont des machines physiques exécutant un ensemble de services informatiques.

Table *physical_servers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du serveur |
| icon_id              | int unsigned | Référence vers une image spécifique |
| description          | longtext     | Description du serveur |
| type                 | varchar(255) | Type / modèle du serveur |
| cpu                  | varchar(255) | Processeur(s) du serveur |
| memory               | varchar(255) | RAM / mémoive vive du serveur |
| disk                 | varchar(255) | Stockage du serveur | 
| disk_used            | varchar(255) | Stockage utilisé du serveur |
| configuration        | longtext     | Configuration du serveur |
| operating_system     | varchar(255) | Système d'exploitaion du serveur |
| install_date         | datetime     | Date d'installation du serveur |
| update_date          | datetime     | Date de mise à jour du serveur |
| responsible          | varchar(255) | Responsable d'exploitation du serveur |
| address_ip           | varchar      | Adresse(s) IP du serveur |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

L'export du modèle de données référence les applications, les clusters (logiques) et les serveurs logiques rattachés à un serveur physique.\
Dans l'application, une application peut être rattachée à un serveur physique depuis un objet serveur physique.\
Un cluster peut être rattachée à un serveur physique depuis ces deux types d'objets.\
Un serveur logique peut être rattachée à un serveur physique depuis ces deux types d'objets.

Pour une question de lisibilité, les champs définis dans le modèle de données mais inutilisés pour le moment dans l'application pour la table *physical_servers* ont été regroupés dans le tableau suivant :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| patching_group       | varchar(255) | Groupe de mise à jour |
| patching_frequency   | varchar(255) | Fréquence des mises à jour |
| next_update          | date         | Date de la prochaine mise à jour |
| physical_swicth_id   | int unsigned | Référence vers le commutateur physique|

#### Postes de travail

Les postes de travail sont des machines physiques permettant à un utilisateur d’accéder au système d’information.

Table *workstations* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du poste de travail |
| icon_id              | int unsigned | Référence vers une image spécifique |
| status               | varchar(255) | Status du poste (cyle de vie, incident) |
| description          | longtext     | Description du poste de travail |
| type                 | varchar(255) | Type / modèle du poste de travail |
| entity_id            | int unsigned | Référence vers l'entité utilisatrice du poste |
| domain_id            | int unsigned | Référence vers le domaine d'identification des utilisateurs |
| user_id              | int unsigned | Référence vers les utilisateurs du poste si intégrés au domaine |
| other_user           | int unsigned | Utilisateurs du poste, si non intégrés au domaine |
| manufacturer         | varchar(255) | Fabriquant du poste |
| model                | varchar(255) | Modèle du poste |
| serial_number        | varchar(255) | Numéro de série |
| cpu                  | varchar(255) | Processeur(s) du poste |
| memory               | varchar(255) | RAM / mémoive vive du poste |
| disk                 | int signed   | Quantité de stockage interne du poste |
| operating_system     | varchar(255) | Système d'exploitaion du poste |
| network_id           | int unsigned | Référence vers le réseau d'appartenance du poste |
| address_ip           | varchar(255) | Adresse(s) IP du poste |
| mac_address          | varchar(255) | Adresse(s) MAC / physique(s) du poste |
| network_port_type    | varchar(255) | Format du connecteur réseau (RJ45, USB, SFP, etc.) |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

L'export du modèle de données référence les applications rattachées à un poste de travail.\
Dans l'application, une application peut être rattachée à un poste de travail depuis un objet poste de travail.

Pour une question de lisibilité, les champs définis dans le modèle de données mais inutilisés pour le moment dans l'application pour la table *workstations* ont été regroupés dans le tableau suivant :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| warranty             | varchar(255) | Contrat de garantie |
| warranty_start_date  | date         | Date de début de la garantie |
| warranty_end_date    | date         | Date de fin de la garantie |
| warranty_period      | date         | Période de garantie |
| purchase_date        | date         | Date d'achat |
| fin_value            | decimal      | Valeur financière. Borne sup. : $10^{11}$ | 
| last_inventory_date  | date         | Date du dernier inventaire |
| update_source        | varchar(255) | Source de la mise à jour / inventaire |
| agent_version        | varchar(255) | Version de l'agent d'inventaire |
| physical_swicth_id   | int unsigned | Référence vers le commutateur physique|

#### Infrastructures de stockage

Les infrastructures de stockage sont des supports physiques ou réseaux de stockage de données : serveur de stockage en réseau (NAS), réseau de stockage (SAN), disque dur…

Table *storage_devices* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de l'infrastructure de stockage |
| type                 | varchar(255) | Type de l'infractructure de stockage |
| description          | longtext     | Description de l'infrastructure de stockage |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| address_ip           | varchar(255) | Adresse IP de l'infrastructure de stockage |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Les champs "vendor", "product" et "version" ne sont pas utilisés pour le moment et sont donc absent de l'application.

#### Périphériques

Les périphériques sont des composants physiques connectés à un poste de travail afin d’ajouter de nouvelles fonctionnalités (ex. : clavier, souris, imprimante, scanner, etc.)

Table *peripherals* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du périphérique |
| description          | longtext     | Description du périphérique |
| type                 | varchar(255) | Type / modèle du périphérique |
| icon_id              | int unsigned | Référence vers une image spécifique |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| responsible          | varchar(255) | Responsable interne de la gestion de l'équipement |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| address_ip           | varchar(255) | Adresse IP de l'équipement |
| domain               | varchar(255) | Domaine général d'appartenance (IT, OT, IOT, etc.) |
| provider_id          | int unsigned | Référence vers l'entité fournisseuse |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

L'export du modèle de données référence les applications utilisant un périphérique.\
Dans l'application, un périphérique peut être rattaché à une application depuis un objet périphérique.

#### Téléphones

Les téléphones fixes ou portables appartenant à l’organisation.

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du téléphone |
| description          | longtext     | Description du téléphone |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| type                 | varchar(255) | Type / modèle du téléphone |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| physical_switch_id   | int unsigned | Référence vers le commutateur physique |
| address_ip           | varchar(255) | Adresse IP du téléphone |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Les champs "vendor", "product" et "version" ne sont pas utilisés pour le moment et sont donc absent de l'application.\
Le champ "physical_switch_id" n'est pas utilisé pour le moment et est donc absent de l'application. Cependant, un téléphone peut être rattaché à un commutateur réseau en utilisant l'objet lien physique.

#### Commutateurs physiques

Les commutateurs physiques sont des composants physiques gérant les connexions entre les différents serveurs au sein d’un réseau.

Table *physical_switches* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du commutateur physique |
| description          | longtext     | Description du commutateur physique |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| type                 | varchar(255) | Type / modèle du commutateur physique |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | unsigned int | Référence vers la baie |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Les champs "vendor", "product" et "version" ne sont pas utilisés pour le moment et sont donc absent dans l'application.\
L'export du modèle de données référence les commutateurs logiques rattachés à un commutateur physique.\
Dans l'application, un commutateur physique peut être rattaché à un commutateur logique (noté comme "Commutateurs réseau") depuis ces deux types d'objets.

#### Routeurs physiques

Les routeurs physiques sont des composants physiques gérant les connexions entre différents réseaux.

Table *physical_routers* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du routeur physique |
| description          | longtext     | Description du routeur physique |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| type                 | varchar(255) | Type / modèle du routeur physique |
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Les champs "vendor", "product" et "version" ne sont pas utilisés pour le moment et sont donc absent dans l'application.\
L'export du modèle de données référence les routeurs logiques et les VLAN rattachés à un routeur physique.\
Dans l'application, un routeur physique peut être rattaché à un routeur logique (noté comme "Routeurs" depuis ces deux types d'objets.\
Un VLAN peut être rattaché à un routeur physique depuis un objet routeur physique.

#### Bornes WiFi

Les bornes WiFi sont des équipements matériel permettant l’accès au réseau sans fil wifi.

Table *wifi_terminals* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom de la borne wifi |
| description          | longtext     | Description de la bornes wifi |
| vendor               | varchar(255) | Vendeur / éditeur pour recherche CPE |
| product              | varchar(255) | Produit d'un éditeur pour recherche CPE |
| version              | varchar(255) | Version d'un produit pour recherche CPE |
| type                 | varchar(255) | Type / modèle de la bornes wifi |
| address_ip           | varchar(255) | Adresse IP de la borne wifi |
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
| icon_id              | int unsigned | Référence vers une image spécifique |
| description          | longtext     | Description de l'équipement de sécurité |
| type                 | varchar(255) | Type / modèle de l'équipement de sécurité |
| attributes           | varchar(255) | Attributs (#tags...)|
| site_id              | int unsigned | Référence vers le site |
| building_id          | int unsigned | Référence vers le building / salle |
| bay_id               | int unsigned | Référence vers la baie |
| address_ip           | varchar(255) | Adresse(s) IP de l'équipement |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

L'export du modèle de données référence les équipements de sécurité logiques rattachés aux équipements de sécurité physiques.\
Dans l'application, un équipement de sécurité physique peut être rattaché à un équipement de sécurité logique depuis ces deux types d'objets.

#### Liens physiques

Table *physical_links* :

Principe général :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| *device*_src_id      | int unsigned | Actif source |
| src_id               | varchar(255) | Port physique de l'actif source |
| *device*_dst_id      | int unsigned | Actif de destination |
| dst_port             | varchar(255) | Port physique de l'actif de destination |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

Les actifs sources et destination peuvent être :
| Actif                           | Source   | Destination  |
|:--------------------------------|:---------|:-------------|
| Périphérique                    | oui | oui |
| Téléphone                       | oui | oui |
| Routeur physique                | oui | oui |
| Equipement de sécurité physique | oui | oui |
| Serveur physique                | oui | oui |
| Commutateur physique            | oui | oui |
| Infrastructure de stockage      | oui | oui |
| Borne Wifi                      | oui | oui |
| Poste de travail                | oui | oui |
| Serveur logique                 | oui | oui |
| Commutateur logique             | oui | oui |
| Routeur logique                 | oui | oui |

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

L'export du modèle de données référence les MAN et LAN rattachés à un WAN.\
Dans l'application, un WAN peut être rattaché à un MAN ou un LAN depuis les objets WAN.

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

L'export du modèle de données référence les WAN et LAN rattachés à un MAN.\
Dans l'application, un MAN peut être rattaché à un WAN depuis les objets WAN.\
Un LAN peut être rattaché à un MAN depuis les objets MAN.

#### LAN

Les LAN (Local Area Network) sont des réseaux informatiques reliant des équipements sur une aire géographique réduite.

Table *lans* :

| Champ                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Nom du LAN |
| description          | varchar(255) | Description du LAN |
| created_at           | timestamp    | Date de création |
| updated_at           | timestamp    | Date de mise à jour |
| deleted_at           | timestamp    | Date de suppression |

L'export du modèle de données référence les MAN et les WAN rattachés à un LAN.\
Dans l'application, un LAN peut être rattaché à un MAN ou un WAN depuis les objets MAN et WAN.
