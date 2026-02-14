## Data model

[<img src="/mercator/images/model.png" width="700">](/mercator/images/model.png)

### GDPR view

The GDPR view contains all the data required to maintain the data processing register, and provides a link with the processes, applications and information used by the information system.

This view is used to fulfill the obligations set out in article 30 of the GDPR.

#### Register

The register of processing activities contains the information required by article 30.1 of the GDPR.

| Table            | api             |
|:-----------------|:----------------|
| <span style="color: blue;">*data_processing*</span>  | `/api/data-processings` |

| Field                          | Type         | Description                             |
|:-------------------------------|:-------------|:----------------------------------------|
| id                             | int unsigned | auto_increment                          |
| name                           | varchar(255) | Processing name                         |
| description                    | longtext     | Processing description                  |
| legal_basis                    | varchar(255) | Legal basis for processing              |
| responsible                    | longtext     | Responsible person for processing       |
| purpose                        | longtext     | Purposes of processing                  |
| lawfulness                     | text         | Legality of processing                  |
| lawfulness_consent             | tinyint(1)   | Legality based on consent               |
| lawfulness_contract            | tinyint(1)   | Contractual legality                    |
| lawfulness_legal_obligation    | tinyint(1)   | Legality based on legal obligation      |
| lawfulness_vital_interest      | tinyint(1)   | Legality based on vital interest        |
| lawfulness_public_interest     | tinyint(1)   | Legality based on public interest       |
| lawfulness_legitimate_interest | tinyint(1)   | Legality based on legitimate interest   |
| categories                     | longtext     | Categories of recipients                |
| recipients                     | longtext     | Data recipients                         |
| transfer                       | longtext     | Data transfers                          |
| retention                      | longtext     | Retention periods                       |
| controls                       | longtext     | Security Measures                       |
| created_at                     | timestamp    | Date of creation                        |
| updated_at                     | timestamp    | Date of update                          |
| deleted_at                     | timestamp    | Date of deletion                        |

The "controls" field is not used and is not implemented yet in mercator.

Data model export references processes, information, applications and documents 
related to data processing.  

In the application, a process can be linked to data processing from a processing object 
of data.  
Information can be linked to data processing from a data processing object.

An application can be linked to data processing from a data processing object.  
A document can be attached to data processing from a data processing object.  

#### Security measures

This table identifies the security measures applied to processes and applications.

By default, this table is populated with the security measures of ISO 27001:2022.

| Table            | api             |
|:-----------------|:----------------|
| <span style="color: blue;">*security_controls*</span>  | `/api/security-controls` |

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

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*entities*</span>      | `/api/entities`    |

| Field             | Type         | Description                              |
|:------------------|:-------------|:-----------------------------------------|
| id                | int unsigned | Unique identifier of the entity          |
| name              | varchar(255) | Name of entity                           |
| icon_id           | int unsigned | Reference to a specific image            |
| entity_type       | varchar(255) | Type of entity                           |
| attributes        | varchar(255) | Attributes (#tag...)                     |
| description       | longtext     | Entity description                       |
| reference         | varchar(255) | Reference number of the entity (billing) |
| parent_entity_id  | int unsigned | Pointer to the parent entity             |
| is_external       | boolean      | External entity                          |
| security_level    | longtext     | Security level                           |
| contact_point     | longtext     | Contact point                            |
| created_at        | timestamp    | Date of creation                         |
| updated_at        | timestamp    | Date of update                           |
| deleted_at        | timestamp    | Date of deletion                         |

The "external_ref_id" field is not used and is therefore missing from the application.  

Exporting the data model references the processes and applications attached to an entity.

In the application, a process can be attached to an entity from these two objects.  
An application can be attached to an entity (as operations manager) from these two objects.

In the application, a database can be attached to an entity (as operations manager)
from these two objects. 

#### Relationships

Relationships represent a link between two entities or systems.

Relationships are contracts, service agreements, legal obligations... that have an influence on the information system.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*relations*</span>      | `/api/relations`    |

| Field              | Type         | Description                                |
|:-------------------|:-------------|:-------------------------------------------|
| id                 | int unsigned | auto_increment                             |
| name               | varchar(255) | Relationship name                          |
| type               | varchar(255) | Type of relationship                       |
| attributes         | varchar(255) | Attributes (#tag...)                       |
| description        | longtext     | Description of relationship                |
| source_id          | int unsigned | Reference to source entity                 |
| destination_id     | int unsigned | Reference to destination entity            |
| reference          | varchar(255) | Reference number of the relation (billing) |
| responsible        | varchar(255) | Responsible of the relation                |
| order_number       | varchar(255) | Ordre number (billing)                     |
| active             | tinyint(1)   | Is the reation still active                |
| start_date         | date         | Start date of the relation                 |
| end_date           | date         | End date of the relation                   |
| comments           | text         | Comment on the status of the relation      |
| importance         | int          | Importance of relationship                 |
| security_need_c    | int          | Confidentiality level need                 |
| security_need_i    | int          | Integrity level need                       |
| security_need_a    | int          | Available level need                       |
| security_need_t    | int          | Traceability level need                    |
| security_need_auth | int          | Need for authentication                      |
| created_at         | timestamp    | Date of creation                           |
| updated_at         | timestamp    | Date of update                             |
| deleted_at         | timestamp    | Date of deletion                           |

In the app, the need for authentication, "security_need_auth", is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu.  

exporting the data model references the reference documents attached to a relationship.  
In the application, a document can be attached to a relationship from a relationships object. 

The financial values of a contract can be indicated in dedicated fields.  

---

### Business view of the information system

The business view of the information system describes all the organization's business processes and the players involved, independently of the technological choices made by the organization and the resources made available to it.

[<img src="/mercator/images/information_system.png" width="600">](/mercator/images/information_system.png)

The business view is essential, as it allows you to reposition technical elements in their business environment, and thus understand their context of use.

#### Macro-processes

Macro-processes represent sets of processes.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*macro-processuses*</span>      | `/api/macro-processuses`    |

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
| security_need_auth | int          | Authentication                   |
| owner | varchar(255) | Owner |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |

In the app, the need for authentication, "security_need_auth", is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu.  

In the application, a process can be attached to a macro-process from these two objects.

#### Processes

Processes are a set of activities designed to achieve an objective. The process produces value-added information (output) (in the form of deliverables) from information (input) produced by other processes.

Processes are made up of activities, the entities involved in the process, and the information processed by the process.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*processes*</span>      | `/api/processes`    |

| Field              | Type         | Description                         |
|:-------------------|:-------------|:------------------------------------|
| id                 | int unsigned | auto_increment                      |
| name               | varchar(255) | Process name                        |
| description        | longtext     | Process description                 |
| icon_id            | int unsigned | Reference to a specific image       |
| owner              | varchar(255) | Process owner                       |
| in_out             | longtext     | incoming and outgoing elements      |
| security_need_c    | int          | Confidentiality                     |
| security_need_i    | int          | Integrity                           |
| security_need_a    | int          | Availability                        |
| security_need_t    | int          | Traceability                        |
| macroprocess_id    | int unsigned | Reference to macro-process          |
| activities         | List int [,] | IDs list of related activities      |
| entities           | List int [,] | IDs list of related entities        |
| informations       | List int [,] | IDs list of related informations    |
| applications       | List int [,] | IDs list of related applications    |
| operations         | List int [,] | IDs list of related operations      |
| created_at         | timestamp    | Date of creation                    |
| updated_at         | timestamp    | Date of update                      |
| deleted_at         | timestamp    | Date of deletion                    |

In the app, the need for authentication, "security_need_auth", is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu.  

The export of the data model references:

- entities,
- activities,
- information,
- applications,
- data processing,
- and security measures

linked to a process.

In the application, an entity associated with a process can be attached to a process from these two objects.  
An activity can be linked to a process from these two objects.  
Information can be linked to a process from these two objects.  

An application can be attached to a process from these two objects.  
GDPR registry processing can be attached to a process from a registry processing object.  

A security measure can be attached to an application from the "Assign a security measure" button.  
This button is present in the GDPR view and visible in the list of Security Measures objects.


#### Activities

An activity is a step required to carry out a process. It corresponds to a speciﬁc know-how and not necessarily to an organizational structure of the company.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*activities*</span>      | `/api/activities`    |

| Field                       | Type         | Description |
|:----------------------------|:-------------|:-----------------|
| id                          | int unsigned | auto_increment |
| name                        | varchar(255) | activity name |
| description                 | longtext     | Activity description |
| recovery_time_objective     | int signed   | RTO, Target time for activity recovery           |
| maximum_tolerable_downtime  | int signed   | Maximum Tolerable Disturbance Duration           |
| recovery_point_objective    | int signed   | RPO, Data Restore Time Point                     |
| maximum_tolerable_data_loss | int signed   | Maximum Allowable Data Loss                |     |
| drp                         | text         | Description of the business recovery plan (DRP)  |
| drp_link                    | varchar(255) | Link (URL) to the DRP                            |
| created_at                  | timestamp    | Date of creation |
| updated_at                  | timestamp    | Date of update |
| deleted_at                  | timestamp    | Date of deletion |

In the application, the "Impact Type" and "Gravity" fields are managed in a separate table.


##### Impacts

Impacts are the consequences of the occurrence of a risk during an activity.

Impacts are only accessible through activity objects.  

They are neither importable nor exportable through the graphics tool.  

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*activity_impacts*</span>      |  N/A   |

| Field            | Type          | Description                                              |
|:-----------------|:--------------|:---------------------------------------------------------|
| id               | bigint signed | auto_increment                                           |
| activity_id      | int unsigned  | Link to the activity related to this impact              |
| impact_type      | varchar(255)  | Kind of impact (finance, brand, environnement, other...) |
| severity         | tinyint(4)    | Impact description                                       |
| created_at       | timestamp     | Date of creation                                         |
| updated_at       | timestamp     | Date of update                                           |


#### Operations

An operation is made up of actors and tasks.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*operations*</span>      | `/api/operations`    |

| Field       | Type         | Description                                             |
|:------------|:-------------|:--------------------------------------------------------|
| id          | int unsigned | auto_increment                                          |
| name        | varchar(255) | Name of operation                                       |
| description | longtext     | Description of operation                                |
| process_id  | int unsigned | Reference to the process of which the operation is part |
| actors      | List int [,] | IDs list of related actors                              |
| tasks       | List int [,] | IDs list of related tasks                               |
| activities  | List int [,] | IDs list of related activities                          |
| created_at  | timestamp    | Date of creation |
| updated_at  | timestamp    | Date of update |
| deleted_at  | timestamp    | Date of deletion |

The export of the data model references the activities, actors and tasks linked to an operation.  

In the application, an activity can be linked to an operation from these two objects.  
An actor can be attached to an operation from the operations object.  
A task can be attached to an operation from the operations object.  

#### Tasks

A task is an elementary activity performed by an organizational function and constituting an indivisible unit of work in the value-added chain of a process.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*tasks*</span>      | `/api/tasks`    |

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Task name |
| description | longtext | Task description |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |

Exporting the data model references operations related to a task. In the application.

An operation can be attached to a task from the operations object.

#### Actors

An actor is a representative of a business role who performs operations, uses applications and makes decisions within processes. This role can be carried by a person, a group of people or an entity.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*actors*</span>      | `/api/actors`    |

| Field      | Type         | Description |
|:-----------|:-------------|:-----------------|
| id         | int unsigned | auto_increment |
| name       | varchar(255) | actor's name |
| nature     | varchar(255) | Nature of actor |
| type       | varchar(255) | Type of actor |
| contact    | varchar(255) | Actor contact |
| operations | List int [,] | IDs list of related operations |
| created_at | timestamp    | Date of creation |
| updated_at | timestamp    | Date of update |
| deleted_at | timestamp    | Date of deletion |

#### Information

Information is data that is processed by a computer.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*information*</span>      | `/api/information`    |

| Field              | Type         | Description |
|:-------------------|:-------------|:-----------------|
| id                 | int unsigned | auto_increment |
| name               | varchar(255) | Name of information |
| description        | longtext     | Description of information |
| owner              | varchar(255) | Owner of information |
| administrator      | varchar(255) | Information administrator |
| sensitivity        | varchar(255) | Sensitivity of information |
| storage            | varchar(255) | Information storage |
| security_need_c    | int          | Confidentiality |
| security_need_i    | int          | Integrity |
| security_need_a    | int          | Availability |
| security_need_t    | int          | Traceability |
| security_need_auth | int          | Authentication                    |
| constraints        | longtext     | Legal and regulatory constraints |
| retention          | varchar(255) | Information retention period     |
| created_at         | timestamp    | Date of creation |
| updated_at         | timestamp    | Date of update |
| deleted_at         | timestamp    | Date of deletion |

The "retention" field is not used at the moment and is therefore absent from the application. 

In the app, the need for authentication is hidden by default. It is mandatory in the case from an entity subject to EU Directive 2022/2554 (DORA).
It is activated from the Configuration > Settings menu. 

The export of the data model references the databases and processes attached to information. In the application, a database can be attached to information from the database object. A process can be linked to information from these two objects.

---

### Applications view

The application view is used to describe part of what is classically referred to as the "computer system".

[<img src="/mercator/images/applications.png" width="600">](/mercator/images/applications.png)

This view describes the technological solutions that support business processes, mainly applications.

#### Applications blocks

An application block represents a set of applications.

An application block can be: office applications, management applications, analysis applications, development applications, etc.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*application-blocks*</span>      | `/api/application-blocks`    |

| Field | Type | Description |
|:----------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | Name of information |
| description | longtext | Description of application block |
| responsible | varchar(255) | Responsible for application block |
| created_at | timestamp | Date of creation |
| updated_at | timestamp | Date of update |
| deleted_at | timestamp | Date of deletion |

#### Applications

An application is a coherent set of IT objects (executables, programs, data, etc.). It is a grouping of application services.

An application can be deployed on one or more logical servers.

When there is no virtualized environment, there are not several logical servers per physical server, but one logical server per physical server.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*m_applications*</span>      | `/api/applications`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the application |
| application_block_id | int unsigned | Group of application |
| attributes           | varchar(255) | Attributes of the application |
| description          | longtext     | Description |
| icon_id              | int unsigned | Reference to a specific image         |
| responsible          | varchar(255) | Person/team responsible |
| functional_referent  | varchar(255) | Functional referent |
| editor               | varchar(255) | Application's editor |
| users                | varchar(255) | Number of users and type |
| technology           | varchar(255) | Technology |
| type                 | varchar(255) | Type of application |
| external             | varchar(255) | External |
| install_date         | datetime     | Date of installation |
| update_date          | datetime     | Date of upgrade |
| next_update          | datetime     | Date of next planned upgrade |
| documentation        | varchar(255) | Link to documentation |
| security_need_c      | int          | Confidentiality |
| security_need_i      | int          | Integrity |
| security_need_a      | int          | Availability |
| security_need_t      | int          | Traceability |
| ecurity_need_auth    | int          | authentication |
| rto                  | int          | Recovery Time Objective |
| rpo                  | int          | Recovery Point Objective |
| vendor               | varchar(255) | Application's vendor |
| product              | varchar(255) | Product name |
| version              | varchar(255) | Version of the application |
| patching_frequency   | int          | Patching frequency |
| entities             | List int [,] | IDs list of related entities         |
| processes            | List int [,] | IDs list of related processes        |
| services             | List int [,] | IDs list of related services         |
| databases            | List int [,] | IDs list of related dataabses        |
| logical_servers      | List int [,] | IDs list of related logical_servers  |
| activities           | List int [,] | IDs list of related activities       |
| containers           | List int [,] | IDs list of related containers       |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

RTO : *Recovery Time Objective*  
RPO : *Recovery Point Objective*  

The "patching_frequency" and "next_update" fields are not used at the moment and are therefore missing from the application.  

In the app, the need for authentication is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu. 

'export of reference data model:

- user entities (*entities* field),
- supported processes,
- supported activities,
- application services,
- databases,
- workstations,
- logical servers,
- logical safety equipment,
- administrators (Users object of the administration view),
- and security measures

attached to an application.

In the application, a user entity can be attached to an application from an application object.  
A process can be attached to an application from these two objects.  
An activity can be attached to an application from these two objects.  


An application service can be attached to an application from these two objects.  
A database can be attached to an application from these two objects.  
A workstation can be attached to an application from a workstation object.  


A logical server can be attached to an application from these two objects.  
Logical security equipment can be attached to an application from these two objects.  
An administrator can be attached to an application from an application object.  


A security measure can be attached to an application from the "Assign a security measure" button.  
This button is present in the GDPR view and visible in the list of Security Measures objects.  


In the application, a container can be attached to an application from these two objects.  
In the application, the *major events* field is managed in a separate table.


#### Major events

Major events are the main events experienced by an application during its operation.  
Major events are only accessible through application objects.  

They are neither importable nor exportable through the graphics tool.  

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*m_application_events*</span>      | N/A    |

| Field            | Type         | Description                                         |
|:-----------------|:-------------|:----------------------------------------------------|
| id               | int unsigned | auto_increment                                      |
| user_id          | int unsigned | Mercator user id who has register the event            |
| m_application_id | varchar(255) | Reference to the id of the application that suffered the event |
| message          | longtext     | Description of the event                            |
| created_at       | timestamp    | Date of creation                                    |
| updated_at       | timestamp    | Date of update                                      |


#### Applications services

An application service is a specific service provided to a user to perform specific tasks related to their role in the organisation.

Eg. an application service could be a Cloud service or platform.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*application_services*</span>      | `/api/application-services`    |

| Field           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Name of the application service |
| description     | longtext     | Description of the application service |
| exposition      | varchar(255) | Exposure of the application service |
| modules         | List int [,] | Liens vers les applications-modules |
| applications    | List int [,] | Liens vers les applications        |
| created_at      | timestamp    | Date of creation |
| updated_at      | timestamp    | Date of update |
| deleted_at      | timestamp    | Date of deletion |

The export of the data model references the applications and application modules attached to an application service. 

In the application, an application can be attached to an application service from these two objects.  
In the application, an application module can be attached to an application service from these two objects.  

There are two fields with the same information in the data model export, *servicesApplications* and 
*applications*.  
The connection with application objects is made via the *applications* field.

#### Application modules

An application module is a component of an application characterized by functional coherence and technological homogeneity.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*application_modules*</span>      | `/api/application-modules`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the application module |
| description          | longtext     | Description of the application module |
| application_services | List int [,] | IDs list of related to applications-services |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

The export of the data model references the application services attached to an application module.  
In the application, an application service can be attached to an application module from these two objects.

#### Databases

A collection of databases managed within the organisation.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*databases*</span>      | `/api/databases`    |

| Field               | Type         | Description      |
|:-------------------|:-------------|:-----------------|
| id                 | int unsigned | auto_increment |
| name               | varchar(255) | Name of the database |
| description        | longtext     | Description of the database |
| type               | varchar(255) | Technology used |
| entity_resp_id     | int unsigned | Entity responsible  |
| responsible        | varchar(255) | Responsible entity |
| icon_id            | int unsigned | Reference to a specific image            |
| security_need_c    | int          | Confidentiality |
| security_need_i    | int          | Integrity |
| security_need_a    | int          | Availability |
| security_need_t    | int          | Traceability |
| security_need_auth | int          | authentication                              |
| external           | varchar(255) | External |
| created_at         | timestamp    | Date of creation |
| updated_at         | timestamp    | Date of update |
| deleted_at         | timestamp    | Date of deletion |

In the app, the need for authentication is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu. 

Exporting the data model references the specific image of a database.  
In the application, a specific image can be attached to a database from a database object.  

The data model export references user entities (*entities* field), applications, information,
logical servers and containers attached to a database.  
In the application, a user entity can be attached to a database from a database object.  
In the application, information can be attached to a database from a database object.  
In the application, an application can be attached to a database from these two objects.  
In the application, a logical server can be attached to a database from these two objects.  
In the application, a container can be attached to a database from these two objects.


#### Flows

A flow is an exchange of information between a sender and a receiver (application, application service, application module or database).

A flow represents an exchange of information between two elements of the information system. It is important to avoid representing all firewall filtering rules in terms of flows.

For example, DNS or NTP requests should not be represented as flows.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*fluxes*</span>      | `/api/fluxes`    |

| Field                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Name of the flow |
| attributes            | varchar(255) | Attributs (tags) du flux                  |
| description           | longtext     | Description of the flow |
| ***device***_source_id    | int unsigned | Link to the source_id                 |
| ***device***_dest_id      | int unsigned | Lnk to the destination id          |
| crypted               | tinyint(1)   | The flow is encrypted (1=yes, O=no) |
| bidirectional         | tinyint(1)   | The flow is bydirectional (1=yes, O=no)|
| created_at            | timestamp    | Date of creation |
| updated_at            | timestamp    | Date of update |
| deleted_at            | timestamp    | Date of deletion |

The  ***device***_ for source_id or dest_id might be: :

| Actif (*device*)                | Source | Destination |
|:--------------------------------|:------:|:-----------:|
| Application                     | ✅     | ✅          |
| Application service             | ✅     | ✅          |
| Application module              | ✅     | ✅          |
| Database                        | ✅     | ✅          |


---

### Administration

The administration view lists the administration of resources, directories and privilege levels of information system users.

[<img src="/mercator/images/administration.png" width="400">](/mercator/images/administration.png)

Having directories and centralized user access rights is strongly recommended for operators of vital importance (OVI).

#### Administration areas

An administration zone is a set of resources (people, data, equipment) under the responsibility of one (or more) administrator(s).

An administration zone is made up of Active Directory (AD) directory services and forests, or LDAP trees.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*zone_admins*</span>      | `/api/zone-admins`    |

| Field                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Name of the area |
| description           | longtext     | Description area |
| created_at            | timestamp    | Date of creation |
| updated_at            | timestamp    | Date of update |
| deleted_at            | timestamp    | Date of deletion |

#### Administration directory services

An administration directory service is an application that collects data on a company's users or IT equipment, enabling them to be administered.

It can be an inventory tool used to manage changes or tickets, or a mapping tool such as Mercator.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*annuaires*</span>      | `/api/annuaires`    |

| Field                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Name of the directory |
| description           | longtext     | Description  of the directory |
| solution              | varchar(255) | Techinical solution |
| zone_admin_id         | int unsigned | Reference to administration area|
| created_at            | timestamp    | Date of creation |
| updated_at            | timestamp    | Date of update |
| deleted_at            | timestamp    | Date of deletion |

#### Active Directory forests / LDAP tree structure

These objects represent an organized grouping of Active Directory domains or LDAP trees.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*forest_ads*</span>      | `/api/forest-ads`    |

| Field                 | Type         | Description      |
|:----------------------|:-------------|:-----------------|
| id                    | int unsigned | auto_increment |
| name                  | varchar(255) | Name of Active Directory or LDAP forests |
| description           | longtext     | Description of Active Directory or LDAP forests |
| zone_admin_id         | int unsigned | Reference to Administration zone |
| created_at            | timestamp    | Date of creation |
| updated_at            | timestamp    | Date of update |
| deleted_at            | timestamp    | Date of deletion |

#### Active Directory domains / LDAP

Active Directory domains / LDAP are company IT directories. They contains user and computer accounts, contacts, objects rights and a part of IT policies (e.g. Group Policy Object - GPO).

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*domaines_ads*</span>      | `/api/domaines-ads`    |

| Field                  | Type         | Description                              |
|:-----------------------|:-------------|:-----------------------------------------|
| id                     | int unsigned | auto_increment                           |
| name                   | varchar(255) | AD Domain / LDAP name                    |
| description            | longtext     | Domain description                       |
| domain_ctrl_cnt        | int signed   | Number of domain controllers             |
| user_count             | int signed   | Number of domain users                   |
| machine_count          | int signed   | Number of domain computers               |
| relation_inter_domaine | varchar(255) | Cross domains relationships description  |
| created_at             | timestamp    | Date of creation                         |
| updated_at             | timestamp    | Date of update                           |
| deleted_at             | timestamp    | Date of deletion                         |

The data model export lists AD forests / LDAP trees linked with an AD domain / LDAP.

In the app, an AD forest / LDAP tree can be linked with an AD domain / LDAP from these two objects.
A logical server can be linked with an AD domain / LDAP from these two objects.

#### Users

Users are user accounts with privileged rights on IT systems.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*admin_users*</span>      | `/api/admin-users`    |

| Field                 | Type         | Description                                    |
|:----------------------|:-------------|:-----------------------------------------------|
| id                    | int unsigned | auto_increment                                 |
| user_id               | varchar(255) | Unique ID number / other of an user            | 
| firstname             | varchar(255) | User's first name                              |
| lastname              | varchar(255) | User's last name                               |
| type                  | varchar(255) | User type                                      |
| attributes            | varchar(255) | User's tags                                    |
| icon_id               | int unsigned | Link to a specific icon                        |
| description           | longtext     | DUser description                              |
| domain_id             | int unsigned | Link to the users's domain                     |
| created_at            | timestamp    | Date of creation                               |
| updated_at            | timestamp    | Date of update                                 |
| deleted_at            | timestamp    | Date of deletion                               |

The data model export lists applications which an user is administrator.

In the app, an user can be defined as administrator of an application from an application object.

---

### Logical infrastructure

The logical infrastructure view corresponds to the logical distribution of the network.

[<img src="/mercator/images/logical.png" width="400">](/mercator/images/logical.png)

It illustrates the partitioning of networks and the logical links between them. It also lists the network equipment that handles the traffic.

#### Networks

Networks are a set of logically interconnected devices that exchange information.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*networks*</span>      | `/api/networks`    |

| Field           | Type         | Description      |
|:----------------|:-------------|:-----------------|
| id              | int unsigned | auto_increment |
| name            | varchar(255) | Name of network |
| description     | longtext     | Description of network |
| protocol_type   | varchar(255) | Used protocols |
| responsible     | varchar(255) | Operation manager |
| responsible_sec | varchar(255) | Security manager |
| security_need_c | int          | Confidentiality |
| security_need_i | int          | Integrity |
| security_need_a | int          | Availability |
| security_need_t | int          | Traceability |
| security_need_auth | int       | Authentication                  |
| created_at      | timestamp    | Date of creation |
| updated_at      | timestamp    | Date of update |
| deleted_at      | timestamp    | Date of deletion |

In the app, the need for authentication is hidden by default. It is mandatory in the case 
from an entity subject to EU Directive 2022/2554 (DORA).  
It is activated from the Configuration > Settings menu. 

#### Subnetworks

Subnetworks are a logical subdivision of a larger network.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*subnetworks*</span>      | `/api/subnetworks`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the subnet |
| description          | longtext     | Description of the subnet |
| network_id           | int unsigned | ID related to parent network |
| subnetwork_id        | int unsigned | ID related to child network  |
| connected_subnets_id | int unsigned | Network to which this subnet belongs |
| address              | varchar(255) | Addresses range |
| default_gateway      | varchar(255) | Default gateway |
| gateway_id           | int unsigned | Gateway |
| vlan_id              | int unsigned | Associated VLAN |
| ip_allocation_type   | varchar(255) | Type of IP address allocation |
| zone                 | varchar(255) | Name of the firewall zone |
| dmz                  | varchar(255) | DMZ (Yes/No) |
| wifi                 | varchar(255) | Wireless |
| responsible_exp      | varchar(255) | Operations manager |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

The "connected_subnets_id" field is used to define a foreign key. However, this one does not seem to be used.

#### External input gateways

Gateways are components used to connect a local network to the outside world.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*gateways*</span>      | `/api/gateways`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the gateway |
| description          | longtext     | Description of the gateway |
| ip                   | varchar(255) | IP address of the gateway |
| authentification     | varchar(255) | Authentication modes |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


In the application, a subnet can be attached to a gateway from these objects.

#### Connected external entities

Connected external entities represent external entities connected to the network.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*external_connected_entities*</span>      | `/api/external-connected-entities`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of entity/company |
| type                 | varchar(255) | Connexion type                    |
| description          | longtext     | Description of the entity/company |
| entity_id            | int unsigned | Reference to the connected external                       |
| network_id           | int unsigned | Reference to the internal neetwork connected to the entity |
| contacts             | varchar(255) | Contacts within the entity/company|
| src                  | varchar(255) | IP Adress(es) of entity connection                          |
| src_desc             | varchar(255) | Description de Entity source connection                   |
| dst                  | varchar(255) | IP Adress(es) or IP Range of the destination of the connection |
| dst_desc             | varchar(255) | Description of the Entity connection |
| security             | text         | Security requirements of the system                         |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

Exporting the data model references subnets and documents attached to a connected external entity.  
In the application, a subnet can be attached to a connected external entity from a connected external entity object.  
A document can be attached to a connected external entity from a connected external entity object.

#### Network switches

Network switches are the components that manage connections between the various servers on a network.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*network_switches*</span>      | `/api/network-switches`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the switch |
| description          | longtext     | Description of the switch |
| ip                   | varchar(255) | IP address of the switch |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### Logical routers

Logical routers are logical components that manage connections between different networks.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*routers*</span>      | `/api/routers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the router |
| type                 | varchar(255) | router type         |
| ip_addresses         | text         | IP Adress(es) of the router |
| description          | longtext     | Description of the router |
| rules                | longtext     | Filtering rules |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


Exporting the data model references physical routers attached to a logical router.  
In the application, a physical router can be attached to a logical router from these two objects.

#### Security equipments

Security devices are components used for network supervision, incident detection, equipment protection and information system security.

Security equipment includes intrusion detection systems (IDS: Intrusion Detection System), intrusion prevention systems (IPS: Intrusion Prevention System) and equipment monitoring systems.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*security_devices*</span>      | `/api/security-devices`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the device |
| type                 | varchar(255) | Eqquipement type                    |
| attributes           | varchar(255) | Equipment attributes                |
| icon_id              | int unsigned | Reference to a specific image            |
| description          | longtext     | Description of the device |
| address_ip           | varchar(255) | IP Adress(es) of the equipment          |
| vendor               | varchar(255) | Vendor / editor for CPE search    |
| product              | varchar(255) | Editor Product for CPE search  |
| version              | varchar(255) | Version of a product for CPE search |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

The "vendor", "product" and "version" fields are not used at the moment and are therefore 
missing from the app.  
Data model export references physical security equipment and applications 
attached to logical safety equipment.  
In the application, physical safety equipment can be attached to safety equipment 
logic from these two objects.  
An application can be attached to logical security equipment from these two objects.

#### DHCP servers

DHCP servers are physical or virtual devices that manage a network's IP addresses.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*dhcp_servers*</span>      | `/api/dhcp-servers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the DHCP server |
| description          | longtext     | Description of the DHCP server |
| address_ip           | varchar(255) | Adresse(s) IP of the server |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### DNS servers

Domain Name System (DNS) servers are physical or virtual devices that convert a domain name into an IP address.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*dnsservers*</span>      | `/api/dnsservers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the DNS server |
| description          | longtext     | Description of the DNS server |
| address_ip           | varchar(255) | Adresse(s) IP of the server |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### Clusters

Clusters are a set of logical servers hosted on one or more physical servers.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*clusters*</span>      | `/api/clusters`    |

| Field                | Type         | Description                     |
|:---------------------|:-------------|:--------------------------------|
| id                   | int unsigned | auto_increment                  |
| name                 | varchar(255) | Name of the cluster             |
| type                 | varchar(255) | Type of cluster                 |
| icon_id              | int unsigned | Reference to a specific image   |
| description          | longtext     | Description of the cluster      |
| attributes           | varchar(255) | Cluster attributes              |
| address_ip           | varchar(255) | IP address of the cluster       |
| created_at           | timestamp    | Date of creation                |
| updated_at           | timestamp    | Date of update                  |
| deleted_at           | timestamp    | Date of deletionn               |


The data model export references logical routers, logical and physical servers attached to a cluster.  
In the application, a logical router can be attached to a cluster from a cluster object.  
A logical server can be attached to a cluster from these two objects.  
A physical server can be attached to a cluster from these two objects.

---
#### Logical servers

Logical servers are a logical breakdown of a physical server. If the physical server is not virtualized, it is split into a single logical server.


| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*logical_servers*</span>      | `/api/logical-servers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the logical server |
| description          | longtext     | Description of the logical server |
| icon_id              | int unsigned | Reference to a specific image   |
| type                 | varchar(255) | Server type (appli, DB, ...)                 |
| active               | tinyint(1)   | Server status active (1) or obsolete (0)          |
| attributes           | varchar(255) | Server atributes (tags)            |
| net_services         | varchar(255) | Active network services |
| configuration        | longtext     | Server configuration |
| operating_system     | varchar(255) | Operative system |
| address_ip           | varchar(255) | IP address |
| cpu                  | varchar(255) | Number of CPU |
| memory               | varchar(255) | Quantity of RAM |
| environment          | varchar(255) | Environnement (prod, dev, test, ...) |
| disk                 | int          | Storage allocated |
| disk_used            | int          | Stroage used                           |
| domain_id            | int unsigned | Administartion domain for this server   |
| install_date         | datetime     | Date of server installation |
| update_date          | datetime     | Date of server upgrade |
| patching_frequency   | int signed   | Recurrence of update                   |
| next_update          | date         | NNewt planned update date              |
| databases            | List int [,] | IDs List of related database(s)        |
| cluster_id           | List int [,] | IDs List of related cluster(s)         |
| physical_servers     | List int [,] | IDs List of related physical_servers   |
| applications         | List int [,] | IDs List of related applications       |
| containers           | List int [,] | IDs List of related containers         |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

The "patching_frequency" and "next_update" fields are not used at the moment and are therefore absent from the application.  
Exporting the reference data model: 

- applications,
- physical servers,
- the documents,
- databases,
- clusters,
- certificates,
- and the containers

attached to a logical server.  

in the application, an application can be attached to a logical server from these two objects.  
A database can be attached to a logical server from these two objects.  
A cluster can be attached to a logical server from these two objects.  
A physical server can be attached to a logical server from these two objects.  
A certificate can be attached to a logical server from a certificate object.  
A container can be attached to a logical server from a container object.

The "documents" field does not appear to be used in a logical server's data model.

#### Containers

Containers are part of virtualization systems. They can operate in clusters or in isolation.
on internal or external logical servers (cloud).

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*containers*</span>      | `/api/containers`    |

| Field                | Type         | Description                                  |
|:---------------------|:-------------|:---------------------------------------------|
| id                   | int unsigned | auto_increment                               |
| name                 | varchar(255) | Container name                               |
| description          | longtext     | Container description                        |
| type                 | varchar(255) | Type of the container (docker, kubernetes, ...) |
| icon_id              | int unsigned | Reference to a specific image                |
| created_at           | timestamp    | Date of creation                             |
| updated_at           | timestamp    | Date of update                               |
| deleted_at           | timestamp    | Date of deletion                             |

Data model export references applications, databases and servers
logics attached to a container.  
In the application, an application can be attached to a container from these two objects.  
A database can be attached to a container from these two objects.  
A logical server can be attached to a container from a containing object.

#### Logical flows

Logical flows describe relationships at layers 3 and 4 of the OSI model.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*logical_flows*</span>      | `/api/logical-flows`    |

General principle :

| Field                | Type         | Description                                   |
|:---------------------|:-------------|:----------------------------------------------|
| id                   | int unsigned | auto_increment                                |
| name                 | varchar(255) | Name of logical flow                          |
| description          | text         | Description of logical flow                   |
| chain                | varchar(255) | INPUT / OUTPUT / FORWARD                      |
| interface            | varchar(255) | Network interface linked                      |
| router_id            | int unsigned | Router used for this logical flow             |
| priority             | int signed   | Priority of the rule / flow                   |
| action               | varchar(255) | Rule action (Grant, refuse, restrict, ...)    |
| protocol             | varchar(255) | Protocol(s) used by this flow                 |
| source_ip_range      | varchar(255) | Source IP range                               |
| dest_ip_range        | varchar(255) | Destination IP range                          |
| source_port          | varchar(255) | Logical port of the source                    |
| dest_port            | varchar(255) | Logical port of the destination               |
| *device*_source_id   | int unsigned | Actif source                                  |
| *device*_dest_id     | int unsigned | Actif de destination                          |
| users                | varchar(255) | Users concerned by this flow rule             |
| schedule             | varchar(255) | Period of activity of the rule/flow           |
| created_at           | timestamp    | Date of creation                              |
| updated_at           | timestamp    | Date of update                                |
| deleted_at           | timestamp    | Date of deletion                              |

Les actifs sources et destination peuvent être :

| Asset (*device*)                | Source  | Destination |
|:--------------------------------|:-------:|:-----------:|
| Peripherals                     | ✅      | ✅          |
| Physical Security Equipement    | ✅      | ✅          |
| Physical server                 | ✅      | ✅          |
| Storage infrastructure          | ✅      | ✅          |
| Workstations                    | ✅      | ✅          |
| Logical security equipment      | ✅      | ✅          |
| Logical server                  | ✅      | ✅          |
| Subnetworks                     | ✅      | ✅          |


#### Certificates

Electronic certificates are used to identify and authenticate services and individuals, as well as to encrypt exchanges.  

Certificates are SSL keys, HTTPS certificates, etc. They are associated with logical servers or applications.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*certificates*</span>      | `/api/certificates`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the certificate |
| description          | longtext     | Description of the certificate |
| type                 | varchar(255) | Type of certificate (SSL, HTTPS ...) |
| start_validity       | date         | Date start of validity |
| end_validity         | date         | Date end of validity |
| status               | int          | State of certificate (RFC 6960) |
| last_notification    | datetime     | Last notification submited |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

* Note:
    * status = 0 : "Good"
    * status = 1 : "Revoked
    * status = 2 : "Unknown

The "last_notification" field is not used at the moment and is therefore missing from the application.  
Exporting the data model references logical applications and servers attached to a certificate.  
In the application, a certificate can be attached to a logical application or server from a certificate object.


#### VLANs

A VLAN (Virtual Local Area Network) or virtual local area network (LAN) enables equipment to be logically grouped together, free from physical constraints.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*vlans*</span>      | `/api/vlans`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of VLAN |
| description          | longtext     | Description of VLAN |
| vlan_id              | int signed   | VLAN  number     |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


The data model export references physical routers and logical switches ("network switches")
attached to a VLAN.
In the application, a VLAN can be attached to a physical router from a physical router object.  
In the application, a VLAN can be attached to a subnet or a logic switch from these two objects.

---

### Physical infrastructure

The physical infrastructure view describes the physical equipment that makes up or is used by the information system.

[<img src="/mercator/images/physical.png" width="700">](/mercator/images/physical.png)

This view corresponds to the geographical distribution of network equipment within the various sites.

#### Sites

Sites are geographical locations that bring together a group of people and/or resources.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*sites*</span>     | `/api/sites`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the site |
| icon_id           | int unsigned | Reference to a specific image            |
| description          | longtext     | Description of the site |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### Buildings / Rooms

Buildings or rooms represent the location of people or resources within a site.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*buildings*</span>      | `/api/buildings`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of building |
| icon_id              | int unsigned | Reference to a specific image            |
| type                 | varchar(255) | Type of Room/Building               |
| attributes           | varchar(255) | Attributes of the building / room                        |
| description          | longtext     | Description of the building |
| site_id              | int unsigned | Reference to the site |
| building_id          | int unsigned | Reference to a building / room |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

In the application, a building /room or a site can be attached to a building /room from a building object /
room.

#### Racks

Racks are technical cabinets housing computer network or telephony equipment.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*bays*</span>      | `/api/bays`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of the rack |
| description          | longtext     | Description of the rack |
| room_id              | int unsigned | Reference to building / room |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### Physical servers

Physical servers are physical machines running a set of IT services.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*physical_servers*</span>      | `/api/physical-servers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of physical server |
| icon_id              | int unsigned | Reference to a specific image            |
| description          | longtext     | Description of physical server |
| type                 | varchar(255) | Type / model of server |
| cpu                  | varchar(255) | Number of CPU |
| memory               | varchar(255) | Quantity of RAM |
| disk                 | int          | Storage allocated |
| disk_used            | int          | Stroage used                           |
| configuration        | longtext     | Server configuration |
| operating_system     | varchar(255) | Operative system |
| address_ip           | varchar(255) | IP address |
| install_date         | datetime     | Date of server installation |
| update_date          | datetime     | Date of server upgrade |
| responsible          | varchar(255) | Person/Team responsible for the server |
| site_id              | int unsigned | Reference to the site |
| building_id          | int unsigned | Reference to the building / room |
| bay_id               | int unsigned | Reference to the rack |
| clusters             | List int [,] | IDs list of related cluster(s)         |
| logical_servers      | List int [,] | IDs List of related logical(s) servers(s) |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


Data model export references applications, (logical) clusters, and servers 
logics attached to a physical server.  
In the application, an application can be attached to a physical server from a physical server object.  
A cluster can be attached to a physical server from these two types of objects.  
A logical server can be attached to a physical server from these two types of objects.

For readability, fields defined in the data model but unused for the 
moment in application for table *physical_servers* have been grouped into the following table:


| Field                | Type         | Description                             |
|:---------------------|:-------------|:----------------------------------------|
| vendor               | varchar(255) | Vendor / editor for CPE search        |
| product              | varchar(255) | Product of an editor for CPE search   |
| version              | varchar(255) | Version of a product for CPE search   |
| patching_group       | varchar(255) | Group for upgrade                       |
| patching_frequency   | varchar(255) | Frequency of upgrade                    |
| next_update          | date         | Date of next upgrade                    |
| physical_swicth_id   | int unsigned | ID of related Physical switch           |


#### Workstations

Workstations are physical machines that enable a user to access the information system.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*workstations*</span>      | `/api/workstations`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of workstation |
| icon_id              | int unsigned | Reference to a specific image            |
| description          | longtext     | Description of workstation |
| status               | varchar(255) | Status of the workstation (lifecycle incident)   |
| type                 | varchar(255) | Type / model of workstation |
| entity_id            | int unsigned | ID of the related entity                   |
| domain_id            | int unsigned | ID of the related users identification domain      |
| user_id              | int unsigned | ID of the reference domain user of the workstation |
| other_user           | int unsigned | Workstation users , if outside of domain               |
| manufacturer         | varchar(255) | Workstation manufacturer                                        |
| model                | varchar(255) | Workstation model                                               |
| serial_number        | varchar(255) | Workstation serial number                                       |
| cpu                  | varchar(255) | Workstation CPU                                          |
| memory               | varchar(255) | Workstation RAM                                     |
| disk                 | int signed   | Disk size of the workstation                           |
| operating_system     | varchar(255) | Workstation Operating system                                  |
| network_id           | int unsigned | ID of the workstation related network               |
| address_ip           | varchar(255) | IP Adresse(s) of the workstation                       |
| mac_address          | varchar(255) | MAC Adresse(s) MAC of the workstation                           |
| network_port_type    | varchar(255) | Network Connector type (RJ45, USB, SFP,...)              |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

Exporting the data model references applications attached to a workstation.  
In the application, an application can be attached to a workstation from a workstation object.

For readability reasons, fields defined in the data model but unused at the moment 
in the application for the table *workstations* have been grouped in the following table:

| Field                | Type         | Description                             |
|:---------------------|:-------------|:----------------------------------------|
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| warranty             | varchar(255) | Guarantee contract                      |
| warranty_start_date  | date         | Guarantee start date                    |
| warranty_end_date    | date         | Guaranty end date                       |
| warranty_period      | date         | Guarantee period                        |
| purchase_date        | date         | Purchase date                           |
| fin_value            | decimal      | Financial value.                        | 
| last_inventory_date  | date         | Date of last inventory                  |
| update_source        | varchar(255) | Source of inventory update              |
| agent_version        | varchar(255) | Inventory agent verrsion                |
| physical_swicth_id   | int unsigned | ID related to the physical switch       |



#### Storage infrastructures

Storage infrastructures are physical media or data storage networks: network attached storage (NAS), storage area network (SAN), hard disk...

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*storage_devices*</span>      | `/api/storage-devices`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of storage infrastructure |
| description          | longtext     | Description of the storage infrastructure |
| site_id              | int unsigned | Reference to the site |
| building_id          | int unsigned | Reference to building / room |
| bay_id               | int unsigned | Reference to the rack |
| address_ip           | varchar(255) | IP address of storage infrastructure |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


The "vendor", "product" and "version" fields are not used at the moment and are therefore missing from the application.

#### Peripherals

Peripherals are physical components connected to a workstation to add new functions (e.g. keyboard, mouse, printer, scanner, etc.).

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*peripherals*</span>      | `/api/peripherals`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of peripheral |
| description          | longtext     | Description of peripheral |
| type                 | varchar(255) | Type / model of peripheral |
| icon_id              | int unsigned | Reference to a specific image            |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| bay_id               | int unsigned | Reference to rack |
| responsible          | varchar(255) | Internal responsible of this equipment  |
| address_ip           | varchar(255) | IP Adress of the equipment                         |
| domain               | varchar(255) | General domain where it fits to (IT, OT, IOT, etc.) |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| provider_id          | int unsigned | ID related to the provider of this peripheral      |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

Data model export references applications using a device.  
In the application, a device can be attached to an application from a peripheral object.


#### Phones

Fixed and mobile phones belonging to the organization.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*phones*</span>      | `/api/phones`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of telephone |
| description          | longtext     | Description of telephone |
| type                 | varchar(255) | Type / model of telephone |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| physical_switch_id   | int unsigned | ID related to the physical switch  |
| address_ip           | varchar(255) | IP Adress of the phone               |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


The "vendor", "product" and "version" fields are not used at the moment and are therefore missing from the application.



#### Physical switches

Physical switches are physical components that manage connections between different servers within a network.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*physical_switches*</span>      | `/api/physical-switches`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of physical switch |
| description          | longtext     | Description of physical switch |
| type                 | varchar(255) | Type / model of physical switch |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| bay_id               | int unsigned | Reference to rack |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


The "vendor", "product" and "version" fields are not used at the moment and are therefore missing from the application.

'data model export references logical switches attached to a physical switch.  
In the application, a physical switch can be attached to a logical switch (denoted as "Network Switches")
from these two types of objects.


#### Physical routers

Physical routers are physical components that manage connections between different networks.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*physical_routers*</span>      | `/api/physical_routers`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of physical router |
| description          | longtext     | Description of physical router |
| type                 | varchar(255) | Type / model of physical router |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| bay_id               | int unsigned | Reference to rack |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

The "vendor", "product" and "version" fields are not used at the moment and are therefore missing from the application.

Exporting the data model references logical routers and VLANs attached to a physical router.  
In the application, a physical router can be attached to a logical router (denoted as "Routers" from these two types of objects.  
A VLAN can be attached to a physical router from a physical router object.

#### WiFi terminals

WiFi hotspots are hardware devices that enable access to the WiFi wireless network.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*wifi_terminals*</span>      | `/api/wifi-terminals`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of WiFi hotspot |
| description          | longtext     | Description of WiFi hotspot |
| type                 | varchar(255) | Type / model of WiFi hotspot |
| site_id              | int unsigned | Reference to site |
| building_id          | int unsigned | Reference to building / room |
| address_ip           | varchar(255) | IP Adress of the wifi terminal          |
| vendor               | varchar(255) | Vendor / editor for CPE search          |
| product              | varchar(255) | Product of an editor for CPE search     |
| version              | varchar(255) | Version of a product for CPE search     |
| created_at           | timestamp    | Date of creation |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### Physical security equipments

Physical security equipment includes components for network supervision, incident detection, equipment protection and information system security.

Physical security equipment includes temperature sensors, cameras, security doors, etc.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*physical_security_devices*</span>      | `/api/physical-security-devices`    |

| Field                | Type         | Description                        |
|:---------------------|:-------------|:-----------------------------------|
| id                   | int unsigned | auto_increment                     |
| name                 | varchar(255) | Name of security equipment         |
| icon_id              | int unsigned | Reference to a specific image      |
| description          | longtext     | Description of security equipment  |
| type                 | varchar(255) | Type / model of security equipment |
| site_id              | int unsigned | Reference to site                  |
| building_id          | int unsigned | Reference to building / room       |
| bay_id               | int unsigned | Reference to rack                  |
| address_ip           | varchar(255) | IP Adress                          |
| created_at           | timestamp    | Date of creation                   |
| updated_at           | timestamp    | Date of update                     |
| deleted_at           | timestamp    | Date of deletion                   |


#### Physical links

Physical links represent the cables between physical or logical objects.  
Logical objects can have physical links, for example within a virtualized network.  
A physical link is different from a logical flow. A physical link describes a relationship at layers 1 or 2 of the OSI model.  
A logic flow describes a relationship at layers 3 and 4 of the OSI model.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*physical_links*</span>      | `/api/physical-links`    |

| Field           | Type         | Description                             |
|:----------------|:-------------|:----------------------------------------|
| id              | int unsigned | auto_increment                          |
| *device*_src_id | int unsigned | Source                                  |
| src_id          | varchar(255) | Physical port of the source             |
| *device*_dst_id | int unsigned | Destination                             |
| dst_port        | varchar(255) | Physical port of the destination        |
| created_at      | timestamp    | Date of creation                        |
| updated_at      | timestamp    | Date of update                          |
| deleted_at      | timestamp    | Date of deletion                        |

Les ***devices***** sources or destination might be :

| Assets ***(devices***)           | Source  | Destination |
|:---------------------------------|:-------:|:-----------:|
| Peripheral                       | ✅      | ✅          |
| Phonehone                        | ✅      | ✅          |
| Physical Router                  | ✅      | ✅          |
| Physical Security equipment      | ✅      | ✅          |
| Physical server                  | ✅      | ✅          |
| Physical switch                  | ✅      | ✅          |
| Storage infrastructure           | ✅      | ✅          |
| Wifi terminal                    | ✅      | ✅          |
| Workstation                      | ✅      | ✅          |
| Logical server                   | ✅      | ✅          |
| Logical switch                   | ✅      | ✅          |
| Logical router                   | ✅      | ✅          |


#### WANs

WANs (Wide Area Networks) are computer networks linking equipment over long distances. They generally interconnect MANs or LANs.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*wans*</span>      | `/api/wans`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of WAN |
| created_at           | timestamp    | Date of creation |
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |


#### MANs

MANs (Middle Area Networks) are computer networks linking equipment over medium-sized distances. They generally interconnect LANs.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*mans*</span>      | `/api/mans`    |

| Field                | Type         | Description      |
|:---------------------|:-------------|:-----------------|
| id                   | int unsigned | auto_increment |
| name                 | varchar(255) | Name of MAN |
| created_at           | timestamp    | Date of creation|
| updated_at           | timestamp    | Date of update |
| deleted_at           | timestamp    | Date of deletion |

#### LANs

LANs (Local Area Networks) are computer networks linking equipment over a small geographical area.

| Table            | api              |
|:-----------------|:-----------------|
|   <span style="color: blue;">*lans*</span>      | `/api/lans`    |

| Field                | Type         | Description        |
|:---------------------|:-------------|:-------------------|
| id                   | int unsigned | auto_increment     |
| name                 | varchar(255) | Name of LAN        |
| description          | longtext     | Description of LAN |
| created_at           | timestamp    | Date of creation   |
| updated_at           | timestamp    | Date of update     |
| deleted_at           | timestamp    | Date of deletion   |
