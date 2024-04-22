## API

La cartographie peut être modifiée ou mise à jour via une REST API.

Une API REST ([Representational State Transfer](https://fr.wikipedia.org/wiki/Representational_state_transfer))
est une interface de programmation d'application qui respecte les contraintes du style d'architecture REST
et permet d'interagir avec les services web RESTful.

### Installer l'API sur Mercator

pour installer l'API dans Mercator, il est nécessaire d'installer Passport en lançant cette commande :

```bash
php artisan passport:install
```
- l'environnement Docker prend en charge cette fonctionnalité nativement, via le l'entrypoint.

### Les APIs

Pour chaque objet du modèle de données de la cartographie, il existe une API.
La liste des API se trouve dans le fichier /route/api.php

__Vue du RGPD__

- /api/data-processings
- /api/security-controls

__Vues de l'écosystème__

- /api/entities
- /api/relations

__Vue métier du système d'information__

- /api/macro-processuses
- /api/processes
- /api/operations
- /api/actors
- /api/activities
- /api/tasks
- /api/information

__Vue des applications__

- /api/application-blocks
- /api/applications
- /api/application-modules
- /api/application-services
- /api/databases
- /api/fluxes

__Vue de l'administration__

- /api/zone-admins
- /api/annuaires
- /api/forest-ads
- /api/domaine-ads

__Vue de l'infrastructure logique__

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
- /api/certificates
- /api/vlans

__Vue de l'infrastructure physique__

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

__Administration__

- /api/users
- /api/permissions
- /api/roles

### Actions gérées par le contrôleur de ressources

Les requêtes et URI de chaque api est représentée dans le tableau ci-dessous.

| Requête   | URI                | Action 	
|-----------|--------------------|--------------------------------|      
| GET       | /api/objets        | renvoie la liste des objets    |
| GET       | /api/objets/{id}   | renvoie l'objet {id}           |
| POST 	    | /api/objets 	     | sauve un nouvel objet          |
| PUT/PATCH | /api/objets/{id}   | met à jour l'objet {id}        |
| DELETE 	| /api/objets/{id}   | supprimer l'objet {id}         |

Les champs à fournir sont ceux décrits dans le [modèle de données](/mercator/model/).

### Droits d'accès

Il faut s'identifier avec un utilisateur de l'application Mercator pour pouvoir accèder aux API.
Cet utilisateur doit disposer d'un rôle dans Mercator qui lui permet d'accéder / modifier les objets
accédés par l'API.

Lorsque l'authentification réussi, l'API envoie un "access_token" qui doit être passé dans
l'entête "Authorization" de la requête de l'API.

### Exemples

Voici quelques exemples d'utilisation de l'API avec PHP :

#### Authentification

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

#### Liste des utilisateurs

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

#### Récupérer un utilisateur

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

#### Mettre à jour un utilisateur

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

Voici un exemple d'utilisation de l'API en Python

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

### bash

Voici un exemple d'utilisation de l'API en ligne de commande avec [CURL](https://curl.se/docs/manpage.html) et [JQ](https://stedolan.github.io/jq/)
```
# valid login and password
data='{"email":"admin@admin.com","password":"password"}'

# get a token after correct login
token=$(curl -s -d ${data} -H "Content-Type: application/json" http://localhost:8000/api/login | jq -r .access_token)

# query users and decode JSON data with JQ.
curl -s -H "Content-Type: application/json" -H "Authorization: Bearer ${token}" "http://127.0.0.1:8000/api/users" | jq .

```
