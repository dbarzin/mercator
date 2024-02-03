# Mercator installation procedure

## Recommended configuration

- OS: RedHat 9.3 - Server
- RAM: 2G
- Disk: 10G
- VCPU2

## Facility

Update linux distribution

sudo dnf update && sudo dnf upgrade

Install Httpd, GIT, Graphviz, Vim and Php

sudo dnf install vim httpd git graphviz php

Check that the php 8.1 module is available

sudo dnf module list php

Install php 8.1

sudo dnf module reset php
sudo dnf module enable php:8.1
sudo dnf install php

Check php version

php --version

Install PHP and libraries

sudo dnf install php-json php-dbg php-mysqlnd php-gd php-zip php-curl php-mbstring php-xml php-ldap php-soap

Install Composer

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

Verify that Composer is installed

compose --version

##Project

Create the project directory

cd /var/www
sudo mkdir mercator
sudo chown $USER:$GROUP mercator

Clone the project from Github

git clone https://www.github.com/dbarzin/mercator


## Compose

Update composer

cd /var/www/mercator
compose self-update

Install packages with composer

composer update

Publish all publishable assets from vendor packages

php artisan vendor:publish --all

## MySQL

Install MySQL

sudo dnf install mysql-server

Verify that you are using MySQL

sudo mysql --version

Start the MySQL service

systemctl start mysqld.service
systemctl enable mysqld.service

Launch MySQL with root rights

sudo mysql

Create the _mercator_ database and the _mercator_user_ user

CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'mercator_user'@'localhost' IDENTIFIED BY 's3cr3t';
GRANT ALL PRIVILEGES ON mercator.* TO 'mercator_user'@'localhost';
GRANT PROCESS ON *.* TO 'mercator_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;

## Configuration

Create an .env file in the project root directory:

cd /var/www/mercator
cp .env.example .env

Edit database connection settings in the .env file:

## .env file
DB_CONNECTION=mysql
#DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=3306
# Comment DB_PORT for pgsql
DB_DATABASE=mercator
DB_USERNAME=mercator_user
DB_PASSWORD=s3cr3t

## Create the database

Run migrations

php artisan migrate --seed

Note: The seed is important (--seed), because it will create the first admin user for you.

Generate app key

php artisan key:generate

Clear cache

php artisan config:clear

To import the test database (optional)

sudo mysql mercator < mercator_data.sql

or (Postgres):

     psql mercator < pg_mercator_data.sql

## Import the CPE database

Unpack the database

     gzip -d mercator_cpe.sql.gz

Import database

     sudo mysql mercator < mercator_cpe.sql

## Startup

Start application with php

     php artisan serve

or to access the application from another server

     php artisan serve --host 0.0.0.0 --port 8000

The application is accessible at URL [http://127.0.0.1:8000]

     user: admin@admin.com
     password: password

## Configure Passport

For the JSON API, you need to install Laravel Passport

     php artisan passport:install

Generate API keys

     php artisan passport:keys

Change key access permissions

     sudo chown apache:apache storage/oauth-*.key
     sudo chmod 600 storage/oauth-*.key

## Email configuration

If you want to send notification emails from Mercator.

Install postfix and mailx

     sudo apt install postfix mutt

Configure postfix

     sudo vi /etc/postfix/main.cf

Restart the Postfix service

sudo systemctl restart postfix

Send a test email with

     echo "Test mail body" | mailx -r "mercator@yourdomain.local" -s "Subject Test" yourname@yourdomain.local

## Sheduler

Edit crontab

     sudo crontab -e

add this line to the crontab

     * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## Configuring the LDAP connection

If you want to connect Mercator with an Active Directory or an LDAP server,
in the .env file, put the connection parameters by uncommenting the lines:

     # Several possible types: AD, OpenLDAP, FreeIPA, DirectoryServer
     LDAP_TYPE="AD"
     # If true, LDAP actions will be written to the application's default log file
     LDAP_LOGGING=true
     LDAP_CONNECTION=default
     LDAP_HOST=127.0.0.1
     # Identifiers of the user who will connect to the LDAP in order to perform queries
     LDAP_USERNAME="cn=user,dc=local,dc=com"
     LDAP_PASSWORD=secret
     LDAP_PORT=389
     LDAP_BASE_DN="dc=local,dc=com"
     LDAP_TIMEOUT=5
     LDAP_SSL=false
     LDAP_TLS=false
     # Allows you to restrict access to a tree structure
     LDAP_SCOPE="ou=Accounting,ou=Grou
