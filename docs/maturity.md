## Maturity Levels

Maturity levels represent the completeness percentage of the cartography. It is an indicator of the remaining effort
required to achieve a complete mapping, in accordance with the recommendations of the
[ANSSI Information System Mapping Guide](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/).

This maturity is divided into three levels:

- **Level 1 minimum granularity**, which contains the information essential to the cartography;
- **Level 2 intermediate granularity**, which contains the information important to the cartography;
- **Level 3 fine granularity**, which contains the information useful for managing the security of the information
  system.

[<img src="/mercator/images/maturity.png" width="600" alt="Cartography maturity levels">](images/maturity.png)

### Meaning of Fields Marked with a Hash (#)

Some field labels are followed by an **#** symbol in orange. These markers indicate that the field
contributes to the calculation of the cartography maturity level, according to the relevant level:

| Marker | Meaning                                                           |
|--------|-------------------------------------------------------------------|
| `#`    | Field contributing to maturity level 1 — minimum granularity      |
| `##`   | Field contributing to maturity level 2 — intermediate granularity |
| `###`  | Field contributing to maturity level 3 — fine granularity         |

### Calculating the Maturity Level

An element is considered **compliant** when all fields marked `#` corresponding to the target level are
filled in and the expected links with other cartography elements have been established.

An element is considered **non-compliant** when:

- a field marked `#` is empty or not filled in (e.g., missing description or owner),
- an expected link to another element is missing (e.g., an application not supporting any business process, a
  server not associated with any application).

The maturity level is calculated using the following formula:

```text
Maturity level (%) = (Number of compliant elements / Total number of elements) × 100
```

### Visual Identification of Non-Compliant Elements

In lists, **non-compliant** entries are highlighted in **yellow**, indicating that at least one field marked `#`
has not been filled in. This allows quick identification of elements requiring particular attention in order
to progress toward a complete cartography.

### Compliance fields

The calculation of conformity levels for each mapping object is based on the presence of the following elements:

| Object                      | Level | Required elements                                                                              |
|-----------------------------|-------|------------------------------------------------------------------------------------------------|
| **Ecosystem**               |       |                                                                                                |
| Entities                    | 1     | Description, security level, point of contact, at least one process                            |
| Relationships               | 1     | Description, type                                                                              |
| Relationships               | 2     | Importance                                                                                     |
| **Business**                |       |                                                                                                |
| Macro-processes             | 2     | Description, io_elements, security levels                                                      |
| Macro-processes             | 3     | Responsible                                                                                    |
| Process                     | 1     | Description, input-output, responsible                                                         |  
| Process                     | 2     | Macro-processes, security requirements                                                         |
| Activities                  | 2     | Description                                                                                    |
| Operations                  | 1     | Description                                                                                    |
| Operations                  | 2     | Actors                                                                                         |
| Operations                  | 3     | Tasks                                                                                          |
| Tasks                       | 3     | Description                                                                                    |
| Actor                       | 2     | Contact, nature, type                                                                          |
| Information                 | 1     | Description, owner, administrator, storage                                                     |
| Information                 | 2     | Security Requirements, Sensitivity                                                             |
| **Information system**      |       |                                                                                                |
| Application block           | 2     | Description, responsible, applications                                                         |
| Applications                | 1     | Description, technology, type, users, process                                                  |
| Applications                | 2     | Responsible, security levels                                                                   |
| Application Services        | 2     | Description, applications                                                                      |
| Application Modules         | 2     | Description                                                                                    |
| Database                    | 1     | Description, type, responsible entity, responsible person                                      |
| Database                    | 2     | Security requirements                                                                          |
| Flows                       | 1     | Description, source, destination                                                               |
| **Administration**          |       |                                                                                                |
| Zones                       | 1     | Description                                                                                    |
| Directories                 | 1     | Description, solution, administration area                                                     |
| Forest                      | 1     | Description, administration area                                                               |
| Domains                     | 1     | Description, domain controller, number of users, number of machines, inter-domain relationship |
| **Logical**                 |       |                                                                                                |
| Networks                    | 1     | Description, manager, security manager, security requirements                                  |
| Subnets                     | 1     | Description, address, default gateway, IP allocation type, DMZ, WiFi, VLAN                     |
| Gateways                    | 1     | Description, authentication, IP range                                                          |
| Connected external devices  | 2     | Type, contacts                                                                                 |
| Switches                    | 1     | Description                                                                                    |
| Routers                     | 1     | Description                                                                                    |
| Security devices            | 1     | Description                                                                                    |
| Clusters                    | 1     | Description, type                                                                              |  
| Logical servers             | 1     | Description, OS, environment, IP address, applications, physical servers or clusters           |
| VLans                       | 1     | Description                                                                                    |
| Certificates                | 2     | Description, Type, validity start date, validity end date, applications or logical server      |
| **Physical infrastructure** |       |                                                                                                |
| Sites                       | 1     | Description                                                                                    |
| Buildings                   | 1     | Description                                                                                    |
| Bays                        | 1     | Description                                                                                    |
| Physical servers            | 1     | Description, configuration, site, building, responsible                                        |
| Workstations                | 1     | Description, site, building                                                                    |
| Telephones                  | 1     | Description, site, building                                                                    |
| Storage                     | 1     | Description, site, building                                                                    |
| Peripherals                 | 1     | Description, site, building, responsible                                                       |
| Physical switches           | 1     | Description, type, site, building                                                              |
| Physical routers            | 1     | Description, type, site, building                                                              |
| WiFi terminals              | 1     | Description, type, site, building                                                              |
| Physical security devices   | 1     | Description, type, site, building                                                              |
| LANs                        | 1     | Description                                                                                    |

