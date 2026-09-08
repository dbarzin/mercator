# Les Cartographes dans Mercator

Les cartographes sont les responsables de la mise à jour et de la fiabilité de la cartographie du système d'information. Cette documentation explique leur rôle, leurs droits et les outils mis à leur disposition.

## Introduction — Qu'est-ce qu'un cartographe ?

Un **cartographe** est un utilisateur de Mercator à qui l'on a confié la responsabilité d'un ou plusieurs objets de la cartographie : un serveur, une application, un réseau, un site, etc.

Concrètement, être cartographe d'un objet signifie :

- être **responsable de la qualité et de l'exactitude** des informations qui le décrivent,
- avoir les **droits nécessaires** pour consulter et modifier cet objet,
- être **notifié** lorsqu'une mise à jour est attendue.

Le système des cartographes permet de **déléguer finement la maintenance** de la cartographie aux personnes les plus proches du terrain, sans leur ouvrir l'ensemble de la cartographie du système d'information.

!!! info "Un cartographe n'est pas un administrateur"
    Le rôle de cartographe est limité aux objets qui lui sont explicitement assignés. Il ne donne pas accès à l'ensemble de la cartographie ni aux fonctions d'administration de Mercator.

## Gestion des cartographes

### Qui peut assigner un cartographe ?

Seuls les utilisateurs disposant des permissions de gestion des **cartographes** au travers d'un rôle spécifique peuvent assigner des cartographes. Cette opération se réalise depuis l'interface de configuration de Mercator.

### Assigner un cartographe (interface d'administration)

L'assignation se fait depuis le menu **Configuration → Cartographes**.

Pour assigner un cartographe à un objet :

1. Accéder au menu **Administration → Cartographes**.
2. Cliquer sur **Nouveau**.
3. Sélectionner l'**utilisateur** (ou le **rôle**) à désigner comme cartographe.
4. Choisir le **type d'objet** concerné (ex. : Serveur logique, Application, Réseau…).
5. Sélectionner l'**objet** précis dans la liste.
6. Valider.

Il est possible d'assigner plusieurs cartographes au même objet, et un même utilisateur peut être cartographe de plusieurs objets.

!!! tip "Assigner un rôle plutôt qu'un utilisateur"
    Il est possible d'assigner un **rôle** comme cartographe d'un objet. Tous les utilisateurs ayant ce rôle bénéficieront alors des mêmes droits sur cet objet. Cela facilite la gestion lors des changements d'équipe.

## Ce que permet le rôle de cartographe

### Accès en lecture à ses objets

Un cartographe peut **consulter** tous les objets qui lui sont assignés, même si son rôle habituel ne lui donne pas accès à cette catégorie d'objets.

Par exemple, un utilisateur sans accès aux réseaux pourra consulter les fiches des réseaux dont il est cartographe.

### Modification de ses objets

Un cartographe peut **modifier** les fiches des objets qui lui sont assignés : mise à jour des informations, ajout de relations, correction de données…

Les modifications sont tracées dans le journal d'audit de Mercator, comme toute autre modification.

### Ce qui reste hors de portée

Le rôle de cartographe **ne donne pas accès** à :

- la création ou la suppression d'objets (sauf si l'utilisateur dispose d'un rôle le permettant par ailleurs),
- les objets qui ne lui ont pas été explicitement assignés,
- les fonctions d'administration de Mercator (gestion des utilisateurs, des rôles, de la configuration…),
- les rapports et exports globaux réservés aux administrateurs.

## Visualiser ses objets assignés

### Tableau de bord

Le tableau de bord de Mercator affiche uniquement les objets auxquels l'utilisateur a accès grâce à son rôle, ainsi que les objets dont il est cartographe. Le tableau de bord reflète ainsi naturellement le périmètre du cartographe, sans configuration supplémentaire.

### Liste des objets du cartographe

Les cartographes ont accès à la liste consolidée des objets dont ils sont responsables via le menu **Préférences → Cartographie**. Cette liste regroupe tous les objets assignés, quel que soit leur type, et permet d'accéder directement à chaque fiche et historiques des changements.

!!! info "Menu visible uniquement si des objets sont assignés"
    Le menu **Préférences → Cartographie** n'apparaît que si au moins un objet a été assigné à l'utilisateur. Il est invisible pour les utilisateurs sans responsabilité de cartographie.

### Filtrage dans les listes

Dans les listes d'objets (serveurs, applications, réseaux…), chaque cartographe ne voit que les objets auxquels il a accès via son rôle et ceux dont il est cartographe. Ce filtrage est automatique et transparent : il n'y a rien à activer.

## Recevoir des notifications

Mercator peut envoyer des notifications aux cartographes par e-mail pour les tenir informés de l'état de leurs objets et des modifications qui y sont apportées.

### Rappels périodiques pour maintenir la cartographie à jour

Un rappel périodique peut être configuré par l'administrateur pour inciter les cartographes à vérifier et mettre à jour leurs fiches. Ce rappel indique :

- les objets dont le cartographe est responsable,
- un lien direct vers chaque fiche dans Mercator.

La fréquence des rappels (hebdomadaire, mensuelle…) et le contenu du message sont définis par l'administrateur dans **Administration → Configuration → Notifications**. L'administrateur peut également renseigner une adresse de **copie cachée** : chaque rappel envoyé à un cartographe y est alors également transmis en copie cachée (Cci).

!!! note "Pas de rappel si aucune configuration n'est définie"
    Les rappels ne sont envoyés que si l'administrateur a activé et configuré cette fonctionnalité. En l'absence de configuration, aucun e-mail n'est envoyé.

### Notification lors d'une modification par un autre cartographe

Lorsqu'un objet est modifié par un utilisateur autre que vous, Mercator peut vous envoyer une notification pour vous en informer. Cette notification précise :

- quel objet a été modifié,
- quels champs ont changé,
- qui a effectué la modification.

Cela permet à plusieurs cartographes d'un même objet de rester synchronisés.

## Bonnes pratiques de cartographie

### Maintenir ses fiches à jour

La valeur d'une cartographie repose entièrement sur la **fiabilité et la fraîcheur des données**. En tant que cartographe, il est recommandé de :

- vérifier régulièrement les informations de ses objets, notamment après un changement en production,
- compléter les champs vides dès que l'information est disponible,
- ne pas attendre le rappel automatique pour effectuer une mise à jour si un changement est connu.

### Signaler une anomalie ou un changement

Si un objet dont vous êtes cartographe évolue significativement (décommissionnement, changement de nom, migration…), il est important de :

- mettre à jour la fiche dans Mercator sans délai,
- contacter l'administrateur si une action hors de votre portée est nécessaire (suppression, fusion d'objets, changement de catégorie…).

!!! warning "Un objet obsolète est une fausse information"
    Une fiche non mise à jour peut induire en erreur d'autres utilisateurs ou fausser les analyses de sécurité. La mise à jour est une responsabilité à part entière du cartographe.


