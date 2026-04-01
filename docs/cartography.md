# Cartography / Views

🇫🇷 [Lire en français](/mercator/cartography_fr)

The information system cartography is organized into several complementary views, progressing gradually from business to
technical concerns. Rather than managing siloed inventories, Mercator places all objects into a dependency graph:
instead of isolated lists, it manages relationships between objects. This makes it possible to identify critical paths,
spot strategic suppliers, and understand what depends on what.

### GDPR View

The GDPR view maintains the processing register and establishes links between processes, information assets,
applications, and the security measures in place.

### Business View

The ecosystem view presents the various entities — suppliers, partners, sub-parts of the organization — with which the
IS interacts, along with the relationships they maintain: support contracts, partnerships, memberships.

The business view of the information system represents the IS through its macro-processes, processes, activities,
operations, actors, and the information they handle. These elements constitute the business values in the sense of the
EBIOS Risk Manager risk assessment method.

### Application View

The application view describes the software components of the information system: applications grouped into application
blocks, databases, services and modules, along with their links to the business processes they support.

The application flow view describes the information exchanges between the various applications, services, modules, and
databases.

### Administration View

The administration view lists the scopes and privilege levels of users and administrators, as well as the directories
that reference them.

### Logical View

The logical infrastructure view illustrates the logical segmentation of networks: IP address ranges, VLANs, filtering
and routing functions. It notably makes it possible to compare what the system is *capable* of doing with what it was
*authorized* to do.

### Infrastructure View

The physical infrastructure view describes the physical equipment that makes up the information system: servers, racks,
server rooms, buildings, and sites.

---

## Exploring Views and Rendering Engines

Mapping objects can be explored organically: double-clicking on an object immediately displays all objects connected to
it—physically or logically—and all incoming and outgoing flows.

Hierarchical views are also available. From a macro-process, you can visualize all the processes, activities, and
operations that depend on it, or take a site and display all the rooms and equipment it contains.

You can select the graphic rendering engine directly from each view. **Dot, Neato, FDP, Sfdp, Twopi, Circo** — each
Graphviz engine produces a different rendering depending on the nature and density of the graph. This flexibility allows
you to optimize readability according to the context: dense network mapping, application view, or multi-layered impact
analysis. A simple setting for very tangible visual comfort.
