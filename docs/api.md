## API

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

_GDPR view_

- /api/data-processing
- /api/security-controls

_Ecosystem view_

- /api/entities
- /api/relations

_Information system business view_

- /api/macro-processes
- /api/macro-processuses
- /api/processes
- /api/operations
- /api/actors
- /api/activities
- /api/tasks
- /api/information

_Application view_

- /api/application-blocks
- /api/applications
- /api/application-modules
- /api/application-services
- /api/databases
- /api/fluxes

_Administration view_

- /api/zone-admins
- /api/annuaires
- /api/forest-ads
- /api/domaine-ads

_Logical infrastructure view_

- /api/networks
- /api/subnetworks
- /api/gateways
- /api/external-connected-entities
- /api/network-switches
- /api/routers
- /api/security-devices
- /api/dhcp-servers
- /api/dnsservers
- /api/logical-servers
- /api/certificates

_Physical infrastructure view_

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
- /api/vlans

_Administration_

- /api/users
- /api/permissions
- /api/roles

### Actions managed by the resource controller

Requests and URIs for each api are shown in the table below.

| Request | URI | Action    
|-----------|--------------------|--------------------------------|      
| GET | /api/objects | returns the list of objects |
| GET /api/objets/{id} | returns object {id} |
| POST | /api/objects | save new object |
| PUT/PATCH | /api/objets/{id} | update object {id} |
| DELETE | /api/objets/{id} | delete object {id} |

The fields to be supplied are those described in the [data model](/mercator/model/).

### Access rights

To access the APIs, you must identify yourself as a Mercator application user.
This user must have a role in Mercator that allows him/her to access/modify the objects
objects accessed via the API.

When authentication is successful, the API sends an "access_token", which must be passed in the "Authorization" header.
header of the API request.

### PHP

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
            array("email" => "admin@admin.com",
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
                'email' => 'henri@test.fr',
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

### Python

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
    data= {'email':'admin@admin.com', 'password':'password'} )
print(response.status_code)

vheaders['Authorization'] = "Bearer " + response.json()['access_token']

print("Get workstations")
response = requests.get("http://127.0.0.1:8000/api/workstations", headers=vheaders)
print(response.json())
print(response.status_code)

```

### Bash

Here's an example of using the API on the command line with [CURL](https://curl.se/docs/manpage.html) and [JQ](https://stedolan.github.io/jq/)

```
# valid login and password
data='{"email":"admin@admin.com","password":"password"}'

# get a token after correct login
token=$(curl -s -d ${data} -H "Content-Type: application/json" http://localhost:8000/api/login | jq -r .access_token)

# query users and decode JSON data with JQ.
curl -s -H "Content-Type: application/json" -H "Authorization: Bearer ${token}" "http://127.0.0.1:8000/api/users" | jq .

```
