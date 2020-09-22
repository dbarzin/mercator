# Procédure d'installation de Mercator

Update and upgrade distribution

    sudo apt update
    sudo apt upgrade

Install PHP and other packages

    sudo apt install php php-zip php-curl php-mbstring php-dom php-ldap php-soap php-xdebug php-mysql

## GIT

Create your Laravel project in user directory.

    cd /var/lib
    git clone https://www.github.com/dbarzin/mercator

## Composer

[Install Composer globally](https://getcomposer.org/download/).

    sudo mv composer.phar /usr/local/bin/composer

Install the packages

    cd /var/lib/mercator
    composer install

## MySQL

Install MySQL

    sudo apt install mysql-server

Create a MySQL (or SQLite) database to use with your Laravel project.

    sudo mysql

    CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;
    CREATE USER 'mercator_user'@'localhost' IDENTIFIED BY 's3cr3t';
    GRANT ALL PRIVILEGES ON mercator.* TO 'mercator_user'@'localhost';
    GRANT PROCESS ON *.* TO 'mercator_user'@'localhost';

    FLUSH PRIVILEGES;
    EXIT;

## Configure

Update the .env file in the root directory of your project with the appropriate parameter values to match your new database:

    cd /var/lib/mercator

    cp .env.example .env

    vi .env

    ## .env file
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mercator
    DB_USERNAME=mercator_user
    DB_PASSWORD=s3cr3t


## Create the database

Run migration

    php artisan migrate --seed 

Notice: seed is important, because it will create the first admin user for you. 

Generate Keys

    cd /var/lib/mercator 
    php artisan key:generate

Cache

Rebuild the cache

    php artisan config:cache

## Test

start the application

    php artisan serve

Open your browser with the URL [http://127.0.0.1:8000]
    user : admin@admin.com
    password : password




