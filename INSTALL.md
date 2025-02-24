# Mercator installation procedure

## Recommended configuration

- OS : Ubuntu 24.04 LTS - Server - Small user footprint
- RAM : 2G
- Disk : 10G
- VCPU 2

## Installation

Update the linux distribution

    sudo apt update && sudo apt upgrade

Install PHP and some PHP libraries

    sudo apt install php-zip php-curl php-mbstring php-xml php-ldap php-soap php-xdebug php-mysql php-gd libapache2-mod-php

Install Apache2, GIT, Graphviz et Composer

    sudo apt install apache2 git graphviz composer

## Project

Create the project directory

    cd /var/www
    sudo mkdir mercator
    sudo chown $USER:$GROUP mercator

Clone the project from Github

    git clone https://www.github.com/dbarzin/mercator

## Composer

Install packages with composer :

    cd /var/www/mercator
    composer install

## MySQL

Install MySQL

    sudo apt install mysql-server

Launch MySQL with root rights

    sudo mysql

Create the database _mercator_ and the user _mercator_user_.

    CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;
    CREATE USER 'mercator_user'@'localhost' IDENTIFIED BY 's3cr3t';
    GRANT ALL PRIVILEGES ON mercator.* TO 'mercator_user'@'localhost';
    GRANT PROCESS ON *.* TO 'mercator_user'@'localhost';

    FLUSH PRIVILEGES;
    EXIT;

## Configuration

Create an .env file in the root directory of the project:

    cd /var/www/mercator
    cp .env.example .env

Put the connection parameters to the database :

    vi .env

    ## .env file
    DB_CONNECTION=mysql
    # DB_CONNECTION=pgsql.env
    DB_HOST=127.0.0.1
    DB_PORT=3306
    # Comment DB_PORT for pgsql
    DB_DATABASE=mercator
    DB_USERNAME=mercator_user
    DB_PASSWORD=s3cr3t


## Create the database

Execute the migrations

    php artisan migrate --seed

Note: the seed is important (--seed), as it will create the first administrator user for you.

Generate the application key

    php artisan key:generate

Clear the cache

    php artisan config:clear

To import the test database (optional)

    sudo mysql mercator < mercator_data.sql

## Import the CPE Database

Decompress CPE databse

    gzip -d mercator_cpe.sql.gz

Import database

    sudo mysql mercator < mercator_cpe.sql

## Start

Start the application with php

    php artisan serve

or to access the application there from another server

    php artisan serve --host 0.0.0.0 --port 8000

The application is accessible at the URL [http://127.0.0.1:8000]
    user : admin@admin.com
    password : password

## Passport configuration

To set up the JSON API, install Laravel Passport

    php artisan passport:install

Generate the API keys

    php artisan passport:keys

Change the access permissions of the key

    sudo chown www-data:www-data storage/oauth-*.key
    sudo chmod 600 storage/oauth-*.key

## Mail configuration

If you wish to send notification e-mails from Mercator.
You have to configure the SMTP server access in .env

    MAIL_HOST='smtp.localhost'
    MAIL_PORT=2525
    MAIL_AUTH=true
    MAIL_SMTP_SECURE='ssl'
    MAIL_SMTP_AUTO_TLS=false
    MAIL_USERNAME=
    MAIL_PASSWORD=

You may also configure DKIM :

    MAIL_DKIM_DOMAIN = 'admin.local';
    MAIL_DKIM_PRIVATE = '/path/to/private/key';
    MAIL_DKIM_SELECTOR = 'default'; // Match your DKIM DNS selector
    MAIL_DKIM_PASSPHRASE = '';      // Only if your key has a passphrase

Don't forget to [configure](https://dbarzin.github.io/deming/config/#notifications) the content and frequency of your emails.

## Scheduler

Modify the crontab

    sudo crontab -e

add this line in the crontab

    * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## LDAP Configuration

Modify .env file, and uncomment LDAP configuration :

    # Several possible types: AD, OpenLDAP, FreeIPA, DirectoryServer, Custom
    LDAP_TYPE="AD"
    # If true, LDAP's actions will be log into
    LDAP_LOGGING=true
    LDAP_CONNECTION=default
    LDAP_HOST=127.0.0.1
    # Identifiants de l'utilisateur qui se connectera au LDAP afin d'effectuer les requêtes
    LDAP_USERNAME="cn=user,dc=local,dc=com"
    LDAP_PASSWORD=secret
    LDAP_PORT=389
    LDAP_BASE_DN="dc=local,dc=com"
    LDAP_TIMEOUT=5
    LDAP_SSL=false
    LDAP_TLS=false
    # Permet de restreindre l'accès à une arborescence
    LDAP_SCOPE="ou=Accounting,ou=Groups,dc=planetexpress,dc=com"
    # Permet de restreindre l'accès à des groupes
    LDAP_GROUPS="Delivering,Help Desk"

Find more complete documentation on LDAP configuration [here](https://ldaprecord.com/docs/laravel/v2/configuration/#using-an-environment-file-env).

If you choose Custom, you have to provide a LdapUserCustom class in  `app/Ldap/LdapUserCustom.php` file, ie : 

```php
<?php

namespace App\Ldap;

use LdapRecord\Models\OpenLDAP\User as OpenLdapUser;
use LdapRecord\Models as Models;

class LdapUserCustom extends OpenLdapUser
{
        public function groups(): Models\Relations\HasMany {
                return $this->hasMany(Models\OpenLDAP\Group::class, 'memberUid', 'uid');
        }
}
```


## KeyCloak Configuration (optional)

To configure Keycloak, follow these steps:

- Open your .env file.
- Uncomment and modify the Keycloak configuration settings as follows:

```
KEYCLOAK = enable
KEYCLOAK_CLIENT_ID= # Client Name (on Keycloak)
KEYCLOAK_CLIENT_SECRET=  # Client Secret
KEYCLOAK_REDIRECT_URI=<Mercator IP Address>/login/keycloak/callback
KEYCLOAK_BASE_URL=<KeyCloak IP Address>
KEYCLOAK_REALM=   # RealM Name
```

Once you make the KEYCLOAK parametre in 'enable' you would see a bouton in Login page that redirect to keycloak server

Find more complete documentation on Keycloak configuration [here](https://www.keycloak.org/documentation).

## Apache

To configure Apache, change the properties of the mercator directory and grant the appropriate permissions to the hive with the following command:

    sudo chown -R www-data:www-data /var/www/mercator
    sudo chmod -R 775 /var/www/mercator/storage

Next, create a new Apache virtual host configuration file to serve the Mercator application:

    sudo vi /etc/apache2/sites-available/mercator.conf

Add the following lines:

```xml
<VirtualHost *:80>
    ServerName mercator.local
    ServerAdmin admin@example.com
    DocumentRoot /var/www/mercator/public
    <Directory /var/www/mercator>
        AllowOverride All
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Save and close the file when you are done. Next, enable the Apache virtual host and the rewrite module with the following command:

    sudo a2enmod rewrite
    sudo a2dissite 000-default.conf
    sudo a2ensite mercator.conf

Finally, restart the Apache service to activate the changes:

    sudo systemctl restart apache2

### HTTPS

Activate Apache SSL module

    sudo a2enmod ssl

Here is the configuration file for HTTPS

```xml
<VirtualHost *:443>
    ServerName carto.XXXXXXXX
    ServerAdmin
    DocumentRoot /var/www/mercator/public
    SSLEngine on
    SSLProtocol all -SSLv2 -SSLv3
    SSLCipherSuite HIGH:3DES:!aNULL:!MD5:!SEED:!IDEA
    SSLCertificateFile /etc/apache2/certs/certs/carto.XXXXX.crt
    SSLCertificateKeyFile /etc/apache2/certs/private/private.key
    SSLCertificateChainFile /etc/apache2/certs/certs/XXXXXCA.crt
    <Directory /var/www/mercator/public>
        AllowOverride All
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/mercator_error.log
    CustomLog ${APACHE_LOG_DIR}/mercator_access.log combined
</VirtualHost>
```

To force HTTPS redirection you have to set this parameter in .env

    APP_ENV=production

## Problems

### Restore the administrator password

    mysql mercator -e "update users set password=$(php -r "echo password_hash('n3w-p4sSw0rD.', PASSWORD_BCRYPT, ['cost' => 10]);") where id=1;"

### PHP Memory

If you generate large reports, you will need to increase the memory allocated to PHP in /etc/php/x.x/apache2/php.ini

    memory_limit = 512M

## Upgrade

Before updating the application take a backup of the database and the project.

    mysqldump mercator > mercator_backup.sql

Get the sources from GIT

    cd /var/www/mercator
    git pull

Migrate the database

    php artisan migrate

Update the libraries

    composer install

Empty caches

    php artisan config:clear && php artisan view:clear

## Non-regression tests

To run Mercator's non-regression tests, you must first install Chromium :

    sudo apt install chromium-browser

Install the dusk pluggin

    php artisan dusk:chrome-driver

Configure the environment

    cp .env .env.dusk.local

Launch the application

    php artisan serve

In another terminal, launch the tests

    php artisan dusk

or to stop on first error

    php artisan dusk --stop-on-error --stop-on-failure
