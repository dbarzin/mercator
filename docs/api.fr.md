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

- l'environnement Docker prend en charge cette fonctionnalité nativement, via l'entrypoint.

### Les APIs

Pour chaque objet du modèle de données de la cartographie, il existe une API.
La liste des API se trouve dans le fichier /route/api.php

*Note:* Pour visualiser le datamodel d'une API, cliquer sur son nom.

__Vue du RGPD__

- [/api/data-processings](./model.fr.md#registre)
- [/api/security-controls](./model.fr.md#mesures-de-securite)

__Vues de l'écosystème__

- [/api/entities](./model.fr.md#entites)
- [/api/relations](./model.fr.md#relations)

__Vue métier du système d'information__

- [/api/macro-processuses](./model.fr.md#macro-processus)
- [/api/processes](./model.fr.md#processus)
- [/api/activities](./model.fr.md#activites)
- [/api/operations](./model.fr.md#operations)
- [/api/tasks](./model.fr.md#taches)
- [/api/actors](./model.fr.md#acteurs)
- [/api/information](./model.fr.md#information)

__Vue des applications__

- [/api/application-blocks](./model.fr.md#blocs-applicatif)
- [/api/applications](./model.fr.md#applications)
- [/api/application-services](./model.fr.md#services-applicatif)
- [/api/application-modules](./model.fr.md#modules-applicatif)
- [/api/databases](./model.fr.md#bases-de-donnees)
- [/api/fluxes](./model.fr.md#flux)

__Vue de l'administration__

- [/api/zone-admins](./model.fr.md#zones-dadministration)
- [/api/annuaires](./model.fr.md#services-dannuaire-dadministration)
- [/api/forest-ads](./model.fr.md#forets-active-directory-arborescence-ldap)
- [/api/domaine-ads](./model.fr.md#domaines-active-directory-ldap)
- [/api/admin-users](./model.fr.md#utilisateurs)

__Vue de l'infrastructure logique__

- [/api/networks](./model.fr.md#reseaux)
- [/api/subnetworks](./model.fr.md#sous-reseaux)
- [/api/gateways](./model.fr.md#passerelles-dentrees-depuis-lexterieur)
- [/api/external-connected-entities](./model.fr.md#entites-exterieures-connectees)
- [/api/network-switches](./model.fr.md#commutateurs-reseau)
- [/api/routers](./model.fr.md#routeurs-logiques)
- [/api/security-devices](./model.fr.md#equipements-de-securite)
- [/api/dhcp-servers](./model.fr.md#serveurs-dhcp)
- [/api/dnsservers](./model.fr.md#serveurs-dns)
- [/api/clusters](./model.fr.md#clusters)
- [/api/logical-servers](./model.fr.md#serveurs-logiques)
- [/api/logical-flows](./model.fr.md#flux-logiques)
- [/api/containers](./model.fr.md#conteneurs)
- [/api/certificates](./model.fr.md#certificats)
- [/api/vlans](./model.fr.md#vlans)

__Vue de l'infrastructure physique__

- [/api/sites](./model.fr.md#sites)
- [/api/buildings](./model.fr.md#batiments-salles)
- [/api/bays](./model.fr.md#baies)
- [/api/physical-servers](./model.fr.md#serveurs-physiques)
- [/api/workstations](./model.fr.md#postes-de-travail)
- [/api/storage-devices](./model.fr.md#infrastructures-de-stockage)
- [/api/peripherals](./model.fr.md#peripheriques)
- [/api/phones](./model.fr.md#telephones)
- [/api/physical-switches](./model.fr.md#commutateurs-physiques)
- [/api/physical-routers](./model.fr.md#routeurs-physiques)
- [/api/wifi-terminals](./model.fr.md#bornes-wifi)
- [/api/physical-security-devices](./model.fr.md#equipements-de-securite-physique)
- [/api/physical-links](./model.fr.md#liens-physiques)
- [/api/wans](./model.fr.md#wans)
- [/api/mans](./model.fr.md#mans)
- [/api/lans](./model.fr.md#lans)

__Rapport__

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

### Actions gérées par le contrôleur de ressources

Les requêtes et URI de chaque api est représentée dans le tableau ci-dessous.

| Requête   | URI                      | Action 	                              |
|-----------|--------------------------|---------------------------------------|
| GET       | /api/objets              | renvoie la liste des objets           |
| GET       | /api/objets/{id}         | renvoie l'objet {id}                  |
| POST 	    | /api/objets              | sauve un nouvel objet                 |
| PUT/PATCH | /api/objets/{id}         | met à jour l'objet {id}               |
| DELETE 	  | /api/objets/{id}         | supprime l'objet {id}                 |
| POST      | /api/objets/mass-store   | crée plusieurs objets en une requête  |
| PUT/PATCH | /api/objets/mass-update  | met à jour plusieurs objets à la fois |
| DELETE    | /api/objets/mass-destroy | supprime plusieurs objets à la fois   |

Les champs à fournir sont ceux décrits dans le [modèle de données](/mercator/model/).

Pour voir les fonctions avancées de filtres : voir la page [API Avancé (filtres)](./apifilters.fr.md)

### Droits d'accès

Il faut s'identifier avec un utilisateur de l'application Mercator pour pouvoir accèder aux API.
Cet utilisateur doit disposer d'un rôle dans Mercator qui lui permet d'accéder / modifier les objets
accédés par l'API.

Lorsque l'authentification réussit, l'API envoie un "access_token" qui doit être passé dans
l'entête "Authorization" de la requête de l'API.

### Liaison entre les objets

Les objets de la cartographie peuvent faire référence à d'autres objets. Par exemple, nous pouvons lier une processus à
une application. Supposons que nous ayons un "processus" qui utilise deux applications "app1" et "app2". Pour ce faire,
nous suivons ces étapes :

- Étape 1 : Assurez-vous d'avoir l'application_id pour les applications que vous souhaitez lier.

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

- Étape 2 : Liez le processus aux applications. Soit avec une mise à jour, soit avec un enregistrement, nous pouvons
  ajouter :

```
{
  "id": 101,
  "name": "processus",
  "application_id[]": [201, 202]
}
```

Les noms de tous les champs supplémentaires
sont : ['actors', 'tasks', 'activities', 'entities', 'applications', 'informations', 'processes', 'databases', 'logical_servers', 'modules', 'domainesForestAds', 'servers', 'vlans', 'lans', 'mans', 'wans', 'operations', 'domaineAds', 'applicationServices', 'certificates', 'peripherals', 'physicalServers', 'physicalRouters', 'networkSwitches', 'routers', 'physicalSwitches' ].

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
    data= {'login':'admin@admin.com', 'password':'password'} )
print(response.status_code)

vheaders['Authorization'] = "Bearer " + response.json()['access_token']

print("Get workstations")
response = requests.get("http://127.0.0.1:8000/api/workstations", headers=vheaders)
data=response.json()
print(data)
print(response.status_code)

```

#### bash

Voici un exemple d'utilisation de l'API en ligne de commande avec [CURL](https://curl.se/docs/manpage.html)
et [JQ](https://stedolan.github.io/jq/)

```
#!/usr/bin/bash

API_URL=http://127.0.0.1:8000/api
OBJECT=applications
OBJECT_ID=45

# valid login and password

data='{"login":"admin@admin.com","password":"password"}'

# Get a token after correct login

TOKEN=$(curl -s -d ${data} -H "Content-Type: application/json" ${API_URL}/login | jq -r .access_token)

# Récupération de l'objet
RESPONSE=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}" \
 -H "Authorization: Bearer ${TOKEN}" \
 -H "Accept: application/json")

echo "Objet récupéré: ${RESPONSE}"

# Mise à jour d'une valeur avec une requête PUT

RESPONSE=$(echo "$RESPONSE" | jq -c '.data')
RESPONSE=$(echo "$RESPONSE" | jq -r '.activities=[1]')

echo "Objet modifié: ${RESPONSE}"

curl -s -X PUT "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "cache-control: no-cache" \
  -d "$RESPONSE"

# Vérification de la mise à jour

UPDATED_OBJECT=$(curl -s -X GET "${API_URL}/${OBJECT}/${OBJECT_ID}" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json")

echo "Objet mis à jour: ${UPDATED_OBJECT}"

```

#### Powershell

Le script PowerShell ci-dessous montre comment s’authentifier auprès de l’API et récupérer la liste des serveurs
logiques.

##### Étape 1 — Authentification et obtention du jeton d’accès

```powershell
# Définir l’URL d’authentification et les identifiants
$loginUri = "http://127.0.0.1:8000/api/login"
$loginBody = @{
    login = "admin@admin.com"
    password = "password"
}

# Envoyer la requête d’authentification
try {
    $loginResponse = Invoke-RestMethod -Uri $loginUri -Method Post -Body $loginBody -ContentType "application/x-www-form-urlencoded"
    $token = $loginResponse.access_token
    Write-Host "Jeton d’accès récupéré avec succès."
} catch {
    Write-Error "Échec de l’authentification : $_"
    return
}
```

##### Étape 2 — Utilisation du jeton pour interroger les serveurs logiques

```powershell
# Définir l’endpoint et les en-têtes d’autorisation
$endPoint = "logical-servers"
$apiUri = "https://127.0.0.1:8000/api/$endPoint"
$headers = @{
    'Authorization' = "Bearer $token"
    'Accept'        = 'application/json'
}

# Envoyer la requête GET
try {
    $servers = Invoke-RestMethod -Uri $apiUri -Method Get -Headers $headers
    $servers | Format-Table id, name, operating_system, description
} catch {
    Write-Error "Échec de la requête : $_"
}
```