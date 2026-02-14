## API avancé (filtres)

Ce chapitre vient en complément du chapitre [API](./api.fr.md).


### Filtrage, tri et inclusion de relations

Les endpoints de liste (`GET /api/objets`) supportent un système avancé de filtres, tri et inclusion de relations via
les paramètres de requête.

#### Champs filtrables

Tous les champs déclarés dans le modèle (`$fillable`) sont automatiquement filtrables. Les filtres sur des champs non
autorisés sont simplement ignorés.

#### Syntaxe des filtres

Chaque filtre se présente sous la forme :

```text
filter[<champ>]=<valeur>                    # Filtre exact
filter[<champ>_<operateur>]=<valeur>        # Filtre avec opérateur
```

#### Opérateurs de filtrage disponibles

##### Filtres exacts et textuels

| Opérateur | Syntaxe               | Description                  | Exemple                |
|-----------|-----------------------|------------------------------|------------------------|
| (aucun)   | `filter[name]=Backup` | Égalité exacte               | `name = 'Backup'`      |
| (partiel) | `filter[name]=backup` | Recherche partielle (LIKE) * | `name LIKE '%backup%'` |

_* La recherche partielle automatique s'applique aux champs contenant : `name`, `description`, `email`_

##### Comparaisons numériques

| Opérateur | Syntaxe                                 | Description         | Exemple SQL                    |
|-----------|-----------------------------------------|---------------------|--------------------------------|
| `_lt`     | `filter[recovery_time_objective_lt]=4`  | Inférieur à         | `recovery_time_objective < 4`  |
| `_lte`    | `filter[recovery_time_objective_lte]=4` | Inférieur ou égal à | `recovery_time_objective <= 4` |
| `_gt`     | `filter[recovery_time_objective_gt]=8`  | Supérieur à         | `recovery_time_objective > 8`  |
| `_gte`    | `filter[recovery_time_objective_gte]=8` | Supérieur ou égal à | `recovery_time_objective >= 8` |

##### Opérateurs avancés

| Opérateur  | Syntaxe                                        | Description           | Exemple                                    |
|------------|------------------------------------------------|-----------------------|--------------------------------------------|
| `_in`      | `filter[id_in]=1,2,3`                          | Valeur dans une liste | `id IN (1, 2, 3)`                          |
| `_between` | `filter[recovery_time_objective_between]=4,24` | Entre deux valeurs    | `recovery_time_objective BETWEEN 4 AND 24` |
| `_null`    | `filter[deleted_at_null]=true`                 | Valeur NULL           | `deleted_at IS NULL`                       |
| `_null`    | `filter[description_null]=false`               | Valeur NOT NULL       | `description IS NOT NULL`                  |
| `_not`     | `filter[type_not]=opensource`                  | Négation (différent)  | `type != 'opensource'`                     |

##### Recherche globale et filtres sur relations

| Opérateur  | Syntaxe                       | Description                                                          | Exemple                                               |
|------------|-------------------------------|----------------------------------------------------------------------|-------------------------------------------------------|
| `search`   | `filter[search]=backup`       | Recherche OR sur tous les champs textuels (name, description, email) | `name LIKE '%backup%' OR description LIKE '%backup%'` |
| (relation) | `filter[actors.email]=john`   | Recherche LIKE sur champs de relations                               | `actors.email LIKE '%john%'`                          |
| (relation) | `filter[contacts.name]=alice` | Recherche LIKE sur nom dans une relation                             | `contacts.name LIKE '%alice%'`                        |

##### Filtres de dates

Pour les champs contenant `date` ou `at` dans leur nom :

| Opérateur | Syntaxe                                | Description      | Exemple                      |
|-----------|----------------------------------------|------------------|------------------------------|
| `_after`  | `filter[created_at_after]=2024-01-01`  | Après cette date | `created_at >= '2024-01-01'` |
| `_before` | `filter[updated_at_before]=2024-12-31` | Avant cette date | `updated_at <= '2024-12-31'` |

##### Elements supprimés

| Syntaxe                | Description                       |
|------------------------|-----------------------------------|
| `filter[trashed]=with` | Inclure les éléments supprimés    |
| `filter[trashed]=only` | Uniquement les éléments supprimés |

#### Tri des résultats

Le tri se fait avec le paramètre `sort` :

```text
sort=<champ>        # Tri ascendant
sort=-<champ>       # Tri descendant
```

Exemples :

- `sort=name` : Tri par nom croissant
- `sort=-created_at` : Tri par date de création décroissant
- `sort=recovery_time_objective` : Tri par RTO croissant

#### Inclusion de relations (Eager Loading)

Les relations entre objets peuvent être chargées avec le paramètre `include` :

```text
include=<relation1>,<relation2>
```

Les relations disponibles sont automatiquement détectées depuis le modèle. Pour les activités, par exemple :

- `processes` : Processus liés
- `operations` : Opérations liées
- `applications` : Applications liées
- `impacts` : Impacts liés

Exemple : `include=processes,operations`

#### Cas d'usage

##### Filtres simples

```http
# Activités dont le nom contient "sauvegarde"
GET /api/activities?filter[name]=sauvegarde

# Activités avec un RTO inférieur ou égal à 4 heures
GET /api/activities?filter[recovery_time_objective_lte]=4

# Activités avec un ID supérieur ou égal à 100
GET /api/activities?filter[id_gte]=100

# Applications dont le type est différent de "opensource"
GET /api/applications?filter[type_not]=opensource

# Recherche globale sur tous les champs textuels
GET /api/applications?filter[search]=backup

# Recherche sur l'email d'un acteur lié
GET /api/activities?filter[actors.email]=john@example.com
```

##### Filtres combinés

```http
# Activités dont le nom contient "GDPR" avec RTO >= 8
GET /api/activities?filter[name]=GDPR&filter[recovery_time_objective_gte]=8

# Serveurs créés après le 1er janvier 2024 avec un nom contenant "prod"
GET /api/logical-servers?filter[created_at_after]=2024-01-01&filter[name]=prod

# Applications avec type différent de "opensource" triées par nom
GET /api/applications?filter[type_not]=opensource&sort=name

# Recherche globale "backup" sur toutes les activités avec RTO <= 8
GET /api/activities?filter[search]=backup&filter[recovery_time_objective_lte]=8

# Activités liées à un acteur dont l'email contient "admin"
GET /api/activities?filter[actors.email]=admin&include=actors
```

##### Filtres avancés

```http
# Activités avec ID dans la liste 1, 2, 3, 5
GET /api/activities?filter[id_in]=1,2,3,5

# Activités avec RTO entre 4 et 24 heures
GET /api/activities?filter[recovery_time_objective_between]=4,24

# Activités sans description
GET /api/activities?filter[description_null]=true

# Inclure les activités supprimées
GET /api/activities?filter[trashed]=with
```

##### Tri

```http
# Tri par nom croissant
GET /api/activities?sort=name

# Tri par date de création décroissant
GET /api/activities?sort=-created_at

# Tri par RTO croissant
GET /api/activities?sort=recovery_time_objective
```

##### Inclusion de relations

```http
# Charger les processus liés
GET /api/activities?include=processes

# Charger plusieurs relations
GET /api/activities?include=processes,operations,applications

# Activité spécifique avec ses relations
GET /api/activities/1?include=processes,operations
```

##### Combinaisons complexes

```http
# Activités GDPR avec RTO >= 8, triées par nom, avec relations
GET /api/activities?filter[name]=GDPR&filter[recovery_time_objective_gte]=8&include=processes,operations&sort=name

# Serveurs logiques créés en 2024, sans description, avec tri
GET /api/logical-servers?filter[created_at_after]=2024-01-01&filter[created_at_before]=2024-12-31&filter[description_null]=true&sort=-created_at

# Applications avec plusieurs critères
GET /api/applications?filter[name]=CRM&filter[id_in]=1,2,3&include=databases&sort=-updated_at
```

### Droits d'accès

Il faut s'identifier avec un utilisateur de l'application Mercator pour pouvoir accéder aux API.
Cet utilisateur doit disposer d'un rôle dans Mercator qui lui permet d'accéder / modifier les objets
accédés par l'API.

Lorsque l'authentification réussit, l'API envoie un "access_token" qui doit être passé dans
l'entête "Authorization" de la requête de l'API.

### Liaison entre les objets

Les objets de la cartographie peuvent faire référence à d'autres objets. Par exemple, nous pouvons lier un processus à
une application. Supposons que nous ayons un "processus" qui utilise deux applications "app1" et "app2". Pour ce faire,
nous suivons ces étapes :

- Étape 1 : Assurez-vous d'avoir l'application_id pour les applications que vous souhaitez lier.

```json
{
  "id": 201,
  "name": "app1",
  "description": "desc1"
}
{
  "id": 202,
  "name": "app2",
  "description": "desc2"
}
```

- Étape 2 : Liez le processus aux applications. Soit avec une mise à jour, soit avec un enregistrement, nous pouvons
  ajouter :

```json
{
  "id": 101,
  "name": "processus",
  "applications": [
    201,
    202
  ]
}
```

Les noms de toutes les relations disponibles sont automatiquement détectés depuis le modèle. Les relations courantes
incluent : `actors`, `tasks`, `activities`, `entities`, `applications`, `informations`, `processes`, `databases`,
`logical_servers`, `modules`, `operations`, `certificates`, `peripherals`, `physical_servers`, etc.

### Exemples de filtres

Voici quelques exemples d'utilisation de l'API avec PHP :

#### PHP

##### Authentification

```php
<?php
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query(
            array("login" => "admin@admin.com",
                  "password" => "password")),
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/x-www-form-urlencoded",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $info = curl_getinfo($curl);

    curl_close($curl);

    if ($err) {
        set_error_handler($err);
    } else {
        if ($info['http_code'] == 200) {
            $access_token = json_decode($response)->access_token;
        } else {
            set_error_handler("Login to api failed status 403");
            error_log($responseInfo['http_code']);
            error_log("No login api status 403");
        }
    }

    var_dump($response);
```

##### Liste des activités avec filtres

```php
<?php
    $curl = curl_init();

    // Construire l'URL avec les paramètres de filtrage
    $filters = http_build_query([
        'filter' => [
            'recovery_time_objective_gte' => 8,
            'name' => 'GDPR'
        ],
        'sort' => '-created_at',
        'include' => 'processes,operations'
    ]);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/activities?" . $filters,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: Bearer " . $access_token,
            "cache-control: no-cache",
            "content-type: application/json",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    var_dump($response);
```

##### Récupérer une activité avec relations

```php
<?php
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/activities/1?include=processes,operations",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: Bearer " . $access_token,
            "cache-control: no-cache",
            "content-type: application/json",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    var_dump($response);
```

#### Python

Voici un exemple d'utilisation de l'API en Python avec filtres avancés

```python
#!/usr/bin/python3

import requests

vheaders = {}
vheaders['accept'] = 'application/json'
vheaders['content-type'] = 'application/x-www-form-urlencoded'
vheaders['cache-control'] = 'no-cache'

print("Login")
response = requests.post("http://127.0.0.1:8000/api/login",
    headers=vheaders,
    data={'login':'admin@admin.com', 'password':'password'})
print(response.status_code)

vheaders['Authorization'] = "Bearer " + response.json()['access_token']

# Liste avec filtres et tri
print("Get workstations with filters")
params = {
    'filter[name]': 'laptop',
    'sort': '-created_at'
}
response = requests.get("http://127.0.0.1:8000/api/workstations", 
    headers=vheaders, 
    params=params)
print(response.json())
print(response.status_code)

# Récupération avec relations
print("Get activity with relations")
params = {
    'include': 'processes,operations'
}
response = requests.get("http://127.0.0.1:8000/api/activities/1",
    headers=vheaders,
    params=params)
print(response.json())
```

#### bash

Voici un exemple d'utilisation de l'API en ligne de commande avec [CURL](https://curl.se/docs/manpage.html)
et [JQ](https://stedolan.github.io/jq/)

```bash
#!/usr/bin/bash

API_URL=http://127.0.0.1:8000/api
OBJECT=applications
OBJECT_ID=45

# Identifiants valides
data='{"login":"admin@admin.com","password":"password"}'

# Obtenir un token après connexion
TOKEN=$(curl -s -d ${data} -H "Content-Type: application/json" ${API_URL}/login | jq -r .access_token)

# Liste avec filtres
echo "Liste des applications avec filtres..."
curl -s -X GET "${API_URL}/${OBJECT}?filter[name]=CRM&sort=-created_at" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq .

# Récupération avec relations
echo "Récupération d'un objet avec relations..."
RESPONSE=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}?include=databases,processes" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Objet récupéré: ${RESPONSE}"

# Mise à jour
RESPONSE=$(echo "$RESPONSE" | jq -c '.')
RESPONSE=$(echo "$RESPONSE" | jq -r '.activities=[1]')

echo "Objet modifié: ${RESPONSE}"

curl -s -X PUT "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "cache-control: no-cache" \
  -d "$RESPONSE"

# Vérification
UPDATED_OBJECT=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Objet mis à jour: ${UPDATED_OBJECT}"
```

#### Powershell

Le script PowerShell ci-dessous montre comment s'authentifier auprès de l'API et utiliser les filtres avancés.

##### Étape 1 — Authentification et obtention du jeton d'accès

```powershell
# Définir l'URL d'authentification et les identifiants
$loginUri = "http://127.0.0.1:8000/api/login"
$loginBody = @{
    login = "admin@admin.com"
    password = "password"
}

# Envoyer la requête d'authentification
try {
    $loginResponse = Invoke-RestMethod -Uri $loginUri -Method Post -Body $loginBody -ContentType "application/x-www-form-urlencoded"
    $token = $loginResponse.access_token
    Write-Host "Jeton d'accès récupéré avec succès."
} catch {
    Write-Error "Échec de l'authentification : $_"
    return
}
```

##### Étape 2 — Utilisation avec filtres et tri

```powershell
# Définir les en-têtes
$headers = @{
    'Authorization' = "Bearer $token"
    'Accept'        = 'application/json'
}

# Liste avec filtres
$endPoint = "logical-servers"
$filters = "filter[operating_system]=Linux&sort=-created_at"
$apiUri = "http://127.0.0.1:8000/api/$endPoint?$filters"

try {
    $servers = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $servers | Format-Table id, name, operating_system, description
} catch {
    Write-Error "Échec de la requête : $_"
}

# Récupération avec relations
$activityId = 1
$apiUri = "http://127.0.0.1:8000/api/activities/$activityId?include=processes,operations"

try {
    $activity = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $activity | ConvertTo-Json -Depth 10
} catch {
    Write-Error "Échec de la requête : $_"
}
```

### Résumé des fonctionnalités

| Fonctionnalité    | Syntaxe                           | Exemple                                 |
|-------------------|-----------------------------------|-----------------------------------------|
| Filtre exact      | `filter[champ]=valeur`            | `filter[name]=Backup`                   |
| Comparaison       | `filter[champ_operateur]=valeur`  | `filter[recovery_time_objective_gte]=8` |
| Négation          | `filter[champ_not]=valeur`        | `filter[type_not]=opensource`           |
| Liste de valeurs  | `filter[champ_in]=v1,v2,v3`       | `filter[id_in]=1,2,3`                   |
| Intervalle        | `filter[champ_between]=min,max`   | `filter[age_between]=18,65`             |
| NULL              | `filter[champ_null]=true/false`   | `filter[deleted_at_null]=true`          |
| Dates             | `filter[champ_after/before]=date` | `filter[created_at_after]=2024-01-01`   |
| Recherche globale | `filter[search]=terme`            | `filter[search]=backup`                 |
| Relation          | `filter[relation.champ]=valeur`   | `filter[actors.email]=john`             |
| Tri               | `sort=champ` ou `sort=-champ`     | `sort=-created_at`                      |
| Relations         | `include=relation1,relation2`     | `include=processes,operations`          |
| Soft deletes      | `filter[trashed]=with/only`       | `filter[trashed]=with`                  |

