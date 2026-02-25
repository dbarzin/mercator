## Views

The cartography is made up of three views progressively moving from business to technical, themselves broken down into
views:

### GDPR view

The GDPR view is used to maintain the register of processing operations and to make the link with the processes,
information, applications and security measures in place.

### Business view

The ecosystem view presents the various entities or systems with which the IS interacts to fulfill its function.

The business view of the information system represents the IS through its main processes and information, which are the
business values as defined by the EBIOS Risk Manager risk assessment method.

### Application view

The application view describes the information system's software components, the services they provide and the data
flows between them.

The application flow view describes the information flows between different applications, services, modules and
databases.

### Administrative view

The administration view lists the perimeters and privilege levels of users and administrators.

### Logical view

The logical infrastructure view illustrates the logical partitioning of networks, notably through the definition of IP
address ranges, VLANs and filtering and routing functions;

### Infrastructure view

The physical infrastructure view describes the physical equipment that makes up or is used by the information system.

## Maturity Levels

Maturity levels represent the completeness percentage of the cartography. It is an indicator of the remaining effort
required to achieve a complete mapping, in accordance with the recommendations of the
[ANSSI Information System Mapping Guide](https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/).

This maturity is divided into three levels:

- **Level 1 minimum granularity**, which contains the information essential to the cartography;
- **Level 2 intermediate granularity**, which contains the information important to the cartography;
- **Level 3 fine granularity**, which contains the information useful for managing the security of the information
  system.

[<img src="/mercator/images/maturity.png" width="600">](images/maturity.png)

### Meaning of Fields Marked with cardinal

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

```
Maturity level (%) = (Number of compliant elements / Total number of elements) × 100
```

### Visual Identification of Non-Compliant Elements

In lists, **non-compliant** entries are highlighted in **yellow**, indicating that at least one field marked `#`
has not been filled in. This allows quick identification of elements requiring particular attention in order
to progress toward a complete cartography.

## Security needs

Information security needs are expressed in terms of confidentiality, integrity, availability and traceability with the
following scale:

| Level |  Description  |                   Color                   |
|:-----:|:-------------:|:-----------------------------------------:|
|   0   | Insignificant |                   White                   |
|   1   |      Low      |  <span style="color:green">Green</span>   |
|   2   |    Medium     | <span style="color:yellow;">Yellow</span> |
|   3   |    Strong     | <span style="color:orange">Orange</span>  |
|   4   |  Very strong  |    <span style="color:red">Red</span>     |
