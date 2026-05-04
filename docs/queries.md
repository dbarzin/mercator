# Queries

Mercator queries let you explore and visualise your cartography data flexibly, without going through the standard interface. They are written in a declarative SQL-inspired language and can produce either a **list** or a **graph**.

## Query format

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
| `FROM` | ✅ | Target data model (see [Available models](#available-models)) |
| `FIELDS` | ➖ | List of fields to display, including relation fields (`relation.field`) |
| `WHERE` | ➖ | Data filter (see [Conditions](#conditions)) |
| `WITH` | ➖ | Relations to load via eager loading |
| `OUTPUT` | ➖ | Output format: `list` or `graph` (default: `list`) |
| `LIMIT` | ➖ | Maximum number of records returned (default: 100) |

## Available models {#available-models}

Models correspond to entities in the Mercator [API](api.md) and are written in **kebab-case**.

| Model              | Description |
|--------------------|-------------|
| `logical-servers`  | Logical servers |
| `physical-servers` | Physical servers |
| `applications`     | Applications |
| `databases`        | Databases |
| `certificates`     | SSL/TLS certificates |
| `networks`         | Networks / subnetworks |
| `storage-devices`  | Storage devices |
| `sites`            | Physical sites |
| `bays`             | Hosting bays |
| …                  | _All API models_ |

!!! info "Available fields"
    The fields available in `FIELDS` and `WHERE` are those exposed by the Mercator API.
    Refer to the [data model](model.md) for the full list of attributes for each model.

## FIELDS clause

The `FIELDS` clause lists the attributes to display in the result. It accepts:

- **Direct model fields**: `name`, `cpu`, `environment`, `end_validity`, …
- **Relation fields** in `relation.field` format: `applications.name`, `site.name`, `databases.name`, …

```sql
FIELDS name, operating_system, cpu, memory, applications.name
```

!!! info "Protected fields"
    Fields marked as hidden in Eloquent models (`$hidden`), such as `password` or `remember_token`, are never returned by the query engine, even if explicitly listed in `FIELDS`.

!!! warning "Consistency with WITH"
    If you reference a relation field in `FIELDS` (e.g. `applications.name`), the corresponding relation must be declared in `WITH` (e.g. `WITH applications`), otherwise the data will not be loaded.

## WHERE clause {#conditions}

The `WHERE` clause filters records based on conditions on the main model's fields.

### Supported operators

| Operator | Syntax | Example |
|----------|--------|---------|
| Equality | `=` | `environment = "production"` |
| Inequality | `!=` | `type != "virtual"` |
| Comparison | `<`, `>`, `<=`, `>=` | `memory >= 16` |
| Pattern match | `LIKE` | `operating_system LIKE "%Linux%"` |
| Value list | `IN` | `environment IN ("production", "staging")` |
| Relation exists | `EXISTS` | `EXISTS applications` |
| Relation absent | `NOT EXISTS` | `NOT EXISTS certificates` |

### Logical combinations

Conditions can be combined with `AND` and `OR`. Each condition should be wrapped in parentheses:

```sql
WHERE (environment = "production") AND (operating_system LIKE "%Linux%")
```

```sql
WHERE (environment IN ("production", "staging")) AND (operating_system LIKE "%Windows%")
```

### EXISTS operator {#exists}

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
    The `EXISTS` operator does not load the relation's data.
    If you also want to display fields from that relation in `FIELDS`, declare it explicitly in `WITH`.

## WITH clause

The `WITH` clause declares the **relations to load** (eager loading). It is required to access fields from related objects in `FIELDS`.

```sql
WITH applications, databases, certificates
```

Relation names correspond to Eloquent relation method names, in **snake_case**:

```sql
WITH logical_servers, databases, sites, bays
```

### Hidden intermediate nodes

By default, each segment of a `WITH` path creates a node in the graph. You can **hide an intermediate level** by wrapping it in parentheses: the level is still traversed to reach the next levels, but it appears neither as a node nor as an edge in the resulting graph.

```sql
WITH (subnetworks).vlan
```

In this example, subnetworks act only as a traversal pivot. The graph displays direct `networks → vlan` edges without representing the `subnetworks` themselves.

The syntax generalises to multiple hidden levels:

```sql
-- Hide every other level
WITH (subnetworks).routers.(interfaces).vlan

-- Hide several consecutive levels
WITH (subnetworks).(routers).vlan
```

Rules to follow:

- A fully hidden path (all segments in parentheses) has no effect.
- The last segment of a path cannot be hidden.
- Nested parentheses `((rel))` are not allowed.

!!! tip "When to hide a level?"
    Hide an intermediate node when the pivot relation has no semantic value in the visualisation — for example, subnetworks between a network and its VLANs, or interfaces between a server and its VLANs.

## Output format (OUTPUT)

### `OUTPUT list`

Produces a **table** with one row per record. This format suits inventories, exports, and tabular views.

```sql
OUTPUT list
```

### `OUTPUT graph`

Produces a **relationship graph** between the returned entities. This format suits dependency visualisation, application cartography, or network relationships.

```sql
OUTPUT graph
```

!!! tip "When to use `graph`?"
    Prefer `OUTPUT graph` when your query loads relations with `WITH` and you want to visualise the links between entities (applications ↔ servers, networks ↔ servers, etc.).

## Saving queries

Queries can be **saved** in the interface so they can be retrieved and re-executed without retyping them. Saved queries can be made public (visible to all users) or private (visible only to their author).

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

Visualises networks, subnetworks and their VLANs.

### Networks and VLANs without intermediate subnetworks

```sql
FROM networks
WITH (subnetworks).vlan
OUTPUT graph
```

Generates a graph linking each network directly to its VLANs. Subnetworks act as a traversal pivot but do not appear in the graph — useful for a concise view when subnetworks add no meaningful information to the visualisation.

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

### Critical applications with their servers and databases

```sql
FROM applications
FIELDS name, security_need_c, description, responsible, logical_servers.name, databases.name
WHERE (security_need_c IN ("3", "4"))
WITH logical_servers, databases
OUTPUT graph
```

Cartography of applications with high confidentiality requirements (levels 3 and 4), with their infrastructure dependencies.

### Servers without an SSL certificate

```sql
FROM logical-servers
FIELDS name, environment, operating_system
WHERE (environment = "production") AND (NOT EXISTS certificates)
WITH certificates
```

Identifies production servers on which no SSL/TLS certificate is registered. Useful for detecting blind spots in certificate management.

### Production servers without backup plans but with an application

```sql
FROM logical-servers
FIELDS name, applications.name
WHERE environment = "production"
AND NOT EXISTS backups
AND EXISTS applications
OUTPUT list
```

Identifies servers and application names in production that have no backup plans but have at least one application attached.

### Applications without an associated logical server

```sql
FROM applications
FIELDS name, responsible, security_need_c
WHERE (NOT EXISTS logical_servers)
WITH logical_servers
```

Lists applications not attached to any logical server — a possible indicator of an incomplete cartography.

## Best practices

- **Use `LIMIT`** to limit the number of results to the necessary value: overly broad queries can be slow on large repositories.
- **Use `OUTPUT graph`** only when relations are declared in `WITH`; a graph without relations will consist of isolated nodes only.
- **Check field names** in the [API reference](api.md) — a typo in a field name simply returns nothing, with no error message.
- **With `EXISTS`**, declare the relation in `WITH` only if you need to display its fields in `FIELDS`; otherwise, `EXISTS` alone is sufficient to filter without extra overhead.
- **Save recurring queries** to facilitate teamwork and ensure the reproducibility of cartographies.