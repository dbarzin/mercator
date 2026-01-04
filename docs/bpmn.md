# BPMN Module

## Introduction

The BPMN (Business Process Model and Notation) module, available in the Enterprise version of Mercator,
allows you to create, edit, and visualize business process diagrams according to the BPMN 2.0 standard.

This module offers complete integration with information system mapping, enabling you to link
BPMN graphical elements to Mercator entities.

## Elements of the BPMN Diagram

The BPMN module offers a complete set of elements compliant with the BPMN 2.0 standard:

### Events

- **Start Event**: Starting point of a process
- **Intermediate Event**: Event occurring during the process
- **End Event**: Ending point of a process

[<img src="/mercator/images/bpmn-events.png" width="300">](/mercator/images/bpmn-events.png)

### Activities

- **Task**: Atomic unit of work
- **Sub-Process**: Process that can be broken down into tasks
- **User Task**: Task requiring human interaction
- **Service Task**: Automated task
- **Script Task**: Code execution

[<img src="/mercator/images/bpmn-tasks.png" width="500">](/mercator/images/bpmn-tasks.png)

### Gateways

- **Exclusive Gateway**: Chooses only one path from several
- **Parallel Gateway**: Executes multiple paths simultaneously
- **Inclusive Gateway**: Activates one or more paths
- **Event Gateway**: Waits for multiple events

[<img src="/mercator/images/bpmn-gateways.png" width="400">](/mercator/images/bpmn-gateways.png)

### Flows and Connections

- **Sequence Flow**: Defines the order in which activities are executed within a process.

- **Message Flow**: Represents the communication between different participants (pools) in a process.

- **Conditional Flow**: A sequence flow accompanied by an activation condition.

- **Default Flow**: The sequence flow used when no condition of the other outgoing flows is met.

- **Association**: A non-directional link connecting artifacts (annotations, data objects) to elements of the process.

[<img src="/mercator/images/bpmn-flows.png" width="400">](/mercator/images/bpmn-flows.png)

### Artifacts

- **Data Object**: Information representation
- **Data Store**: Data persistence location

[<img src="/mercator/images/bpmn-data.png" width="300">](/mercator/images/bpmn-data.png)

- **Annotation**: Comments and explanatory notes

[<img src="/mercator/images/bpmn-annotation.png" width="150">](/mercator/images/bpmn-annotation.png)

## Association with Mercator Mapping

### Business Processes

BPMN elements can be associated with the following entities From the mapping:

- **Processes**: A BPMN diagram represents a Mercator business process.
- **Activities**: BPMN subprocesses correspond to the activities in the mapping.
- **Tasks**: BPMN tasks are linked to the operational tasks referenced in Mercator.

[<img src="/mercator/images/bpmn-1.png" width="500">](/mercator/images/bpmn-1.png)

This hierarchy ensures consistency between the BPMN model and the IS mapping.

### Data Objects

BPMN data objects can be associated with information in the ecosystem view.

This association allows you to trace data flows and identify dependencies between processes and systems.

## Lanes

Lanes allow you to organize responsibilities within a process:

### Orientation

- **Horizontal Lanes**: Traditional organization, actors are listed vertically
- **Vertical Lanes**: Alternative organization, actors are listed horizontally

### Association with Actors

Each lane can be associated with an actor in the Mercator map:

- **Organizational Entities**: Departments, services, teams
- **Roles**: Functions and responsibilities
- **External Actors**: Partners, suppliers, customers

Associating lanes with actors allows you to clearly identify responsibilities and
automatically generate RACI matrices.

## BPMN Annotations

Annotations enrich process documentation:

- **Explanatory Notes**: Clarifications on complex steps
- **Business Rules**: Applicable conditions and constraints
- **References**: Links to external documents or standards
- **Comments**: Remarks for maintenance and improvement

Annotations can be freely positioned on the diagram and linked to relevant elements via associations.

## View and Navigation

### Visualization Mode

Visualization mode offers an interactive experience:

- **Click-to-Navigation**: Clicking on an associated BPMN element redirects to the corresponding record in the diagram
- **Contextual Information**: Hovering over elements displays