## API

The cartography can be modified or updated via a REST API.

A REST API ([Representational State Transfer](https://en.wikipedia.org/wiki/Representational_state_transfer))
is an application programming interface that respects the constraints of REST architectural style
and allows interaction with RESTful web services.

### Installing the API on Mercator

To install the API in Mercator, you need to install Passport by running this command:

```bash
php artisan passport:install
```

- The Docker environment supports this functionality natively, via the entrypoint.

### The APIs

For each object in the cartography data model, there is an API.
The list of APIs can be found in the /route/api.php file

__GDPR View__

- /api/data-processings
- /api/security-controls

__Ecosystem Views__

- /api/entities
- /api/relations

__Business View of the Information System__

- /api/macro-processuses
- /api/processes
- /api/activities
- /api/operations
- /api/tasks
- /api/actors
- /api/information

__Applications View__

- /api/application-blocks
- /api/applications
- /api/application-services
- /api/application-modules
- /api/databases
- /api/fluxes

__Administration View__

- /api/zone-admins
- /api/annuaires
- /api/forest-ads
- /api/domaine-ads
- /api/admin-users

__Logical Infrastructure View__

- /api/networks
- /api/subnetworks
- /api/gateways
- /api/external-connected-entities
- /api/network-switches
- /api/routers
- /api/security-devices
- /api/dhcp-servers
- /api/dnsservers
- /api/clusters
- /api/logical-servers
- /api/logical-flows
- /api/containers
- /api/certificates
- /api/vlans

__Physical Infrastructure View__

- /api/sites
- /api/buildings
- /api/bays
- /api/physical-servers
- /api/workstations
- /api/storage-devices
- /api/peripherals
- /api/phones
- /api/physical-switches
- /api/physical-routers
- /api/wifi-terminals
- /api/physical-security-devices
- /api/wans
- /api/mans
- /api/lans
- /api/physical-links
- /api/fluxes

__Reports__

- /api/report/cartography
- /api/report/entities
- /api/report/applicationsByBlocks
- /api/report/directory
- /api/report/logicalServers
- /api/report/securityNeeds
- /api/report/logicalServerConfigs
- /api/report/externalAccess
- /api/report/physicalInventory
- /api/report/vlans
- /api/report/workstations
- /api/report/cve
- /api/report/activityList
- /api/report/activityReport
- /api/report/impacts
- /api/report/rto

### Actions Handled by the Resource Controller

The requests and URIs for each API are represented in the table below.

| Request   | URI                       | Action 	                                |
|-----------|---------------------------|-----------------------------------------|
| GET       | /api/objects              | returns the list of objects             |
| GET       | /api/objects/{id}         | returns the object {id}                 |
| POST 	    | /api/objects              | saves a new object                      |
| PUT/PATCH | /api/objects/{id}         | updates the object {id}                 |
| DELETE 	  | /api/objects/{id}         | deletes the object {id}                 |
| POST      | /api/objects/mass-store   | creates multiple objects in one request |
| PUT/PATCH | /api/objects/mass-update  | updates multiple objects at once        |
| DELETE    | /api/objects/mass-destroy | deletes multiple objects at once        |

The fields to provide are those described in the [data model](/mercator/model/).

### Filtering, Sorting and Including Relations

List endpoints (`GET /api/objects`) support an advanced system of filters, sorting and relation inclusion via
query parameters.

#### Filterable Fields

All fields declared in the model (`$fillable`) are automatically filterable. Filters on unauthorized
fields are simply ignored.

#### Filter Syntax

Each filter is presented in the form:

```text
filter[<field>]=<value>                     # Exact filter
filter[<field>_<operator>]=<value>          # Filter with operator
```

#### Available Filtering Operators

##### Exact and Text Filters

| Operator  | Syntax                | Description             | Example                |
|-----------|-----------------------|-------------------------|------------------------|
| (none)    | `filter[name]=Backup` | Exact equality          | `name = 'Backup'`      |
| (partial) | `filter[name]=backup` | Partial search (LIKE) * | `name LIKE '%backup%'` |

_* Automatic partial search applies to fields containing: `name`, `description`, `email`_

##### Numeric Comparisons

| Operator | Syntax                                  | Description              | SQL Example                    |
|----------|-----------------------------------------|--------------------------|--------------------------------|
| `_lt`    | `filter[recovery_time_objective_lt]=4`  | Less than                | `recovery_time_objective < 4`  |
| `_lte`   | `filter[recovery_time_objective_lte]=4` | Less than or equal to    | `recovery_time_objective <= 4` |
| `_gt`    | `filter[recovery_time_objective_gt]=8`  | Greater than             | `recovery_time_objective > 8`  |
| `_gte`   | `filter[recovery_time_objective_gte]=8` | Greater than or equal to | `recovery_time_objective >= 8` |

##### Advanced Operators

| Operator   | Syntax                                         | Description          | Example                                    |
|------------|------------------------------------------------|----------------------|--------------------------------------------|
| `_in`      | `filter[id_in]=1,2,3`                          | Value in a list      | `id IN (1, 2, 3)`                          |
| `_between` | `filter[recovery_time_objective_between]=4,24` | Between two values   | `recovery_time_objective BETWEEN 4 AND 24` |
| `_null`    | `filter[deleted_at_null]=true`                 | NULL value           | `deleted_at IS NULL`                       |
| `_null`    | `filter[description_null]=false`               | NOT NULL value       | `description IS NOT NULL`                  |
| `_not`     | `filter[type_not]=opensource`                  | Negation (different) | `type != 'opensource'`                     |

##### Global Search and Relation Filters

| Operator   | Syntax                        | Description                                             | Example                                               |
|------------|-------------------------------|---------------------------------------------------------|-------------------------------------------------------|
| `search`   | `filter[search]=backup`       | OR search on all text fields (name, description, email) | `name LIKE '%backup%' OR description LIKE '%backup%'` |
| (relation) | `filter[actors.email]=john`   | LIKE search on relation fields                          | `actors.email LIKE '%john%'`                          |
| (relation) | `filter[contacts.name]=alice` | LIKE search on name in a relation                       | `contacts.name LIKE '%alice%'`                        |

##### Date Filters

For fields containing `date` or `at` in their name:

| Operator  | Syntax                                 | Description      | Example                      |
|-----------|----------------------------------------|------------------|------------------------------|
| `_after`  | `filter[created_at_after]=2024-01-01`  | After this date  | `created_at >= '2024-01-01'` |
| `_before` | `filter[updated_at_before]=2024-12-31` | Before this date | `updated_at <= '2024-12-31'` |

##### Soft Deletes

| Syntax                 | Description           |
|------------------------|-----------------------|
| `filter[trashed]=with` | Include deleted items |
| `filter[trashed]=only` | Only deleted items    |

#### Sorting Results

Sorting is done with the `sort` parameter:

```text
sort=<field>        # Ascending sort
sort=-<field>       # Descending sort
```

Examples:

- `sort=name` : Sort by name ascending
- `sort=-created_at` : Sort by creation date descending
- `sort=recovery_time_objective` : Sort by RTO ascending

#### Including Relations (Eager Loading)

Relations between objects can be loaded with the `include` parameter:

```text
include=<relation1>,<relation2>
```

Available relations are automatically detected from the model. For activities, for example:

- `processes` : Related processes
- `operations` : Related operations
- `applications` : Related applications
- `impacts` : Related impacts

Example: `include=processes,operations`

#### Complete Examples

##### Simple Filters

```http
# Activities with name containing "backup"
GET /api/activities?filter[name]=backup

# Activities with RTO less than or equal to 4 hours
GET /api/activities?filter[recovery_time_objective_lte]=4

# Activities with ID greater than or equal to 100
GET /api/activities?filter[id_gte]=100

# Applications with type different from "opensource"
GET /api/applications?filter[type_not]=opensource

# Global search on all text fields
GET /api/applications?filter[search]=backup

# Search on linked actor's email
GET /api/activities?filter[actors.email]=john@example.com
```

##### Combined Filters

```http
# Activities with name containing "GDPR" and RTO >= 8
GET /api/activities?filter[name]=GDPR&filter[recovery_time_objective_gte]=8

# Servers created after January 1, 2024 with name containing "prod"
GET /api/logical-servers?filter[created_at_after]=2024-01-01&filter[name]=prod

# Applications with type different from "opensource" sorted by name
GET /api/applications?filter[type_not]=opensource&sort=name

# Global search "backup" on all activities with RTO <= 8
GET /api/activities?filter[search]=backup&filter[recovery_time_objective_lte]=8

# Activities linked to an actor whose email contains "admin"
GET /api/activities?filter[actors.email]=admin&include=actors
```

##### Advanced Filters

```http
# Activities with ID in the list 1, 2, 3, 5
GET /api/activities?filter[id_in]=1,2,3,5

# Activities with RTO between 4 and 24 hours
GET /api/activities?filter[recovery_time_objective_between]=4,24

# Activities without description
GET /api/activities?filter[description_null]=true

# Activities with a description
GET /api/activities?filter[description_null]=false

# Include soft-deleted items
GET /api/activities?filter[trashed]=with

# Only soft-deleted items
GET /api/activities?filter[trashed]=only
```

##### With Sorting and Relations

```http
# Activities with RTO >= 8, sorted by creation date descending
GET /api/activities?filter[recovery_time_objective_gte]=8&sort=-created_at

# Activities with name "GDPR", including related processes and operations
GET /api/activities?filter[name]=GDPR&include=processes,operations

# Complete example: filters + sort + include
GET /api/activities?filter[recovery_time_objective_gte]=8&filter[name]=GDPR&sort=-created_at&include=processes,operations
```

### Authentication

#### Authentication with email / password

To use the API, it is necessary to obtain an authentication token.

Endpoint : `POST /api/login`

Parameters :

- **login** : User's email address
- **password** : User's password

Example with CURL:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -d "login=admin@admin.com" \
  -d "password=password"
```

The API returns a token in JSON format:

```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

This token must be included in the `Authorization` header for all subsequent requests:

```bash
curl -X GET http://127.0.0.1:8000/api/activities \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

#### Authentication with API Token

It's possible to create an API token directly from the interface.

For this:

- Click on "Profile" in the top menu
- Go to the "API Token" section
- Click on "Create a new token"
- Give a name to your token
- Copy the generated token

The token must be used in the `Authorization` header as a Bearer token.

### Usage Examples

Here are practical examples of API usage in different programming languages.

### PHP

#### Authentication

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
        CURLOPT_POSTFIELDS => "login=admin@admin.com&password=password",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "cache-control: no-cache",
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

#### List of Activities with Filters

```php
<?php
    $curl = curl_init();

    // Build URL with filtering parameters
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

#### Retrieve an Activity with Relations

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

### Python

Here is an example of API usage in Python with advanced filters

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

# List with filters and sort
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

# Retrieval with relations
print("Get activity with relations")
params = {
    'include': 'processes,operations'
}
response = requests.get("http://127.0.0.1:8000/api/activities/1",
    headers=vheaders,
    params=params)
print(response.json())
```

### bash

Here is an example of API usage from the command line with [CURL](https://curl.se/docs/manpage.html)
and [JQ](https://stedolan.github.io/jq/)

```bash
#!/usr/bin/bash

API_URL=http://127.0.0.1:8000/api
OBJECT=applications
OBJECT_ID=45

# Valid credentials
data='{"login":"admin@admin.com","password":"password"}'

# Get a token after login
TOKEN=$(curl -s -d ${data} -H "Content-Type: application/json" ${API_URL}/login | jq -r .access_token)

# List with filters
echo "List of applications with filters..."
curl -s -X GET "${API_URL}/${OBJECT}?filter[name]=CRM&sort=-created_at" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" | jq .

# Retrieval with relations
echo "Retrieving an object with relations..."
RESPONSE=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}?include=databases,processes" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Retrieved object: ${RESPONSE}"

# Update
RESPONSE=$(echo "$RESPONSE" | jq -c '.')
RESPONSE=$(echo "$RESPONSE" | jq -r '.activities=[1]')

echo "Modified object: ${RESPONSE}"

curl -s -X PUT "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "cache-control: no-cache" \
  -d "$RESPONSE"

# Verification
UPDATED_OBJECT=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Updated object: ${UPDATED_OBJECT}"
```

### Powershell

The PowerShell script below shows how to authenticate with the API and use advanced filters.

#### Step 1 — Authentication and Obtaining Access Token

```powershell
# Define authentication URL and credentials
$loginUri = "http://127.0.0.1:8000/api/login"
$loginBody = @{
    login = "admin@admin.com"
    password = "password"
}

# Send authentication request
try {
    $loginResponse = Invoke-RestMethod -Uri $loginUri -Method Post -Body $loginBody -ContentType "application/x-www-form-urlencoded"
    $token = $loginResponse.access_token
    Write-Host "Access token retrieved successfully."
} catch {
    Write-Error "Authentication failed: $_"
    return
}
```

#### Step 2 — Usage with Filters and Sort

```powershell
# Define headers
$headers = @{
    'Authorization' = "Bearer $token"
    'Accept'        = 'application/json'
}

# List with filters
$endPoint = "logical-servers"
$filters = "filter[operating_system]=Linux&sort=-created_at"
$apiUri = "http://127.0.0.1:8000/api/$endPoint?$filters"

try {
    $servers = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $servers | Format-Table id, name, operating_system, description
} catch {
    Write-Error "Request failed: $_"
}

# Retrieval with relations
$activityId = 1
$apiUri = "http://127.0.0.1:8000/api/activities/$activityId?include=processes,operations"

try {
    $activity = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $activity | ConvertTo-Json -Depth 10
} catch {
    Write-Error "Request failed: $_"
}
```

### Feature Summary

| Feature        | Syntax                            | Example                                 |
|----------------|-----------------------------------|-----------------------------------------|
| Exact filter   | `filter[field]=value`             | `filter[name]=Backup`                   |
| Comparison     | `filter[field_operator]=value`    | `filter[recovery_time_objective_gte]=8` |
| Negation       | `filter[field_not]=value`         | `filter[type_not]=opensource`           |
| List of values | `filter[field_in]=v1,v2,v3`       | `filter[id_in]=1,2,3`                   |
| Range          | `filter[field_between]=min,max`   | `filter[age_between]=18,65`             |
| NULL           | `filter[field_null]=true/false`   | `filter[deleted_at_null]=true`          |
| Dates          | `filter[field_after/before]=date` | `filter[created_at_after]=2024-01-01`   |
| Global search  | `filter[search]=term`             | `filter[search]=backup`                 |
| Relation       | `filter[relation.field]=value`    | `filter[actors.email]=john`             |
| Sort           | `sort=field` or `sort=-field`     | `sort=-created_at`                      |
| Relations      | `include=relation1,relation2`     | `include=processes,operations`          |
| Soft deletes   | `filter[trashed]=with/only`       | `filter[trashed]=with`                  |