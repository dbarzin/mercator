## Exploration Feature

The **Exploration** tool (`Tools → Exploration`) allows you to dynamically navigate through the asset relationship
graph. It reveals dependencies between layers: upward (abstract / business) or downward (concrete / physical).

## 1. Interface

[<img src="/mercator/images/explorer.png" width="700">](images/explorer.en.png)

| UI Element      | Type          | Role                                                     |
|-----------------|---------------|----------------------------------------------------------|
| **Filter**      | Text field    | Filters the cartography views (e.g., `Applications`)     |
| **Object**      | Dropdown list | Selects the asset to explore (e.g., `HR-Solution`)       |
| **Add**         | Blue button   | Adds the selected asset to the graph window              |
| **Physical**    | Green toggle  | Enables/disables the display of physical links           |
| **Level (1–5)** | Selector      | Number of relationship levels to expand                  |
| **Deploy**      | Green button  | Launches the exploration based on the defined parameters |
| **↑ Up**        | Cyan button   | Expands toward upper layers (abstract / business)        |
| **↓ Down**      | Cyan button   | Expands toward lower layers (concrete / physical)        |
| **↕ Both**      | Cyan button   | Expands in both directions simultaneously                |
| **Remove**      | Red button    | Removes the selected asset from the graph                |
| **Reset**       | Yellow button | Completely resets the graph                              |
| **Show IP**     | Grey button   | Displays IP addresses on network assets                  |

---

## 2. Understanding the Filter Field

> ⚠️ **Critical point**: the Filter field has a dual effect that is essential to understand before exploring the graph.

### 2.1 Effect 1 — Restricting the "Object" dropdown list

This is the most intuitive use. By entering a view in the Filter field (e.g. `Applications`), the "Object" dropdown will
only display assets belonging to that view. This avoids having to search for an asset across the entire CMDB.

### 2.2 Effect 2 — Limiting asset visibility in the graph (a common pitfall)

This is the least expected effect, and the most frequent source of errors. **The filter does not simply restrict the "
Object" list: it also determines which asset types will be displayed in the graph during exploration.**

In practice: if you explore a `logical-server` with only `Logical Infrastructure` in the filter, any applications linked
to that server will **never** appear in the graph, even if they exist in Mercator and are correctly associated. They are
simply excluded because their type is not covered by the active filter.

**Illustrated example:**

| Active filter                             | Explored asset         | Result in the graph                                                                     |
|-------------------------------------------|------------------------|-----------------------------------------------------------------------------------------|
| `Logical Infrastructure`                  | `LOGICAL-SERVER-RH-11` | Visible: `NETWORK-CORE-11`, `SUBNET-CORE-11`, `SUBNET-VIRT-111` — but not `RH-Solution` |
| `Applications` + `Logical Infrastructure` | `LOGICAL-SERVER-RH-11` | Also visible: `RH-Solution` and `DB-RH-PROD`                                            |
| *(empty)*                                 | Any asset              | All linked assets are visible, across all layers                                        |

[<img src="/mercator/images/exploration_filtre_infra.png" width="700">](images/exploration_filtre_infra.png)
*With filter "Logical Infrastructure" only: RH-Solution does not appear.*

[<img src="/mercator/images/exploration_filtre_full.png" width="700">](images/exploration_filtre_full.png)
*With filters "Applications" + "Logical Infrastructure": RH-Solution and DB-RH-PROD appear.*

### 2.3 Practical rule: which filter should I use?

| Objective                                                 | Recommended filter                                 |
|-----------------------------------------------------------|----------------------------------------------------|
| Quickly find an asset in a specific view                  | Enter only the targeted view (e.g. `Applications`) |
| Cross-layer exploration (application + infrastructure)    | Enter **all** relevant views, or leave empty       |
| Full impact analysis (all layers)                         | **Leave the filter empty** to exclude nothing      |
| Exploration limited to a single layer (e.g. network only) | Enter only that layer's view                       |

> 💡 **Tip**: when in doubt about what you are looking for, always start with an **empty** filter. You can narrow it down
> afterwards if the graph becomes too dense.

---

## 3. Semantics of Directional Buttons

The direction is relative to the **Mercator layer hierarchy**, aligned with ArchiMate:

```
↑ UP    ═══════════════════════════════════════════════  toward 100 (Business)
         Layer 1: Entities, Processes, Actors       (100–260)
         Layer 2: Applications, Modules, DBs        (300–340)
         Layer 3: IAM, Active Directory             (400–460)
         Layer 4: Networks, VMs, Containers         (500–580)
         Layer 5: Physical, Sites, Racks            (600–675)
↓ DOWN  ═══════════════════════════════════════════════  toward 675 (Physical)
```

| Button     | Direction           | Meaning                                                                             | Example from `HR-Solution`                         |
|------------|---------------------|-------------------------------------------------------------------------------------|----------------------------------------------------|
| **↑ Up**   | Toward upper layers | Navigates up to the business layer, processes, and actors that use this asset       | `Application → HR Process → HR Director Actors`    |
| **↓ Down** | Toward lower layers | Navigates down to the infrastructure, servers, and networks that support this asset | `Application → VM → Physical Server → Rack → Site` |
| **↕ Both** | Bidirectional       | Full view: who uses this asset AND what it relies on                                | `Process ← Application → Server → Physical`        |

---

## 4. Step-by-Step Usage Procedure

```
Step 1 ─ Enter a filter (optional)
          └─ "Filter" field: e.g. "Applications"
             Restricts the object list to a specific view

Step 2 ─ Select the starting asset
          └─ "Object" dropdown: e.g. "HR-Solution"

Step 3 ─ Add the asset to the graph
          └─ Click the blue "Add" button
             The asset appears in the graph window

Step 4 ─ Select the asset in the graph
          └─ Click the asset icon in the graph area
             (it must be active / selected)

Step 5 ─ Choose the direction
          └─ Click: ↑ Up  |  ↓ Down  |  ↕ Both

Step 6 ─ Set the number of levels
          └─ Numeric selector: 1 to 5
             Start with 1 or 2 for highly connected assets

Step 7 ─ Deploy
          └─ Click the green "Deploy" button
             The graph is built with the relationships found

Step 8 ─ Iterate (optional)
          └─ Click another node in the graph
             Repeat from Step 4
```

---

## 5. Exploration Examples

### 5.1 Business Impact of an Application

> **Context:** Which business processes and actors depend on `HR-Solution`?

| Parameter | Value          |
|-----------|----------------|
| Filter    | `Applications` |
| Object    | `HR-Solution`  |
| Direction | **↑ Up**       |
| Levels    | `3`            |

**Expected result:**

```
HR-Solution
 └── APPLI-HR-MOD-1 (module)
      └── HR Management Process
           └── Actor: HR Department
```

You obtain the list of all processes and actors that functionally depend on this application.

---

### 5.2 Infrastructure Supporting an Application

> **Context:** What physical hardware does `HR-Solution` run on? (for DRP, ITIL impact analysis, CMDB)

| Parameter | Value          |
|-----------|----------------|
| Filter    | `Applications` |
| Object    | `HR-Solution`  |
| Direction | **↓ Down**     |
| Levels    | `3`            |

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

You obtain the complete chain from the application down to the physical site.

---

### 5.3 Full Impact Analysis (Before Maintenance / Incident)

> **Context:** Full view of ALL dependencies of `HR-Solution` before maintenance.

| Parameter | Value          |
|-----------|----------------|
| Filter    | `Applications` |
| Object    | `HR-Solution`  |
| Direction | **↕ Both**     |
| Levels    | `3`            |

**Expected result:**

```
         Macro-process: Personnel Management
              └── HR Process → HR Director Actor
                       ↑
              [HR-Solution]  ← central asset
                       ↓
         APPLI-HR-SRV-1 · APPLI-HR-MOD-1 · DB-HR-PROD
              └── Logical Servers → Physical → Racks → Site
```

Ideal for **architecture dossiers** and **impact analyses**.

---

### 5.4 Direction Selection by Use Case

| Use Case                              | Direction | Layers Traversed                               |
|---------------------------------------|-----------|------------------------------------------------|
| Who uses this application?            | ↑ Up      | Application → Process → Actors                 |
| What does this server run on?         | ↓ Down    | Logical Server → Physical → Rack → Site        |
| Full impact analysis                  | ↕ Both    | Business ↔ Application ↔ Infrastructure        |
| Which processes depend on a DB?       | ↑ Up      | Database → Application → Process               |
| Which network equipment under a VLAN? | ↓ Down    | VLAN → Switch → Physical Router                |
| AD domain mapping                     | ↕ Both    | AD Forest → Domain → Admin Zone → Servers      |
| Which assets does an actor use?       | ↓ Down    | Actor → Process → Application → Infrastructure |

---

## 6. BPMN — ArchiMate — Mercator Mapping

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
| `physical-servers` (615)     | —                 | Device                | Technology Component  |
| `sites` (600)                | —                 | Location              | Geography             |

### ArchiMate Relations in Mercator

| ArchiMate Relation | Exploration Direction | Mercator Example                              |
|--------------------|-----------------------|-----------------------------------------------|
| **Serving**        | ↑ Up                  | Application Service serves a Business Process |
| **Realization**    | ↕ Both                | Application realizes a Business Service       |
| **Assignment**     | ↓ Down                | Logical Server assigned to Physical Server    |
| **Composition**    | ↓ Down                | Site contains Buildings contains Bays         |
| **Association**    | ↕ Both                | Application associated to Database            |

---

## 7. Best Practices

### 7.1 Exploration Recommendations

**Start with level 1 or 2**
For highly connected assets (e.g., a central application), starting with 1 or 2 levels avoids an unreadable graph.
Increase progressively from there.

**Use the filter to target specific views**
The Filter field restricts available objects to a specific view, avoiding the need to search through the entire CMDB.

**Physical Mode**
Enable the Physical toggle only when you want to visualize physical network links (WAN/LAN/MAN). When disabled, the
exploration stays at the logical level.

### 7.2 Asset Entry Recommendations

- Follow Mercator numbering: create assets from the physical layer (600+) before associating them to logical layers.
- Physical containment relationships must be entered in order: **Site → Building → Bay → Physical Server**.
- A `logical-server` (VM) must always be linked to a `physical-server` for ↓ Down navigation to work correctly.
- `certificates` (570) are cross-cutting: associate them to both applications AND logical servers for complete
  exploration.
- `external-connected-entities` (540) can be linked to the business layer (partners) or the network layer (connections)
  depending on context.

### 7.3 Use Cases by User Profile

| Profile                  | Preferred Direction | Typical Use Case                                                |
|--------------------------|---------------------|-----------------------------------------------------------------|
| Enterprise Architect     | ↑ Up                | Tracing alignment between infrastructure and business processes |
| Infrastructure Architect | ↓ Down              | Identifying the physical chain supporting an application        |
| CISO / Risk Manager      | ↕ Both              | Mapping dependencies for risk analysis                          |
| CMDB Manager             | ↓ Down              | Verifying completeness of physical chaining                     |
| Crisis / DRP Manager     | ↕ Both              | Impact analysis before/after an incident                        |

---

## References

- [GitHub Mercator](https://github.com/dbarzin/mercator)
- [ArchiMate 3.1](https://www.opengroup.org/archimate-forum/archimate-overview)
- [TOGAF ADM](https://www.opengroup.org/togaf)
- [BPMN 2.0](https://www.omg.org/spec/BPMN/2.0/)
