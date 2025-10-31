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

Install Apache2, GIT, Graphviz and Composer

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

    sudo mysql mercator < database/data/mercator_data_en.sql

## CPE Database (optional)

An Artisan command **`mecator:cpe-sync`**  **synchronizes CPEs (Common Platform Enumeration)** from the NVD (National
Vulnerability Database) and populates the
`cpe_vendors`, `cpe_products`, and `cpe_versions` tables.

### Scheduling

The command is **scheduled daily at 03:30** via Laravel’s scheduler.
➡️ If your **Laravel scheduler is already configured** (cron running `php artisan schedule:run`), **no
action is required**. Synchronization will happen automatically.

### Configuration

Add the following parameters to your `.env` file:

```dotenv
# NVD CPE 2.0 API endpoint
CPE_API_URL=https://services.nvd.nist.gov/rest/json/cpes/2.0

# (Optional but recommended) NVD API key to benefit from higher request quotas
NVD_API_KEY=
```

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

Don't forget to [configure](https://dbarzin.github.io/deming/config/#notifications) the content and frequency of your
emails.

## Scheduler

Modify the crontab

    sudo crontab -e

add this line in the crontab

    * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## LDAP / LDAPRecord configuration (optional)

This section lets you enable LDAP authentication in Deming using **LDAPRecord v2**. It works with **Active Directory**
*and* **OpenLDAP**, and can coexist with local (database) authentication.

### Prerequisites

The PHP LDAP extension must be installed and enabled.

```bash
sudo apt-get install php-ldap
sudo systemctl restart apache2
```

### Environment

Add / adjust the following variables:

```dotenv
# Enable LDAP in Deming (hybrid mode)
LDAP_ENABLED=true                 # Turn LDAP authentication on
LDAP_FALLBACK_LOCAL=true          # If LDAP fails, try local DB auth
LDAP_AUTO_PROVISION=false         # Auto-create the local user after a successful LDAP bind

# LDAP server connection
LDAP_HOST=ldap.example.org
LDAP_PORT=389                     # 389 (StartTLS) or 636 (LDAPS)
LDAP_BASE_DN=dc=example,dc=org
LDAP_USERNAME=cn=admin,dc=example,dc=org   # Service account used for searches
LDAP_PASSWORD=********
LDAP_TLS=true                     # StartTLS (recommended when using port 389)
LDAP_SSL=false                    # true if you use ldaps:// on port 636
LDAP_TIMEOUT=5                    # (optional)

# Candidate attributes to identify the username entered in the form
# Order matters: the first match wins.
# OpenLDAP: uid, cn, mail ; AD: sAMAccountName, userPrincipalName, mail
LDAP_LOGIN_ATTRIBUTES=uid,cn,mail,sAMAccountName,userPrincipalName
```

# User must be member of this LDAP group

LDAP_GROUP=Mercator

**Examples**

* OpenLDAP (typical user DN: `uid=jdupont,ou=people,dc=example,dc=org`):

  ```dotenv
  LDAP_TLS=true
  LDAP_SSL=false
  LDAP_LOGIN_ATTRIBUTES=uid,cn,mail
  LDAP_GROUP=Merator
  ```

* Active Directory (UPN: `jdupont@example.org`, sAM: `jdupont`):

  ```dotenv
  LDAP_TLS=true
  LDAP_SSL=false
  LDAP_LOGIN_ATTRIBUTES=sAMAccountName,userPrincipalName,mail,cn
  LDAP_USERNAME=EXAMPLE\svc_ldap   # or the full DN of the service account
  ```

After changing `.env`:

```bash
php artisan config:clear
php artisan optimize:clear
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
KEYCLOAK_AUTO_PROVISIONNING= # enable/disable the automatic creation of users (possibles values: true/false. defaults to 'true')
KEYCLOAK_BTN_LABEL= # set the Keycloak login button label (defaults to 'Keycloak')
```

Once you make the KEYCLOAK parametre in 'enable' you would see a bouton in Login page that redirect to keycloak server

Find more complete documentation on Keycloak configuration [here](https://www.keycloak.org/documentation).

## Apache

To configure Apache, change the properties of the mercator directory and grant the appropriate permissions to the hive
with the following command:

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

Save and close the file when you are done. Next, enable the Apache virtual host and the rewrite module with the
following command:

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

### PHP-FPM Variant with Apache

This method allows you to run Mercator with PHP-FPM (FastCGI Process Manager) and Apache's `mpm_event` module, offering
better performance than the classic `mod_php` setup.

#### 1. Install PHP-FPM

```bash
sudo apt install php-fpm
```

#### 2. Configure Apache

Disable incompatible modules (`mod_php` and `mpm_prefork`):

```bash
sudo a2dismod mpm_prefork phpX.Y
```

> Replace `phpX.Y` with your installed PHP version (e.g., `php8.2`).

Enable the required modules for PHP-FPM support:

```bash
sudo a2enmod proxy_fcgi mpm_event
```

#### 3. Review the `mpm_event` Configuration

Edit the `/etc/apache2/mods-enabled/mpm_event.conf` file to adjust settings based on your server’s load. See the
official documentation for details:  
<https://httpd.apache.org/docs/current/mod/event.html>

#### 4. Example HTTP VirtualHost

```apache
<VirtualHost *:80>
    ServerName mercator.local
    ServerAdmin admin@example.com

    DocumentRoot /var/www/mercator/public

    <Directory /var/www/mercator>
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch "\.php$">
        SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost/"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/mercator_error.log
    CustomLog ${APACHE_LOG_DIR}/mercator_access.log combined
</VirtualHost>
```

#### 5. Example HTTPS VirtualHost

```apache
<VirtualHost *:443>
    ServerName carto.example.com
    ServerAdmin admin@example.com

    DocumentRoot /var/www/mercator/public

    SSLEngine on
    SSLProtocol all -SSLv2 -SSLv3
    SSLCipherSuite HIGH:!aNULL:!MD5

    SSLCertificateFile /etc/apache2/certs/carto.example.com.crt
    SSLCertificateKeyFile /etc/apache2/certs/private.key
    SSLCertificateChainFile /etc/apache2/certs/CA.crt

    <Directory /var/www/mercator/public>
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch "\.php$">
        SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost/"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/mercator_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/mercator_ssl_access.log combined
</VirtualHost>
```

> **Notes:**
> - Adjust the SSL certificate paths to match your environment.
> - Make sure the PHP-FPM socket path is correct (e.g., `/run/php/php-fpm.sock` or `/run/php/php8.2-fpm.sock`, depending
    on your PHP version).

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

Install PHP libraries

    composer install

Empty caches

    php artisan config:clear && php artisan view:clear

## 🧪 Non-Regression Tests

Mercator uses **[Pest](https://pestphp.com)** for non-regression testing.
These tests ensure that application features continue to work as expected after updates or refactoring.

### Configure the environment

Create a dedicated testing environment file:

```bash
cp .env .env.testing
```

Then update the database configuration in `.env.testing`, for example:

```env
APP_ENV=testing
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Execute the test suite

Run all non-regression tests with Pest:

```bash
php artisan test
```

or directly:

```bash
./vendor/bin/pest
```

### Stop on first failure

To stop execution after the first error or failure:

```bash
./vendor/bin/pest --stop-on-failure
```

### Run a specific test file or group

You can target a specific test or directory:

```bash
./vendor/bin/pest tests/Feature/Api
```

or use groups/tags:

```bash
./vendor/bin/pest --group=api
```

---

✅ **Notes**

* Chromium and Laravel Dusk are **no longer required**.
* All tests are now handled by **Pest** with Laravel’s built-in testing utilities.
* Test reports and coverage can be generated with:

```bash
XDEBUG_MODE=coverage ./vendor/bin/pest --coverage
```

