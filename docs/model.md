## Modèle de données

[<img src="/mercator/images/model.png" width="700">](/mercator/images/model.png)

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
| security_level | longtext     | Niveau de sécurité |
| contact_point  | longtext     | Point de contact |
| description    | longtext     | Description de l'entité |
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
| description    | longtext     | Description de la relation |
| type           | varchar(255) | Type de la relation |
| importance     | int          | Importance de la relation |
| source_id      | int unsigned | Référence vers l'entité source |
| destination_id | int unsigned | Référence vers l'entité destinataire |
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
| description     | longtext     | Description du block applicatif |
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
| responsible         | varchar(255) | Responsable de l'application |
| type            | varchar(255) | Type d'application |
| technology      | varchar(255) | Technologie |
| external        | varchar(255) | Externe |
| users           | varchar(255) | Nombre d'utilisateurs et type |
| documentation   | varchar(255) | Lien vers la documentation |
| entity_resp_id  | int unsigned | Entité responsable de l'exploitation  |
| application_block_id | int unsigned | Lien vers la bloc applicatif |
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

#### Entités extérieures connectées

Les entités extérieures connectées représentent les entités externes connectées au réseau.

#### Commutateurs réseau

Les commutateurs réseaux sont les composant gérant les connexions entre les différents serveurs au sein d’un réseau.

#### Routeurs logiques

Les routeurs logiques sont des composants logiques gérant les connexions entre différents réseaux.

#### Équipements de sécurité 

Les équipements de sécurité sont des composants permettant la supervision du réseau, la détection d’incidents, la protection des équipements ou ayant une fonction de sécurisation du système d’information.

Les équipements de sécurité sont des systèmes de détection d'intrusion (ou IDS : Intrusion Detection System), des systèmes de prévention d'intrusion (ou IPS : Intrustion Prevention System), des systèmes de surveillance des équipements. 

#### Serveurs DHCP

Les serveurs DHCP sont des équipements physiques ou virtuel permettant la gestion des adresses IP d’un réseau.

#### Serveurs DNS

Les serveurs de noms de domaine (Domain Name System) sont des équipements physique ou virtuel permettant la conversion d’un nom de domaine en adresse IP.

#### Serveurs logiques

Les serveurs logiques sont un découpage logique d’un serveur physique. Si le serveur physique n’est pas virtualisé, il est découpé en un seul serveur logique.

Dans la cas de la virtualisation d’un groupe de serveurs physique aussi appelé « cluster », on peut associer tous les serveurs physiques du cluster au serveur logique.

#### Certificats

Les certificats électroniques sont utilisés pour identifier et authentifier des services, des personnes physiques ou morales, mais aussi pour chiffrer des échanges.  

Les certificats sont des clés SSL, des certificats HTTPS, … Ils sont associés à des serveurs logiques ou des applications.

### L’infrastructure physique

La vue des infrastructures physiques décrit les équipements physiques qui composent le système d’information ou qui sont utilisés par celui-ci. 

[<img src="/mercator/images/physical.png" width="700">](/mercator/images/physical.png)

Cette vue correspond à la répartition géographique des équipements réseaux au sein des différents sites.

#### Sites

Les sites sont des emplacements géographique rassemblant un ensemble de personnes et/ou de ressources.

#### Bâtiments / Salles

Les bâtiments ou salles représentent la localisation des personnes ou ressources à l’intérieur d’un site.

#### Baies

Les baies sont des armoires techniques rassemblant des équipements de réseau informatique ou de téléphonie.

#### Serveurs physiques

Les serveurs physiques sont des machines physiques exécutant un ensemble de services informatiques.

#### Postes de travail

Les postes de travail sont des machines physiques permettant à un utilisateur d’accéder au système d’information.

#### Infrastructures de stockage

Les infrastructures de stockage sont des supports physiques ou réseaux de stockage de données : serveur de stockage en réseau (NAS), réseau de stockage (SAN), disque dur…

#### Périphériques

Les périphériques sont des composant physiques connectés à un poste de travail afin d’ajouter de nouvelles fonctionnalités (ex. : clavier, souris, imprimante, scanner, etc.)

#### Téléphones

Les téléphones fixe ou portable appartenant à l’organisation.

#### Commutateurs physiques

Les commutateurs physiques sont des composants physiques gérant les connexions entre les différents serveurs au sein d’un réseau.

#### Routeurs physiques

Les routeurs physiques sont des composants physiques gérant les connexions entre différents réseaux.

#### Bornes WiFi

Les Bornes WiFi sont des équipements matériel permettant l’accès au réseau sans fil wifi.

#### Équipements de sécurité physique

Les équipements de sécurité physique sont des composants permettant la supervision du réseau, la détection d’incidents, la protection des équipements ou ayant une fonction de sécurisation du système d’information

Les équipements de sécurité physique sont des sondes de températures, des caméras, des portes sécurisées, ...

#### WAN

Les WAN (Wide Area Network) sont des réseaux informatiques reliant des équipements sur des distances importantes. Ils interconnectent généralement des MAN ou LAN entre eux.

#### MAN

Les MAN (Middle Area Network) sont des réseaux informatiques reliant des équipements sur des distances moyennement importantes. Ils interconnectent généralement des LAN entre eux.

#### LAN

Les LAN (Local Area Network) sont des réseaux informatiques reliant des équipements sur une aire géographique réduite.

#### VLAN

Un VLAN (Virtual Local Area Network) ou réseau local (LAN) virtuel permettant de regrouper logiquement des équipements en s’affranchissant des contraintes physiques.
