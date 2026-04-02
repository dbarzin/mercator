# Exploration and Dependencies

Mercator provides two complementary tools for navigating the map:

- **Exploration** — displays a free-form graph around an asset: clicking a node in the graph reveals all objects connected to it (physically or logically) along with all incoming and outgoing flows.
- **Dependencies** — imposes a layered structure (upstream → downstream) starting from a selected asset.

These tools allow you to navigate dynamically through the graph of relationships between assets. They reveal dependencies across layers: upward (abstract / business) or downward (concrete / physical).

Hierarchical views are available in both cases: starting from a macro-process, you can visualise all the processes, activities and operations that depend on it; or starting from a site, all the rooms and equipment it contains.

## Exploration

The `Exploration` tool *(Tools ➡️ Exploration)* displays a relational graph **with no imposed hierarchy**.  
Nodes are positioned by a physics engine (attraction / repulsion), which allows you to:

- freely explore relationships
- identify clusters
- navigate complex dependencies
- visualise the full graph around an asset

This mode is ideal for **intuitive exploration**.

🔍 See also the [interface overview](./application.md/#map-exploration)

## Dependencies

The `Dependencies` tool *(Tools ➡️ Dependencies)* imposes a **layered structure** (upstream → downstream).  
Dependencies are directed and organised by level, which allows you to:

- quickly understand data flows
- analyse upstream or downstream dependencies
- document an architecture
- clarify complex relationships

This mode is ideal for **structured analysis**.

🔍 See also [interface overview](./application.md/#dependency-analysis)

---

## Interfaces

Description of the Exploration and Dependencies interfaces.

| UI Element       | Type           | 🕸️ Exploration | ⟁ Dependencies | Role                                                              |
|------------------|----------------|:-:|:-:|-----------------------------------------------------------------------|
| **Filter**       | Dropdown list  | ✅ | ✅ | Filters on map views (e.g. `Applications`)                         |
| **Attributes**   | Dropdown list  | ✅ | ✅ | Filters on asset attributes (e.g. `Opensource`)                    |
| **Object**       | Dropdown list  | ✅ | ✅ | Selects the asset to explore (e.g. `HR-Solution`)                  |
| **Add**          | Green button   | ✅ |    | Adds the selected asset to the graph window                        |
| **Physical**     | Green toggle   | ✅ |    | Enables/disables display of physical network links                 |
| **Level (1–5)**  | Numeric picker | ✅ | ✅ | Number of relationship levels to expand                            |
| **Deploy**       | Green button   | ✅ |    | Launches exploration with the defined parameters                   |
| **Analyze**      | Green button   |    | ✅ | Launches dependency analysis with the defined parameters           |
| **↑ Up**         | Cyan button    | ✅ | ✅ | Expands toward upper layers (abstract / business)                  |
| **↓ Down**       | Cyan button    | ✅ | ✅ | Expands toward lower layers (concrete / physical)                  |
| **↕ Both**       | Cyan button    | ✅ | ✅ | Expands in both directions simultaneously                          |
| **Remove**       | Red button     | ✅ |    | Removes the selected asset from the graph                          |
| **Reset**        | Yellow button  | ✅ | ✅ | Completely resets the graph                                        |
| **Show IP**      | Grey button    | ✅ |    | Displays IP addresses on network assets                            |

---

## Filter

> ⚠️ **Critical point**: the Filter field has a dual effect that is essential to understand before using these tools.

### Effect 1 — Restricting the "Object" Dropdown

This is the most intuitive use. By selecting a view in the Filter field (e.g. `Applications`), the "Object" dropdown only shows assets belonging to that view. This avoids searching for an asset across the entire CMDB.

### Effect 2 — Limiting Asset Visibility in the Graph (common pitfall)

This is the least expected effect, and the most frequent source of errors. **The filter does not simply restrict the "Object" list: it also controls which asset types will appear in the graph during exploration.**

Concretely: if you select a `logical-server` with only `Logical Infrastructure` in the filter, applications linked to that server will **never** appear in the graph, even if they exist in Mercator and are correctly associated. They are simply excluded because their type is not covered by the active filter.

**Illustrated example:**

| Active filter                             | Explored asset         | Result in the graph                                                                      |
|-------------------------------------------|------------------------|------------------------------------------------------------------------------------------|
| `Logical Infrastructure`                  | `LOGICAL-SERVER-HR-11` | Visible: `NETWORK-CORE-11`, `SUBNET-CORE-11`, `SUBNET-VIRT-111` — but not `HR-Solution` |
| `Applications` + `Logical Infrastructure` | `LOGICAL-SERVER-HR-11` | Also visible: `HR-Solution` and `DB-HR-PROD`                                             |
| *(empty)*                                 | Any asset              | All linked assets are visible, across all layers                                         |

| Filter |                                               🕸️ Exploration                                                |                                               ⟁ Dependencies                                                |
|--------|:------------------------------------------------------------------------------------------------------------:|:-----------------------------------------------------------------------------------------------------------:|
| `Logical Infrastructure` only:<br>HR-Solution does not appear. | [<img src="/mercator/images/filter_exploration_infra.png" width="550">](images/filter_exploration_infra.png) | [<img src="/mercator/images/filter_dependency_infra.png" width="550">](images/filter_dependency_infra.png)  |
| `Applications` + `Logical Infrastructure`:<br>HR-Solution and DB-HR-PROD appear. |  [<img src="/mercator/images/filter_exploration_full.png" width="550">](images/filter_exploration_full.png)  |  [<img src="/mercator/images/filter_dependency_full.png" width="550">](images/filter_dependency_full.png)   |

### Practical Rule: Which Filter to Choose?

| Goal                                                                      | Recommended filter                                       |
|---------------------------------------------------------------------------|----------------------------------------------------------|
| Quickly find an asset within a specific view                              | Enter only the target view (e.g. `Applications`)         |
| Cross-layer exploration or analysis (application + infrastructure)        | Enter **all** relevant views, or leave empty             |
| Full impact analysis (all layers)                                         | **Leave the filter empty** to exclude nothing            |
| Exploration or analysis limited to a single layer (e.g. network only)    | Enter only that layer's view                             |

> 💡 **Tips**:
> - When in doubt, always start with an **empty** filter. You can narrow it down afterwards if the graph becomes too dense.
> - Trick: use the filter to easily locate the asset in the list, add it to the graph, then **clear the filter** before clicking `🕸️ Deploy` or `⟁ Analyze` — assets from all layers will then be visible.
> - You can also type the beginning of an asset name directly in the "Object" field to filter without using the Filter field.

---

## Direction

The direction of dependency analysis is relative to the **Mercator view hierarchy**. This hierarchy is aligned with ArchiMate:

```
↑ UP    ═══════════════════════════════════════════════  toward 100 (Business)
         Layer 1 : Entities, Processes, Actors  (100–260)
         Layer 2 : Applications, Modules, DBs   (300–340)
         Layer 3 : IAM, Active Directory         (400–460)
         Layer 4 : Networks, VMs, Containers     (500–580)
         Layer 5 : Physical, Sites, Racks         (600–675)
↓ DOWN  ═══════════════════════════════════════════════  toward 675 (Physical)
```

The directional buttons work as follows:

| Button       | Direction           | Meaning                                                                       | Example from `HR-Solution`                          |
|--------------|---------------------|-------------------------------------------------------------------------------|-----------------------------------------------------|
| **↑ Up**     | Toward upper layers | Goes up toward the business layer: processes and actors that use this asset   | `Application → HR Process → HR Director actors`     |
| **↓ Down**   | Toward lower layers | Goes down toward infrastructure: servers and networks that support this asset | `Application → VM → Physical server → Rack → Site` |
| **↕ Both**   | Bidirectional       | Complete view: who uses this asset AND what it relies on                      | `Process ← Application → Server → Physical`        |

---

## Usage

Step-by-step usage instructions.

### Exploration

```
Step 1 ─ Enter a filter (optional)
         └─ "Filter" field: e.g. "Applications"
            Restricts the object list to a specific view
            ⚠️ See Filter section: the filter also limits which assets appear in the graph

Step 2 ─ Select the starting asset
         └─ "Object" dropdown: e.g. "HR-Solution"

Step 3 ─ Add the asset to the graph
         └─ Click the green "Add" button
            The asset appears in the graph window

Step 4 ─ Select the asset in the graph
         └─ Click the asset icon in the graph area
            (it must be active / selected)

Step 5 ─ Choose the direction
         └─ Click: ↑ Up  |  ↓ Down  |  ↕ Both

Step 6 ─ Set the number of levels
         └─ Numeric picker: 1 to 5
            Start with 1 or 2 for highly connected assets

Step 7 ─ Deploy
         └─ Click the green "Deploy" button
            The graph builds with the relationships found

Step 8 ─ Iterate (optional)
         └─ Click another node in the graph
            Repeat from step 4
```

### Dependencies

```
Step 1 ─ Enter a filter (optional)
         └─ "Filter" field: e.g. "Applications"
            ⚠️ See Filter section: the filter also limits which assets appear in the graph

Step 2 ─ Select the starting asset
         └─ "Object" dropdown: e.g. "HR-Solution"

Step 3 ─ Choose the direction
         └─ Click: ↑ Up  |  ↓ Down  |  ↕ Both

Step 4 ─ Set the number of levels
         └─ Numeric picker: 1 to 5
            Start with 1 or 2 for highly connected assets

Step 5 ─ Analyze
         └─ Click the green "Analyze" button
            The layered graph builds with the dependencies found
            (no need to select an asset in the graph)
```

> 💡 Dependencies mode has no "Add" button: the starting asset is selected directly from the "Object" dropdown.

---

## Examples

### Business Impact of an Application

> **Context:** Which business processes and actors depend on `HR-Solution`?

| Parameter | Value          |
|-----------|----------------|
| Filter    | `Applications` |
| Object    | `HR-Solution`  |
| Direction | **↑ Up**       |
| Levels    | `3`            |

*The `Applications` filter is sufficient here since the ↑ Up navigation stays within the business layer (processes, actors), which is covered by this view.*

**Expected result:**

```
HR-Solution
 └── APPLI-HR-MOD-1 (module)
      └── HR Management Process
           └── Actor: HR Director
```

You get the full list of processes and actors that functionally depend on this application.

---

### Infrastructure Supporting an Application

> **Context:** What physical hardware does `HR-Solution` run on? (BCP, ITIL impact analysis, CMDB)

| Parameter | Value                                                                               |
|-----------|-------------------------------------------------------------------------------------|
| Filter    | `Applications` + `Logical Infrastructure` + `Physical Infrastructure` (or *empty*) |
| Object    | `HR-Solution`                                                                       |
| Direction | **↓ Down**                                                                          |
| Levels    | `3`                                                                                 |

*An `Applications`-only filter would exclude servers and racks from the graph. All traversed layers must be included, or the filter left empty.*

**Expected result:**

```
HR-Solution
 ├── APPLI-HR-SRV-1 (application service)
 └── DB-HR-PROD (database)
      └── VM-APP-HR-01 (logical server)
           └── SRV-PROD-01 (physical server)
                └── RACK-A3 (rack)
                     └── Site DC-Paris
```

You get the complete chain from the application down to the physical site.

---

### Full Impact Analysis (Before Maintenance / Incident)

> **Context:** Complete view of ALL dependencies of `HR-Solution` before a maintenance window.

| Parameter | Value         |
|-----------|---------------|
| Filter    | *(empty)*     |
| Object    | `HR-Solution` |
| Direction | **↕ Both**    |
| Levels    | `3`           |

*The filter must be empty so that all layers (business, application, physical infrastructure) are simultaneously visible in the graph.*

**Expected result:**

```
         Macro-process: Personnel Management
              └── HR Process → HR Director actor
                       ↑
              [HR-Solution]  ← central asset
                       ↓
         APPLI-HR-SRV-1 · APPLI-HR-MOD-1 · DB-HR-PROD
              └── Logical servers → Physical → Racks → Site
```

Ideal for **architecture dossiers** and **impact analyses**.

---

### Choosing the Direction by Use Case

| Use case                                 | Direction | Layers traversed                                |
|------------------------------------------|-----------|-------------------------------------------------|
| Who uses this application?               | ↑ Up      | Application → Processes → Actors                |
| What does this server run on?            | ↓ Down    | Logical server → Physical → Rack → Site         |
| Full impact analysis                     | ↕ Both    | Business ↔ Application ↔ Infrastructure         |
| Which processes depend on a database?    | ↑ Up      | Database → Application → Processes              |
| Which network equipment is under a VLAN? | ↓ Down    | VLAN → Switch → Physical router                 |
| Map an AD domain                         | ↕ Both    | AD Forest → Domain → Admin Zone → Servers       |
| Which assets does an actor use?          | ↓ Down    | Actor → Process → Application → Infrastructure  |

---

## Mapping

| Mercator Asset               | BPMN 2.0          | ArchiMate 3.1         | TOGAF                 |
|------------------------------|-------------------|-----------------------|-----------------------|
| `entities` (100)             | Pool / Lane       | Business Actor        | Organizational Unit   |
| `macro-processes` (200)      | Process (level 1) | Business Process      | Business Function     |
| `processes` (210)            | Sub-Process       | Business Process      | Business Service      |
| `activities` (220)           | Task / Activity   | Business Function     | Business Function     |
| `tasks` (240)                | Task (atomic)     | Business Interaction  | —                     |
| `actors` (250)               | Lane / Performer  | Business Role         | Business Actor        |
| `information` (260)          | Data Object       | Business Object       | Data Entity           |
| `applications` (310)         | —                 | Application Component | Application Component |
| `application-services` (320) | —                 | Application Service   | Application Service   |
| `databases` (340)            | Data Store        | Data Object           | Data Store            |
| `logical-servers` (580)      | —                 | System Software       | Platform Service      |
| `sites` (600)                | —                 | Location              | Geography             |
| `physical-servers` (615)     | —                 | Device                | Technology Component  |

### ArchiMate Relations in Mercator

| ArchiMate Relation  | Direction 🕸️ ⟁ | Mercator Example                              |
|---------------------|-----------------|-----------------------------------------------|
| **Serving**         | ↑ Up            | Application Service serves a Business Process |
| **Realization**     | ↕ Both          | Application realizes a Business Service       |
| **Assignment**      | ↓ Down          | Logical Server assigned to Physical Server    |
| **Composition**     | ↓ Down          | Site contains Buildings contains Racks        |
| **Association**     | ↕ Both          | Application associated with Database          |

---

## Best Practices

### Tool Recommendations

**Start at level 1 or 2**  
For highly connected assets (e.g. a central application), starting at 1 or 2 levels prevents an unreadable graph. Increase progressively from there.

**Use the filter with care**  
As explained in the Filter section, the filter controls not only the list of available objects but also the assets visible in the graph. For cross-layer exploration or analysis, include all relevant views or leave the filter empty.

**"Physical" Toggle (Exploration mode only)**  
This toggle, available only in 🕸️ Exploration mode, enables or disables the display of physical network links (WAN/LAN/MAN). When disabled, exploration stays at the logical level. It does not constitute a third visualisation mode.

### Asset Entry Recommendations

- Follow Mercator's numbering: create physical layer assets (600+) before associating them with logical layers.
- Physical containment relationships must be entered in order: **Site → Building → Bay → Physical Server**.
- A `logical-server` (VM) must always be linked to a `physical-server` for ↓ Down navigation to work correctly.
- `certificates` (570) are cross-layer: associate them with both applications AND logical servers for complete exploration or analysis.
- `external-connected-entities` (540) can be attached to the business layer (partners) or the network layer (connections) depending on context.

### Use Cases by User Profile

| Profile                  | Preferred direction | Typical use case                                              |
|--------------------------|---------------------|---------------------------------------------------------------|
| Enterprise Architect     | ↑ Up                | Trace alignment between infrastructure and business processes |
| Infrastructure Architect | ↓ Down              | Identify the physical chain supporting an application         |
| CISO / Risk Manager      | ↕ Both              | Map dependencies for risk analysis                            |
| CMDB Manager             | ↓ Down              | Verify completeness of physical chaining                      |
| Crisis Manager / BCP     | ↕ Both              | Impact analysis before/after an incident                      |