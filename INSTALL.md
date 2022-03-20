# Mercator installation procedure

## Recommended configuration

- OS : Ubuntu 21.10
- RAM : 2G
- Disk : 50G
- VCPU 2

## Installation 

Update the linux distribution

    sudo apt update && sudo apt upgrade

Install Composer, git, Graphviz

    sudo apt install git graphviz composer

Install some PHP libraries

    sudo apt install php-zip php-curl php-mbstring php-dom php-ldap php-soap php-xdebug php-mysql php-gd

## Project

Create the project directory

    cd /var/www
    sudo mkdir mercator
    sudo chown $USER:$GROUP mercator

Clone the project from Github

    git clone https://www.github.com/dbarzin/mercator

## Composer

Install the packages with composer :

    cd /var/www/mercator
    composer update

Publish all publishable assets from vendor packages

    php artisan vendor:publish --all

## MySQL

Install MySQL

    sudo apt install mysql-server

Make sure you are using MySQL and not MariaDB (Mercator does not work with MariaDB).

    sudo mysql --version

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
    DB_HOST=127.0.0.1
    DB_PORT=3306
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

Start the application with php

    php artisan serve

The application is accessible at the URL [http://127.0.0.1:8000]
    user : admin@admin.com
    password : password

## Mail configuration

If you want to send notification mails from Mercator.

Install postfix and mailx

    sudo apt install postfix mailx

Configure postfix

    sudo dpkg-reconfigure postfix

Send a test mail with

    echo "Test mail body" | mailx -r "mercator@yourdomain.local" -s "Subject Test" yourname@yourdomain.local

## Sheduler

Modify the crontab

    crontab -e

add this line in the crontab

    * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## Apache

To configure Apache, change the properties of the mercator directory and grant the appropriate permissions to the hive with the following command

    chown -R www-data:www-data /var/www/mercator
    chmod -R 775 /var/www/mercator/storage

Next, create a new Apache virtual host configuration file to serve the Mercator application:

    vi /etc/apache2/sites-available/mercator.conf

Add the following lines:

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

Save and close the file when you are done. Next, enable the Apache virtual host and the rewrite module with the following command:

    a2ensite mercator.conf
    a2enmod rewrite

Finally, restart the Apache service to activate the changes:

    systemctl restart apache2

## Problems

### Restore the administrator password

    mysql mercator -e "update users set password=$(php -r "echo password_hash('n3w-p4sSw0rD.', PASSWORD_BCRYPT, ['cost' => 10]);") where id=1;"

### PHP Memory

If you generate large reports, you will need to increase the memory allocated to PHP in /etc/php/7.4/apache2/php.ini

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

    compose update

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

## Repair the problems of migraton

Update the libraries

    compose update

Backup the database

    mysqldump mercator \
        --complete-insert \
        --no-create-db \
        --no-create-info \
        --ignore-table=mercator.users \
        --ignore-table=mercator.roles \
        --ignore-table=mercator.permissions \
        --ignore-table=mercator.permission_role \
        --ignore-table=mercator.role_user \
        --ignore-table=mercator.migrations \
        > backup_mercator_data.sql

Then backup database users

    mysqldump mercator \
        --complete-insert \
        --tables users roles role_user
        --add-drop-table \
        > backup_mercator_users.sql

Delete the Mercator database

    sudo mysql -e "drop database mercator;"

Create a new database

    sudo mysql -e "CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;"

Run the migrations

    php artisan migrate --seed

Generate the key

    php artisan key:generate

Restore the data and fix errors

    mysql mercator < backup_mercator_data.sql

Restore users

    mysql mercator < backup_mercator_users.sql

All migration issues should be resolved.


