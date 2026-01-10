# BPMN 2.0 Module

## Introduction

The BPMN (Business Process Model and Notation) module, available in the Enterprise version of Mercator, allows you to
create, edit, and visualize business process diagrams according to the BPMN 2.0 standard.

This module offers complete integration with information system mapping, enabling you to link
BPMN graphical elements to Mercator entities.

## Association with Mercator Cartography

BPMN diagrams enable business process modeling by linking them to Mercator cartography elements: processes,
macro-processes, activities, and actors.

These bidirectional links between BPMN diagrams and cartography objects provide complete traceability. A Mercator
process can be associated with one or more BPMN diagrams that detail its execution, while a BPMN diagram can reference
multiple processes, activities, or actors from the cartography.

This integration facilitates understanding of business flows by enabling navigation between the cartographic view (who
does what) and the process view (how it's done), while maintaining information consistency across the organization.

### Information System View

BPMN elements integrate with Mercator's information system view objects according to the following correspondences:

- **Activities**: Mercator business activities correspond to high-level BPMN sub-processes
- **Operations**: Detailed operations can be modeled as specific BPMN sub-processes
- **Tasks**: BPMN tasks are directly linked to operational tasks in the cartography
- **Actors**: Roles and actors are represented in lanes/pools, clearly identifying responsibilities
- **Information**: BPMN data objects can be associated with information from the ecosystem view

[<img src="/mercator/images/bpmn-1.png" width="500">](/mercator/images/bpmn-1.png)

This integration maintains consistency between business process modeling (BPMN) and the technical IS cartography.
It enables:

- Tracing data flows across the organization
- Identifying dependencies between the business view and technical infrastructure
- Aligning business processes with the technical components that support them

### BPMN Conversations

BPMN diagrams in Mercator support conversation elements as defined in the BPMN 2.0 specification. Conversation links
represent message exchanges between participants (pools), visually materialized by double parallel lines.

Conversations logically group multiple conversation links to model a coherent set of exchanges between participants.
They can be represented either as standard conversations (simple hexagon) for basic interactions, or as
sub-conversations
(double-bordered hexagon) when they encapsulate more complex exchanges requiring decomposition.

[<img src="/mercator/images/bpmn-2.png" width="400">](/mercator/images/bpmn-2.png)

This notation documents communication protocols between different business actors or systems, complementing the modeling
of internal processes for each participant. BPMN conversations thus provide an architectural view of
inter-organizational or inter-departmental interactions.

## Elements of the BPMN Diagram

The BPMN module offers a complete set of elements compliant with the BPMN 2.0 standard:

### Events

- **Start Event**: Starting point of a process
- **Intermediate Event**: Event occurring during the process
- **End Event**: Ending point of a process

[<img src="/mercator/images/bpmn-events.png" width="300">](/mercator/images/bpmn-events.png)

### Activities

- **Task**: Atomic unit of work
- **Sub-Process**: Process Decomposable into tasks
- **User task**: Task requiring human interaction
- **Service task**: Automated task
- **Script task**: Code execution

[<img src="/mercator/images/bpmn-tasks.png" width="500">](/mercator/images/bpmn-tasks.png)

### Gateways

- **Exclusive gateway**: Selection of a single path from several
- **Parallel gateway**: Simultaneous execution of multiple paths
- **Inclusive gateway**: Activation of one or more paths
- **Event gateway**: Waiting for multiple events

[<img src="/mercator/images/bpmn-gateways.png" width="400">](/mercator/images/bpmn-gateways.png)

### Flows and Connections

- **Sequence Flow**: Defines the order in which activities are executed within a process.

- **Message Flow**: Represents the communication between different participants (pools) in a process.

- **Conditional Flow**: A sequence flow accompanied by an activation condition.

- **Default Flow**: The sequence flow used when no condition in other outgoing flows is met.

- **Association**: A non-directional link connecting artifacts (annotations, data objects) to elements of the process.

-

[<img src="/mercator/images/bpmn-flows.png" width="400">](/mercator/images/bpmn-flows.png)

### Artifacts

- **Data Object**: Information representation
- **Data Store**: Data persistence location

[<img src="/mercator/images/bpmn-data.png" width="300">](/mercator/images/bpmn-data.png)

- **Annotation**: Comments and explanatory notes

[<img src="/mercator/images/bpmn-annotation.png" width="150">](/mercator/images/bpmn-annotation.png)

## Lanes

Lanes allow you to organize responsibilities within a process :

### Orientation

- **Horizontal Lanes**: Traditional organization, actors are listed vertically
- **Vertical Lanes**: Alternative organization, actors are listed horizontally

### Association with Actors

Each lane can be associated with an actor in the Mercator map:

- **Organizational Entities**: Departments, services, teams
- **Roles**: Functions and responsibilities
- **External Actors**: Partners, suppliers, etc.

## View and Navigation

### Visualization Mode

Visualization mode offers an interactive experience:

- **Click-to-navigate**: Clicking on an associated BPMN element redirects to the corresponding record in the
  map
- **Contextual information**: Hover over elements to display details
- **Zoom and pan**: Smooth navigation within complex diagrams
- **Overview**: Minimap for orientation within major processes

This bidirectional integration between BPMN and mapping facilitates impact analysis and traceability.

### Access from the Map

From the process, activity, and task records in Mercator, a button provides direct access to the
associated BPMN diagram.

## Conversations and Subprocesses

### Conversation Association

BPMN message flows represent conversations that can point to:

- **Other BPMN Diagrams**: Collaborative processes and inter-process exchanges
- **External Processes**: Interactions with third-party systems
- **Detailed Subprocesses**: Hierarchical navigation within complex processes

This feature allows you to model distributed process architectures while maintaining the readability of each diagram.

### Navigation Between Diagrams

Clicking on a message flow associated with another BPMN diagram opens that diagram, allowing for intuitive exploration
of interconnected processes.

## Import and Export

### BPMN 2.0 XML Format

**Import**:

- Full support for the BPMN 2.0 standard
- Preserves waypoints and positioning
- Retrieves existing associations if the identifiers match

**Export**:

- Generates compliant BPMN 2.0 files
- Preserves metadata and Mercator associations
- Compatible with standard BPMN tools (Camunda, Bonita, etc.)

### SVG Export

Exporting to SVG allows:

- **Documentation**: Insertion into Word and PDF documents
- **Presentation**: Vector quality for slides
- **Publication**: Integration into intranets and wikis
- **Archiving**: Open and long-lasting format

The generated SVG preserves the formatting, colors, and readability of the original diagram.

## Best Practices

### Modeling

1. **Simplicity**: Prioritize clarity over exhaustiveness
2. **Consistency**: Use the same naming conventions as the process map
3. **Systematic Association**: Link all BPMN elements to Mercator entities
4. **Documentation**: Use annotations for complex business rules

### Organization

1. **Structure**: Create subprocesses for workflows with more than 20 elements
2. **Lanes**: One lane per main actor; avoid excessive lane duplication
3. **Data**: Position data objects close to the activities that manipulate them
4. **Navigation**: Use conversations to link interconnected processes

### Maintenance

1. **Versioning**: Regularly export to BPMN 2.0 for historical data retention
2. **Review**: Synchronize changes with the process map
3. **Validation**: Verify that associations remain valid after Reorganization

4. **Archiving**: Preserve SVG exports of major versions

## Use Cases

### Impact Analysis

Quickly identify the systems and stakeholders impacted by a process change using associations.

### Compliance

Demonstrate traceability between business processes and technical controls for GDPR, ISO 27001, etc. audits.

### Onboarding

Facilitate new employees' understanding of the information system through visual diagrams linked to the detailed process
map.

### Digital Transformation

Map the current state (AS-IS) and model the target state (TO-BE) with visual comparison.

