# Procédure d'installation de Mercator

## Configuration recommandée

- OS : Ubuntu 20.04 LTS
- RAM : 2G
- Disque : 50G
- VCPU 2

## Installation 

Mettre à jour la distribution linux

    sudo apt update && sudo apt upgrade

Insatller PHP et quelques librairies

    sudo apt install php php-zip php-curl php-mbstring php-dom php-ldap php-soap php-xdebug php-mysql php-gd

Installer Graphviz

    sudo apt install graphviz

Installer GIT

    sudo apt install git

## Project

Créer le répertoire du projet

    cd /var/www
    sudo mkdir mercator
    sudo chown $USER:$GROUP mercator

Cloner le projet depuis Github

    git clone https://www.github.com/dbarzin/mercator

## Composer

Installer Composer : [Install Composer globally](https://getcomposer.org/download/).

    sudo mv composer.phar /usr/local/bin/composer

Installer les packages avec composer :

    cd /var/www/mercator
    composer update

Publier tous les actifs publiables à partir des packages des fournisseurs

    php artisan vendor:publish --all

## MySQL

Installer MySQL

    sudo apt install mysql-server

Vérifier que vous utilisez MySQL et pas MariaDB (Mercator ne fonctionne pas avec MariaDB).

    sudo mysql --version

Lancer MySQL avec les droits root

    sudo mysql

Créer la base de données _mercator_ et l'utilisateur _mercator_user_

    CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;
    CREATE USER 'mercator_user'@'localhost' IDENTIFIED BY 's3cr3t';
    GRANT ALL PRIVILEGES ON mercator.* TO 'mercator_user'@'localhost';
    GRANT PROCESS ON *.* TO 'mercator_user'@'localhost';

    FLUSH PRIVILEGES;
    EXIT;

## Configuration

Créer un fichier .env dans le répertoire racine du projet :

    cd /var/www/mercator

    cp .env.example .env

Mettre les paramètre de connexion à la base de données :

    vi .env

    ## .env file
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mercator
    DB_USERNAME=mercator_user
    DB_PASSWORD=s3cr3t


## Créer la base de données

Exécuter les migrations

    php artisan migrate --seed

Remarque: la graine est importante (--seed), car elle créera le premier utilisateur administrateur pour vous.

Générer la clé de l'application

    php artisan key:generate

Vider la cache

    php artisan config:clear

Pour importer la base de données de test (facultatif)

    sudo mysql mercator < mercator_data.sql

Démarrer l'application avec php

    php artisan serve

L'application est accessible à l'URL [http://127.0.0.1:8000]
    utilisateur : admin@admin.com
    mot de passe : password

## Configuration du mail

Si vous souhaitez envoyer des mails de notification depuis Mercator.

Installer postfix et mailx

    sudo apt install postfix mailx

Configurer postfix

    sudo dpkg-reconfigure postfix

Envoyer un mail de test avec

    echo "Test mail body" | mailx -r "mercator@yourdomain.local" -s "Subject Test" yourname@yourdomain.local

## Sheduler

Modifier le crontab

    crontab -e

ajouter cette ligne dans le crontab

    * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## Apache

Pour configurer Apache, modifiez les propriétés du répertoire mercator et accordez les autorisations appropriées au répertoire de stockage avec la commande suivante

    chown -R www-data:www-data /var/www/mercator
    chmod -R 775 /var/www/mercator/storage

Ensuite, créez un nouveau fichier de configuration d'hôte virtuel Apache pour servir l'application Mercator :

    vi /etc/apache2/sites-available/mercator.conf

Ajouter les lignes suivantes :

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

Enregistrez et fermez le fichier lorsque vous avez terminé. Ensuite, activez l'hôte virtuel Apache et le module de réécriture avec la commande suivante :

    a2ensite mercator.conf
    a2enmod rewrite

Enfin, redémarrez le service Apache pour activer les modifications :

    systemctl restart apache2

## Problèmes

### Restaurer le mot de passe administrateur

    mysql mercator -e "update users set password=$(php -r "echo password_hash('n3w-p4sSw0rD.', PASSWORD_BCRYPT, ['cost' => 10]);") where id=1;"

### PHP Memory

Si vous générez de gros rapports, vous devrez mettre augmenter la mémoire allouée à PHP dans /etc/php/7.4/apache2/php.ini

    memory_limit = 512M

## Mise à jour

Avant de mettre à jour l'application prenez un backup de la base de données et du projet.

    mysqldump mercator > mercator_backup.sql

Récupérer les sources de GIT

    cd /var/www/mercator
    git pull

Migrer la base de données

    php artisan migrate

Mettre à jour les librairies

    composer update

Vider les caches

    php artisan config:clear &&  php artisan view:clear


## Tests de non-régression

Pour exécuter les tests de non-régression de Mercator, vous devez d'abord instaler Chromium :

    sudo apt install chromium-browser

Installer le pluggin dusk

    php artisan dusk:chrome-driver

Configurer l'environement

    cp .env .env.dusk.local

Lancer l'application

    php artisan serve

Dans un autre terminal, lancer les tests

    php artisan dusk

## Réparer les problèmes de migraton

Mettre à jour les librairies

    composer update

Sauvegarder la base de données

    mysqldump mercator \
        --ignore-table=mercator.users \
        --ignore-table=mercator.roles \
        --ignore-table=mercator.permissions \
        --ignore-table=mercator.permission_role \
        --ignore-table=mercator.role_user \
        --ignore-table=mercator.migrations \
        --no-create-db \
        --no-create-info \
        > backup_mercator_data.sql

Then backup database users

    mysqldump mercator \
        --tables users roles role_user \
        --add-drop-table \
        > backup_mercator_users.sql

Supprimer la base de données de Mercator

    sudo mysql -e "drop database mercator;"

Créer une nouvelle base de données

    sudo mysql -e "CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;"

Exécuter les migrations

    php artisan migrate --seed

Générer la clé

    php artisan key:generate

Restaurer les données

    mysql mercator < backup_mercator_data.sql

Restaurer les utilisateurs

    mysql mercator < backup_mercator_users.sql

Tous les problèmes de migration devraient être résolus.
