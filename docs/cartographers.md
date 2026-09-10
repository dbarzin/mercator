# Cartographers in Mercator

Cartographers are responsible for keeping the information system's cartography up to date and reliable. This documentation explains their role, their rights, and the tools available to them.

## Introduction — What is a cartographer?

A **cartographer** is a Mercator user who has been given responsibility for one or more objects in the cartography: a server, an application, a network, a site, etc.

Concretely, being the cartographer of an object means:

- being **responsible for the quality and accuracy** of the information describing it,
- having the **necessary rights** to view and modify that object,
- being **notified** when an update is expected.

The cartographer system allows the maintenance of the cartography to be **finely delegated** to the people closest to the field, without giving them access to the entire information system cartography.

!!! info "A cartographer is not an administrator"
    The cartographer role is limited to the objects explicitly assigned to it. It does not grant access to the entire cartography or to Mercator's administration functions.

## Managing cartographers

### Who can assign a cartographer?

Only users with cartographer management permissions, granted through a specific role, can assign cartographers. This operation is performed from Mercator's configuration interface.

### Assigning a cartographer (administration interface)

Assignment is done from the **Configuration → Cartographers** menu.

To assign a cartographer to an object:

1. Go to the **Administration → Cartographers** menu.
2. Click **New**.
3. Select the **user** (or **role**) to designate as cartographer.
4. Choose the **type of object** concerned (e.g., logical server, application, network…).
5. Select the specific **object** from the list.
6. Confirm.

It is possible to assign several cartographers to the same object, and a single user can be cartographer of several objects.

!!! tip "Assigning a role rather than a user"
    It is possible to assign a **role** as the cartographer of an object. All users with that role will then benefit from the same rights on that object. This simplifies management when team changes occur.

## What the cartographer role allows

### Read access to their objects

A cartographer can **view** all the objects assigned to them, even if their usual role does not give them access to that category of objects.

For example, a user without access to networks will be able to view the records of the networks for which they are cartographer.

### Modifying their objects

A cartographer can **modify** the records of the objects assigned to them: updating information, adding relationships, correcting data…

Changes are logged in Mercator's audit trail, like any other change.

### What remains out of scope

The cartographer role **does not grant access** to:

- creating or deleting objects (unless the user has a role that otherwise allows it),
- objects that have not been explicitly assigned to them,
- Mercator's administration functions (user management, role management, configuration…),
- global reports and exports reserved for administrators.

## Viewing assigned objects

### Dashboard

Mercator's dashboard only displays objects the user has access to through their role, as well as objects for which they are cartographer. The dashboard thus naturally reflects the cartographer's scope, with no additional configuration required.

### List of the cartographer's objects

Cartographers have access to the consolidated list of objects they are responsible for via the **Preferences → Cartography** menu. This list gathers all assigned objects, regardless of type, and provides direct access to each record and its change history.

!!! info "Menu visible only if objects are assigned"
    The **Preferences → Cartography** menu only appears if at least one object has been assigned to the user. It is invisible to users with no cartography responsibility.

### Filtering in lists

In object lists (servers, applications, networks…), each cartographer only sees the objects they have access to through their role and those for which they are cartographer. This filtering is automatic and transparent: there is nothing to activate.

## Receiving notifications

Mercator can send notifications to cartographers by email to keep them informed of the status of their objects and the changes made to them.

### Periodic reminders to keep the cartography up to date

A periodic reminder can be configured by the administrator to encourage cartographers to check and update their records. This reminder indicates:

- the objects for which the cartographer is responsible,
- a direct link to each record in Mercator.

The frequency of reminders (weekly, monthly…) and the content of the message are defined by the administrator in **Administration → Configuration → Notifications**. The administrator can also set a **hidden copy** (Bcc) address: every reminder sent to a cartographer is then also sent as a blind carbon copy to that address.

!!! note "No reminder if no configuration is set"
    Reminders are only sent if the administrator has enabled and configured this feature. In the absence of configuration, no email is sent.

### Notification when modified by another cartographer

When an object is modified by a user other than you, Mercator can send you a notification to inform you. This notification specifies:

- which object was modified,
- which fields changed,
- who made the change.

This allows several cartographers of the same object to stay in sync.

## Cartography best practices

### Keeping records up to date

The value of a cartography rests entirely on the **reliability and freshness of the data**. As a cartographer, it is recommended to:

- regularly check the information of your objects, especially after a change in production,
- fill in empty fields as soon as the information is available,
- not wait for the automatic reminder to make an update if a change is already known.

### Reporting an anomaly or a change

If an object for which you are cartographer changes significantly (decommissioning, name change, migration…), it is important to:

- update the record in Mercator without delay,
- contact the administrator if action beyond your scope is required (deletion, merging of objects, category change…).

!!! warning "An outdated object is false information"
    A record that is not kept up to date can mislead other users or distort security analyses. Keeping it up to date is a full responsibility of the cartographer.
