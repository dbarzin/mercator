
# Requêtes

Les requêtes Mercator permettent d'explorer et de visualiser les données de votre cartographie de manière flexible, sans passer par l'interface standard. Elles s'écrivent dans un langage déclaratif inspiré du SQL et peuvent produire une **liste** ou un **graphe**.

---

## Format d'une requête

Une requête Mercator suit la syntaxe suivante :

```sql
FROM    <Modèle>
FIELDS  <champ1>, <champ2>, <relation.champ>, ...
WHERE   (<condition1>) AND|OR (<condition2>)
WITH    <relation1>, <relation2>, ...
OUTPUT  list | graph
LIMIT   <n>
```

| Clause | Obligatoire | Description                                                                       |
|--------|------------|-----------------------------------------------------------------------------------|
| `FROM` | ✅ | Modèle de données cible (voir [Modèles disponibles](#modeles-disponibles))        |
| `FIELDS` | ➖ | Liste des champs à afficher, y compris les champs de relations (`relation.champ`) |
| `WHERE` | ➖ | Filtre sur les données (voir [Conditions](#conditions))                           |
| `WITH` | ➖ | Relations à charger en eager loading                                              |
| `OUTPUT` | ➖ | Format de sortie : `list` ou `graph` (`list` par défaut)                          |
| `LIMIT` | ➖ | Nombre maximum d'enregistrements retournés (défaut : 100)                         |

---

## Modèles disponibles

Les modèles correspondent aux entités de l'API Mercator. Les noms sont en **PascalCase** et identiques aux noms de ressources exposés par l'API REST.

| Modèle             | Description |
|--------------------|-------------|
| `logical-servers`  | Serveurs logiques |
| `physical-servers` | Serveurs physiques |
| `applications`     | Applications |
| `databases`        | Bases de données |
| `certificates`     | Certificats SSL/TLS |
| `networks`         | Réseaux / sous-réseaux |
| `storage-devices`  | Dispositifs de stockage |
| `sites`            | Sites physiques |
| `bays`             | Baies d'hébergement |
| …                  | _Tous les modèles de l'API_ |

!!! info "Champs disponibles"
    Les champs utilisables dans `FIELDS` et `WHERE` sont exactement ceux exposés par l'API Mercator. Consultez la [référence de l'API](api.fr.md) pour connaître l'ensemble des attributs de chaque modèle.

---

## Clause FIELDS

La clause `FIELDS` liste les attributs à afficher dans le résultat. Elle accepte :

- Les **champs directs** du modèle : `name`, `cpu`, `environment`, `end_validity`, …
- Les **champs de relations** au format `relation.champ` : `applications.name`, `site.name`, `databases.name`, …

```sql
FIELDS name, operating_system, cpu, memory, applications.name
```

!!! warning "Cohérence avec WITH"
    Si vous référencez un champ de relation dans `FIELDS` (ex. `applications.name`), la relation correspondante doit être déclarée dans `WITH` (ex. `WITH applications`), sans quoi les données ne seront pas chargées.

---

## Clause WHERE

La clause `WHERE` filtre les enregistrements selon des conditions sur les champs du modèle principal.

### Opérateurs supportés

| Opérateur | Syntaxe | Exemple |
|-----------|---------|---------|
| Égalité | `=` | `environment = "production"` |
| Inégalité | `!=` | `type != "virtual"` |
| Comparaison | `<`, `>`, `<=`, `>=` | `memory >= 16` |
| Recherche | `LIKE` | `operating_system LIKE "%Linux%"` |
| Liste de valeurs | `IN` | `environment IN ("production", "staging")` |

### Combinaisons logiques

Les conditions peuvent être combinées avec `AND` et `OR`. Chaque condition doit être encadrée par des parenthèses :

```sql
WHERE (environment = "production") AND (operating_system LIKE "%Linux%")
```

```sql
WHERE (environment IN ("production", "staging")) AND (operating_system LIKE "%Windows%")
```

---

## Clause WITH

La clause `WITH` déclare les **relations à charger** (eager loading). Elle est indispensable pour accéder aux champs d'objets liés dans `FIELDS`.

```sql
WITH applications, databases, certificates
```

Les noms de relations correspondent aux noms des méthodes de relation des modèles Eloquent, généralement en **camelCase** :

```sql
WITH logicalServers, databases, site, bay
```

---

## Format de sortie (OUTPUT)

### `OUTPUT list`

Génère un **tableau** avec une ligne par enregistrement. C'est le format adapté pour les inventaires, les exports, ou les vues tabulaires.

```sql
OUTPUT list
```

### `OUTPUT graph`

Génère un **graphe de relations** entre les entités retournées. C'est le format adapté pour visualiser les dépendances, les cartographies applicatives ou les relations réseau.

```sql
OUTPUT graph
```

!!! tip "Quand utiliser `graph` ?"
    Préférez `OUTPUT graph` dès que votre requête charge des relations avec `WITH` et que vous souhaitez visualiser les liens entre entités (applications ↔ serveurs, réseaux ↔ serveurs, etc.).

---

## Partage d'une requête

Il est possible de **sauvegarder des requêtes** dans l'interface pour les retrouver et les réexécuter sans les ressaisir. Les requêtes sauvegardées peuvent être rendues publiques (visibles par tous les utilisateurs) ou privées (visibles uniquement par leur auteur).

---

## Exemples

### Serveurs Linux en production avec leurs applications

```sql
FROM logical-servers
FIELDS name, operating_system, environment, cpu, memory, applications.name
WHERE (environment = "production") AND (operating_system LIKE "%Linux%")
WITH applications
```

Retourne la liste des serveurs logiques sous Linux en environnement de production, avec le nom des applications hébergées.

---

### Toutes les applications et leurs bases de données

```sql
FROM applications
FIELDS name, description, databases.name, logicalServers.name
WITH databases, logical-servers
OUTPUT graph
```

Génère un graphe reliant les applications à leurs bases de données et serveurs logiques.

---

### Inventaire des serveurs physiques

```sql
FROM physical-servers
FIELDS name, type, cpu, memory, site.name, bay.name
WITH site, bay
```

Liste complète des serveurs physiques avec leur localisation (site et baie).

---

### Réseaux, sous-réseaux et VLANs

```sql
FROM networks
Fields name, subnetworks.name, subnetworks.vlan.id, subnetworks.vlan.name
WITH subnetworks, subnetworks.vlan
```

Visualizes les réseaux, sous-réseaux et leurs VLANs.

---

### Filtres multiples avec `IN`

```sql
FROM logical-servers
FIELDS applications.name, certificates.name
WHERE (environment IN ("production", "staging")) AND (operating_system LIKE "%Windows%")
WITH applications, certificates
```

Liste les applications et certificats installés sur les serveurs Windows en production ou en staging.

---

### Certificats SSL avec date d'expiration et périmètre d'installation

```sql
FROM certificates
FIELDS name, type, end_validity, domains, logical_servers.name, applications.name
WITH logical_servers, applications
```

Inventaire des certificats SSL/TLS avec leur date d'expiration et les serveurs/applications sur lesquels ils sont déployés. Utile pour anticiper les renouvellements.

---

### Applications critiques avec leurs serveurs et bases de données

```sql
FROM applications
FIELDS name, security_need_c, description, responsible, logical_servers.name, databases.name
WHERE (security_need_c IN ("3", "4"))
WITH logical_servers, databases
OUTPUT graph
```

Cartographie des applications à fort besoin de confidentialité (niveaux 3 et 4), avec leurs dépendances d'infrastructure.

---

## Bonnes pratiques

- **Limitez le `LIMIT`** à la valeur nécessaire : des requêtes trop larges peuvent être lentes sur de grands référentiels.
- **Utilisez `OUTPUT graph`** uniquement lorsque les relations sont déclarées dans `WITH` ; un graphe sans relations ne sera composé que de nœuds isolés.
- **Vérifiez les noms de champs** dans la [référence API](api.fr.md) — une faute de frappe dans un champ n'affiche simplement rien, sans message d'erreur.
- **Sauvegardez les requêtes récurrentes** pour faciliter le travail en équipe et garantir la reproductibilité des cartographies.

