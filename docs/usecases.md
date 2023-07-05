## Use cases

Mercator can help you implement many of the security measures recommended by ISO 27002. By following the use cases described below, you can put in place a robust and effective information systems mapping process that can assist you in managing the information security risks facing your organization.


### Inventory of information and related assets

ISO 27002-5.9 recommends the creation and maintenance of an inventory of information and related assets, including their owners.

With Mercator, it is possible to document the information used, as well as the links between this information and the applications, processes and databases in which it is stored. 
The [applications by application group] report (/mercator/reports/) identifies the applications, databases, processes and macro-processes that use this information.

You can use this report to check with business managers that the information documented in Mercator is correct and up-to-date.


### Information classification

ISO 27002-5.12 recommends classifying information according to the organization's information security needs, based on confidentiality, integrity, availability and important stakeholder requirements.

With Mercator, it is possible to assign a security level to information in terms of confidentiality, integrity, availability and traceability, as well as to the databases, applications, processes and macro-processes that use this information.

To ensure that these requirements are in line with the organization's information security needs, it is possible to generate a [security requirements analysis] report (/mercator/reports/) that denormalizes the security requirements between the information, databases, applications, processes and macro-processes that use this information.

You can check whether security requirements are correctly documented for each line of this report.


### Planning and preparation for information security incident management

ISO 27002-5.24 recommends that the organization should plan and prepare for the management of information security incidents by defining, establishing and communicating the processes, functions and responsibilities involved in information security incident management.

In order to ensure a rapid, efficient, consistent and orderly response to information security incidents, it is important to be able to quickly identify the processes, suppliers and managers involved in an incident.

Mercator provides a complete inventory of this information, and with the [free text search tool](/mercator/application/) at the top left of the screen, it is possible to quickly find an asset, the process(es) concerned, the suppliers and those responsible.



### Location and protection of equipment

ISO 27002-7.8 recommends choosing a secure location for hardware and protecting it.

Mercator lets you specify the location of each piece of physical equipment (server, router, switch, etc.) and extract an inventory of equipment by location.

You can check with the [physical infrastructure inventory] report (/mercator/reports/) that this inventory is up to date and that there is no equipment that is either not in the inventory or is in the inventory but in the wrong location.

It is recommended to check at least annually that the physical equipment listed in the inventory is actually present in the premises where it is referenced, and that there is no equipment that is not present in the inventory.


### Sizing

ISO 27002-8.6 recommends that projections of future sizing requirements take into account new business and system requirements, as well as current and expected trends in the organization's information processing capacity.

With Mercator, it is possible to take a snapshot of consumed resources at regular intervals via the [logical server configuration](/mercator/reports) report. By creating a pivot table with a spreadsheet program, you can make projections on the evolution of the organization's information processing capacity requirements.

You can check that the organization's future information processing capacity requirements are covered.


### Technical vulnerability management

IS 27002-8.8 recommends obtaining information on the technical vulnerabilities of the information systems in use, assessing the organization's exposure to these vulnerabilities and taking appropriate action.

Mercator identifies vulnerabilities in the information system, based on application names and CPEs (Common Platform Enumeration) associated with applications and equipment. A report can be sent when a CVE is detected, identifying the application, its criticality and its exposure.

You can check that these detection alerts are analyzed, and that preventive or corrective measures are taken.


### Redundancy of information processing resources

ISO 27002-8.14 recommends that information processing resources be implemented with sufficient redundancy to meet availability requirements.

With Mercator, you can use the [applications by application group] report (/mercator/reports/) to identify critical applications according to their availability requirements, as well as the logical and physical servers on which these applications are installed.

You can check that these applications have sufficient redundancy to meet availability requirements.
A critical application should be located on more than one physical device and, depending on the deployment model, on more than one logical server.


### Network partitioning

ISO 27001-8.22 recommends that groups of information services, users and information systems be partitioned within the organization's networks.

With Mercator, you can generate a VLAN report that identifies for each VLAN the types of equipment, logical servers and applications that are in that VLAN.

You can check that each VLAN corresponds to a distinct group of information services, users or information systems.


### Change management

ISO 27002-8.32 recommends that changes to information processing resources and information systems should be subject to change management procedures.

With Mercator, it is possible to explore the information system map and identify the dependencies between the mapped objects. This analysis can be carried out using the explorer, the different views of the information system or directly with the [mapping report](/mercator/reports/).

You can identify that the impact of a change has been correctly identified by means of mapping.

In addition, when changes have been made, you can check that the mapping elements involved in the change have been documented using the [change tracking report](/mercator/reports/).

