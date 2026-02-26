# API

🇫🇷 [Lire en français](/mercator/fr/api)

Cartography can be modified or updated via a REST API.

A REST API ([Representational State Transfer](https://fr.wikipedia.org/wiki/Representational_state_transfer))
is an application programming interface that respects the constraints of the REST
architecture and enables interaction with RESTful web services.

### Install the API on Mercator

To install the API in Mercator, you need to install Passport by running this command:

```bash
php artisan passport:install
```

- The Docker environment supports this functionality natively, via the entrypoint.

### APIs

For each object in the cartography data model, there is an API.
The list of APIs can be found in /route/api.php

__GDPR view__

- [/api/data-processings](./model.md#register)
- [/api/security-controls](./model.md#security-measures)

__Ecosystem view__

- [/api/entities](./model.md#entities)
- [/api/relations](./model.md#relationships)

__Information system business view__

- [/api/macro-processuses](./model.md#macro-processes)
- [/api/processes](./model.md#processes)
- [/api/activities](./model.md#activities)
- [/api/operations](./model.md#operations)
- [/api/tasks](./model.md#tasks)
- [/api/actors](./model.md#actors)
- [/api/information](./model.md#information)

__Application view__

- [/api/application-blocks](./model.md#applications-blocks)
- [/api/applications](./model.md#applications)
- [/api/application-services](./model.md#applications-services)
- [/api/application-modules](./model.md#application-modules)
- [/api/databases](./model.md#databases)
- [/api/fluxes](./model.md#flows)

__Administration view__

- [/api/zone-admins](./model.md#administration-areas)
- [/api/annuaires](./model.md#administration-directory-services)
- [/api/forest-ads](./model.md#active-directory-forests-ldap-tree-structure)
- [/api/domaine-ads](./model.md#active-directory-domains-ldap)
- [/api/admin-users](./model.md#users)

__Logical infrastructure view__

- [/api/networks](./model.md#networks)
- [/api/subnetworks](./model.md#subnetworks)
- [/api/gateways](./model.md#external-input-gateways)
- [/api/external-connected-entities](./model.md#connected-external-entities)
- [/api/network-switches](./model.md#network-switches)
- [/api/routers](./model.md#logical-routers)
- [/api/security-devices](./model.md#security-equipments)
- [/api/dhcp-servers](./model.md#dhcp-servers)
- [/api/dnsservers](./model.md#dns-servers)
- [/api/clusters](./model.md#clusters)
- [/api/logical-servers](./model.md#logical-servers)
- [/api/containers](./model.md#containers)
- [/api/logical-flows](./model.md#logical-flows)
- [/api/certificates](./model.md#certificates)
- [/api/vlans](./model.md#vlans)

__Physical infrastructure view__

- [/api/sites](./model.md#sites)
- [/api/buildings](./model.md#buildings-rooms)
- [/api/bays](./model.md#racks)
- [/api/physical-servers](./model.md#physical-servers)
- [/api/workstations](./model.md#workstations)
- [/api/storage-devices](./model.md#storage-infrastructures)
- [/api/peripherals](./model.md#peripherals)
- [/api/phones](./model.md#phones)
- [/api/physical-switches](./model.md#physical-switches)
- [/api/physical-routers](./model.md#physical-routers)
- [/api/wifi-terminals](./model.md#wifi-terminals)
- [/api/physical-security-devices](./model.md#physical-security-equipments)
- [/api/wans](./model.md#wans)
- [/api/mans](./model.md#mans)
- [/api/lans](./model.md#lans)
- [/api/physical-links](./model.md#physical-links)

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

### Actions managed by the resource controller

Requests and URIs for each api are shown in the table below.

| Request   | URI                       | Action                                       |
| --------- | ------------------------- | -------------------------------------------- |
| GET       | /api/objects              | returns the list of objects                  |
| GET       | /api/objects/{id}         | returns the object with ID `{id}`            |
| POST      | /api/objects              | creates a new object                         |
| PUT/PATCH | /api/objects/{id}         | updates the object with ID `{id}`            |
| DELETE    | /api/objects/{id}         | deletes the object with ID `{id}`            |
| POST      | /api/objects/mass-store   | creates multiple objects in a single request |
| PUT/PATCH | /api/objects/mass-update  | updates multiple objects at once             |
| DELETE    | /api/objects/mass-destroy | deletes multiple objects at once             |


The fields to be supplied are those described in the [data model](/mercator/model/).

To get access to advanced filters, feel free to check the related page: [API Advanced (filters)](./apifilters.md)

### Access rights

To access the APIs, you must identify yourself as a Mercator application user.
This user must have a role in Mercator that allows him/her to access/modify the objects
objects accessed via the API.

When authentication is successful, the API sends an "access_token", which must be passed in the "Authorization" header.
header of the API request.

### Linking objects

Mapping objects can refer to other objects. For example, we can link a process to an application. Suppose we have a
‘process’ that uses two applications, ‘app1’ and ‘app2’. To do this, we follow these steps:

- Step 1: Ensure you have the application_id for the applications you want to link.

```
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

- Step 2: Link the process to the applications. Either with an update or a store, we can add:

```
{
  "id": 101,
  "name": "process",
  "applications[]": [201, 202]
}
```

The names of all extra fields
are: ['actors', 'tasks', 'activities', 'entities', 'applications', 'informations', 'processes', 'databases', 'logical_servers', 'modules', 'domainesForestAds', 'servers', 'vlans', 'lans', 'mans', 'wans', 'operations', 'domaineAds', 'applicationServices', 'certificates', 'peripherals', 'physicalServers', 'networkSwitches', 'physicalSwitches', 'physicalRouters']


### Examples

Please find below few examples with differents languages

#### PHP
Here are a few examples of how to use the API with PHP:

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
            set_error_handler("Login to api faild status 403");
            error_log($responseInfo['http_code']);
            error_log("No login api status 403");

        }
    }

    var_dump($response);
```

##### Users list

```php
<?php
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/users",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => null, // here you can send parameters
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: " . "Bearer" . " " . $access_token . "",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
    ));


    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    var_dump($response);

```

##### Get a user

```php
<?php
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/users/1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => null, // here you can send parameters
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: " . "Bearer" . " " . $access_token . "",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    var_dump($response);
```

##### Update a user

```php
<?php
   $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://127.0.0.1:8000/api/users/8",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_POST => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => http_build_query(
            array(
                'name' => 'Henri',
                'login' => 'henri@test.fr',
                'language' => 'fr',
                'roles[0]' => 1,
                'roles[1]' => 3,
                'granularity' => '3')
            ),
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "Authorization: " . "Bearer" . " " . $access_token . "",
            "cache-control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    var_dump($response);
```

#### Python

Here's an example of how to use the API in Python :

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
    data= {'login':'admin@admin.com', 'password':'password'} )
print(response.status_code)

vheaders['Authorization'] = "Bearer " + response.json()['access_token']

print("Get workstations")
response = requests.get("http://127.0.0.1:8000/api/workstations", headers=vheaders)
print(response.json())
print(response.status_code)

```

#### Bash

Here's an example of using the API on the command line with [CURL](https://curl.se/docs/manpage.html)
and [JQ](https://stedolan.github.io/jq/)

```
# valid login and password
data='{"login":"admin@admin.com","password":"password"}'

# get a token after correct login
token=$(curl -s -d ${data} -H "Content-Type: application/json" http://localhost:8000/api/login | jq -r .access_token)

# query users and decode JSON data with JQ.
curl -s -H "Content-Type: application/json" -H "Authorization: Bearer ${token}" "http://127.0.0.1:8000/api/users" | jq .

```

Other Bash example

```
#!/usr/bin/bash

API_URL=http://127.0.0.1:8000/api

# valid login and password

data='{"login":"admin@admin.com","password":"password"}'

# Get a token after correct login

TOKEN=$(curl -s -d ${data} -H "Content-Type: application/json" ${API_URL}/login | jq -r .access_token)

# Récupération de l'objet

OBJECT_ID=10

RESPONSE=$(curl -s -X GET "${API_URL}/logical-servers/${OBJECT_ID}" \
 -H "Authorization: Bearer ${TOKEN}" \
 -H "Accept: application/json")

echo "Objet récupéré: ${RESPONSE}"

# Mise à jour d'une valeur avec une requête PUT

RESPONSE=$(echo "$RESPONSE" | jq -c '.data')
RESPONSE=$(echo "$RESPONSE" | jq -r '.operating_system="Linux"')

echo "Objet modifié: ${RESPONSE}"

curl -s -X PUT "${API_URL}/logical-servers/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "cache-control: no-cache" \
  -d "$RESPONSE"

# Vérification de la mise à jour

UPDATED_OBJECT=$(curl -s -X GET "${API_URL}/logical-servers/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Objet mis à jour: ${UPDATED_OBJECT}"
```

#### PowerShell

The following **PowerShell** script demonstrates how to authenticate with the API and retrieve the list of logical
servers.

##### Step 1 — Authenticate and obtain an access token

```powershell
# Define the login endpoint and credentials
$loginUri = "http://127.0.0.1:8000/api/login"
$loginBody = @{
    login = "admin@admin.com"
    password = "password"
}

# Send the authentication request
try {
    $loginResponse = Invoke-RestMethod -Uri $loginUri -Method Post -Body $loginBody -ContentType "application/x-www-form-urlencoded"
    $token = $loginResponse.access_token
    Write-Host "Access token successfully retrieved."
} catch {
    Write-Error "Authentication failed: $_"
    return
}
```

##### Step 2 — Use the token to query logical servers

```powershell
# Define the endpoint and authorization headers
$endPoint = "logical-servers"
$apiUri = "http://127.0.0.1:8000/api/$endPoint"
$headers = @{
    'Authorization' = "Bearer $token"
    'Accept'        = 'application/json'
}

# Send the GET request
try {
    $servers = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $servers | Format-Table id, name, operating_system, description
} catch {
    Write-Error "Request failed: $_"
}
```
