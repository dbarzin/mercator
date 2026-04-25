# Queries

Mercator queries let you explore and visualize your cartography data in a flexible way, without going through the standard interface. They are written in a declarative language inspired by SQL and can produce a **list** or a **graph**.

## Query Format

A Mercator query follows this syntax:

```sql
FROM    <Model>
FIELDS  <field1>, <field2>, <relation.field>, ...
WHERE   (<condition1>) AND|OR (<condition2>)
WITH    <relation1>, <relation2>, ...
OUTPUT  list | graph
LIMIT   <n>
```

| Clause | Required | Description |
|--------|----------|-------------|
| `FROM` | ✅ | Target data model (see [Available Models](#available-models)) |
| `FIELDS` | ➖ | List of fields to display, including relation fields (`relation.field`) |
| `WHERE` | ➖ | Data filter (see [Conditions](#conditions)) |
| `WITH` | ➖ | Relations to load via eager loading |
| `OUTPUT` | ➖ | Output format: `list` or `graph` (`list` by default) |
| `LIMIT` | ➖ | Maximum number of records returned (default: 100) |

## Available Models

Models correspond to Mercator API entities. Names are in **kebab-case** and identical to the resource names exposed by the REST API.

| Model              | Description |
|--------------------|-------------|
| `logical-servers`  | Logical servers |
| `physical-servers` | Physical servers |
| `applications`     | Applications |
| `databases`        | Databases |
| `certificates`     | SSL/TLS certificates |
| `networks`         | Networks / subnets |
| `storage-devices`  | Storage devices |
| `sites`            | Physical sites |
| `bays`             | Hosting bays |
| …                  | _All API models_ |

!!! info "Available fields"
    The fields usable in `FIELDS` and `WHERE` are exactly those exposed by the Mercator API.
    Refer to the [Data Model](model.md) for the full list of attributes for each model.

## FIELDS Clause

The `FIELDS` clause lists the attributes to display in the result. It accepts:

- **Direct fields** of the model: `name`, `cpu`, `environment`, `end_validity`, …
- **Relation fields** in `relation.field` format: `applications.name`, `site.name`, `databases.name`, …

```sql
FIELDS name, operating_system, cpu, memory, applications.name
```

!!! info "Available fields"
    The fields usable in `FIELDS` and `WHERE` are those exposed by the Mercator API.
    Consult the [data model](model.md) for a complete list of attributes for each model.

!!! warning "Consistency with WITH"
    If you reference a relation field in `FIELDS` (e.g. `applications.name`), the corresponding relation must be declared in `WITH` (e.g. `WITH applications`), otherwise the data will not be loaded.

## WHERE Clause {#conditions}

The `WHERE` clause filters records based on conditions on the main model's fields.

### Supported Operators

| Operator | Syntax | Example |
|----------|--------|---------|
| Equality | `=` | `environment = "production"` |
| Inequality | `!=` | `type != "virtual"` |
| Comparison | `<`, `>`, `<=`, `>=` | `memory >= 16` |
| Search | `LIKE` | `operating_system LIKE "%Linux%"` |
| Value list | `IN` | `environment IN ("production", "staging")` |
| Relation exists | `EXISTS` | `EXISTS applications` |
| Relation absent | `NOT EXISTS` | `NOT EXISTS certificates` |

### Logical Combinations

Conditions can be combined with `AND` and `OR`. Each condition must be enclosed in parentheses:

```sql
WHERE (environment = "production") AND (operating_system LIKE "%Linux%")
```

```sql
WHERE (environment IN ("production", "staging")) AND (operating_system LIKE "%Windows%")
```

### EXISTS Operator {#exists}

The `EXISTS` operator filters records based on whether a relation is present or absent. It takes the Eloquent relation name (as declared in `WITH`).

```sql
WHERE (EXISTS applications)
```

```sql
WHERE (NOT EXISTS certificates)
```

`EXISTS` can be combined with other conditions:

```sql
WHERE (environment = "production") AND (EXISTS certificates)
```

!!! info "EXISTS and eager loading"
    The `EXISTS` operator does not load relation data. If you also want to display fields from that relation in `FIELDS`, declare it explicitly in `WITH`.

## WITH Clause

The `WITH` clause declares the **relations to load** (eager loading). It is required in order to access linked object fields in `FIELDS`.

```sql
WITH applications, databases, certificates
```

Relation names correspond to the relation method names of the Eloquent models, in **snake_case**:

```sql
WITH logical_servers, databases, sites, bays
```

## Output Format (OUTPUT)

### `OUTPUT list`

Generates a **table** with one row per record. This format is suited for inventories, exports, or tabular views.

```sql
OUTPUT list
```

### `OUTPUT graph`

Generates a **relationship graph** between the returned entities. This format is suited for visualizing dependencies, application maps, or network relationships.

```sql
OUTPUT graph
```

!!! tip "When to use `graph`?"
    Prefer `OUTPUT graph` whenever your query loads relations with `WITH` and you want to visualize the links between entities (applications ↔ servers, networks ↔ servers, etc.).

## Saving Queries

It is possible to **save queries** in the interface to retrieve and re-run them without retyping them. Saved queries can be made public (visible to all users) or private (visible only to their author).

## Examples

### Linux production servers with their applications

```sql
FROM logical-servers
FIELDS name, operating_system, environment, cpu, memory, applications.name
WHERE (environment = "production") AND (operating_system LIKE "%Linux%")
WITH applications
```

Returns the list of logical servers running Linux in the production environment, along with the names of the hosted applications.

### All applications and their databases

```sql
FROM applications
FIELDS name, description, databases.name, logical_servers.name
WITH databases, logical_servers
OUTPUT graph
```

Generates a graph linking applications to their databases and logical servers.

### Physical server inventory

```sql
FROM physical-servers
FIELDS name, type, cpu, memory, site.name, bay.name
WITH site, bay
```

Full list of physical servers with their location (site and bay).

### Networks, subnetworks and VLANs

```sql
FROM networks
FIELDS name, subnetworks.name, subnetworks.vlan.id, subnetworks.vlan.name
WITH subnetworks, subnetworks.vlan
```

Visualizes networks, subnetworks and their VLANs.

### Multiple filters with `IN`

```sql
FROM logical-servers
FIELDS applications.name, certificates.name
WHERE (environment IN ("production", "staging")) AND (operating_system LIKE "%Windows%")
WITH applications, certificates
```

Lists the applications and certificates installed on Windows servers in production or staging.

### SSL certificates with expiry date and deployment scope

```sql
FROM certificates
FIELDS name, type, end_validity, domains, logical_servers.name, applications.name
WITH logical_servers, applications
```

Inventory of SSL/TLS certificates with their expiry date and the servers/applications on which they are deployed. Useful for anticipating renewals.


### Servers in production without backup plans and with at least one application

```sql
FROM logical-servers
FIELDS name, applications.name
WHERE environment = "production"
AND NOT EXISTS backups
AND EXISTS applications
OUTPUT list
```

Identify the servers and the names of the production applications that do not have backup plans and at least one application.


### Critical applications with their servers and databases

```sql
FROM applications
FIELDS name, security_need_c, description, responsible, logical_servers.name, databases.name
WHERE (security_need_c IN ("3", "4"))
WITH logical_servers, databases
OUTPUT graph
```

Maps applications with high confidentiality requirements (levels 3 and 4) along with their infrastructure dependencies.

### Servers without an SSL certificate

```sql
FROM logical-servers
FIELDS name, environment, operating_system
WHERE (environment = "production") AND (NOT EXISTS certificates)
WITH certificates
```

Identifies production servers with no SSL/TLS certificate registered. Useful for detecting blind spots in certificate management.

### Applications not linked to any logical server

```sql
FROM applications
FIELDS name, responsible, security_need_c
WHERE (NOT EXISTS logical_servers)
WITH logical_servers
```

Lists applications not attached to any logical server, which may indicate an incomplete cartography.

## Best Practices

- **Keep `LIMIT` to the necessary value**: overly broad queries can be slow on large repositories.
- **Use `OUTPUT graph`** only when relations are declared in `WITH`; a graph without relations will consist only of isolated nodes.
- **Verify field names** in the [API reference](api.md) — a typo in a field name simply displays nothing, with no error message.
- **With `EXISTS`**, declare the relation in `WITH` only if you need to display its fields in `FIELDS`; otherwise, `EXISTS` alone is sufficient to filter without additional overhead.
- **Save recurring queries** to facilitate teamwork and ensure reproducibility of cartographies.