## Reports

### Mapping report

The cartography report contains all the objects making up the cartography and the links between them.

[<img src="/mercator/images/report.png" width="600">](/mercator/images/report.png)

This is a Word document containing information on all the objects in the selected views at the desired level of granularity.

### Lists

Mercator allows you to extract a range of information in the form of lists:

#### Supported entities and applications

Generates a list of information system entities and their supported applications.

This list can be used to draw up an inventory of the entities responsible for the various information system applications, along with their managers and contact points.

#### Applications by application group

List of applications by application group

This list provides an overview of all the applications in the information system, classified by application group. This list can be used to monitor vulnerabilities in information system applications.

#### Logical servers

List of logical servers by applications and managers.

This list can be used to identify logical server managers and the applications they serve.

#### Security needs analysis

List of security requirements between macro-processes, processes, applications, databases and information.

This list is used to analyze the consistency of the information classification plan in terms of confidentiality, integrity, availability and traceability between processes, applications, databases and the information they contain.

#### Logical server configuration

Logical server configuration list.

This list is used to analyze the configuration of logical servers.

This list can be used to analyze the capacity required to run the information system, and to make year-on-year projections.

#### Physical infrastructure inventory

List of equipment by site/location

This list is used to review the physical inventory of information system equipment.

Every year, we recommend you print out this list and check that the equipment in the inventory corresponds to what is actually present in the corresponding premises, sites and bays.

### Audit

#### Maturity levels

This list contains details of the maturity levels for each type of information system mapping object.

#### Updates

This list is used to audit changes made to the mapping.

A map that never changes is not up-to-date. This report identifies the changes (creations, deletions and modifications) made to the cartography by object type over the course of a year.

### Compliance

The calculation of conformity levels for each mapping object is based on the presence of the following elements:

Object | Level | Required elements |
---------------------|--------|----------------------|
**Ecosystem** |
Entities | 1 | Description, security level, point of contact, at least one process |
Relationships | 1 | Description, type |
Relationships | 2 | Importance |
**Business** |
Macro-processes | 2 | Description, security levels |
Macro-processes | 3 | Responsible |
Process | 1 | Description, input-output, responsible |  
Process | 2 | Macro-processes, security requirements |
Activities | 2 | Description |
Operations | 1 | Description |
Operations | 2 | Actors |
Operations | 3 | Tasks |
Tasks | 3 | Description, task |
Actor | 2 | Contact, nature, type |
Information | 1 | Description, owner, administrator, storage |
Information | 2 | Security Requirements, Sensitivity |
**Information system** |
Application block | 2 | Description, responsible, applications |
Applications | 1 | Description, technology, type, users, process |
Applications | 2 | Responsible, security levels |
Application Services | 2 | Description, applications |
Application Modules | 2 | Description |
Database | 1 | Description, type, responsible entity, responsible person |
Database | 2 | Security requirements |
Flows | 1 | Description, source, destination |
**Administration** |
Zones | 1 | Description |
Directories | 1 | Description, solution, administration area |
Forest | 1 | Description, administration area |
Domains | 1 | Description, domain controller, number of users, number of machines, inter-domain relationship |
**Logical** |
Networks | 1 | Description, manager, security manager, security requirements |
Subnets | 1 | Description, address, default gateway, IP allocation type, DMZ, WiFi, VLAN |
Gateways | 1 | Description, authentication, IP range |
Connected external devices | 2 | Type, contacts |
Switches | 1 | Description |
Routers | 1 | Description |
Security devices | 1 | Description |
Clusters | 1 | Description, type |  
Logical servers | 1 | Description, OS, environment, IP address, applications, physical servers or clusters |
Certificates | 2 | Description, Type, validity start date, validity end date, applications or logical server |
**Physical infrastructure** |
Sites | 1 | Description |
Buildings | 1 | Description |
Bays | 1 | Description |
Physical servers | 1 | Description, configuration, site, building, responsible |
Workstations | 1 | Description, site, building |
Telephones | 1 | Description, site, building |
Storage | 1 | Description, site, building |
Peripherals | 1 | Description, site, building, responsible |
Physical switches | 1 | Description, type, site, building |
Physical routers | 1 | Description, type, site, building |
WiFi terminals | 1 | Description, type, site, building |
Physical security devices | 1 | Description, type, site, building |
LANs | 1 | Description |
VLans | 1 | Description |
