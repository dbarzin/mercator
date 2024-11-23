# Procédure d'installation de Mercator

## Configuration recommandée

- OS : Ubuntu 24.04 LTS - Server - Small user footprint
- RAM : 2G
- Disque : 10G
- VCPU 2

## Installation

Mettre à jour la distribution linux

    sudo apt update && sudo apt upgrade

Installer Apache2, GIT, Graphviz et Composer

    sudo apt install vim apache2 git graphviz composer

Installer PHP et les librairies

    sudo apt install php-zip php-curl php-mbstring php-dom php-ldap php-soap php-xdebug php-mysql php-gd libapache2-mod-php

## Project

Créer le répertoire du projet

    cd /var/www
    sudo mkdir mercator
    sudo chown $USER:$GROUP mercator

Cloner le projet depuis Github

    git clone https://www.github.com/dbarzin/mercator

## Composer

Installer les packages avec composer :

    cd /var/www/mercator
    composer update

Publier tous les actifs publiables à partir des packages des fournisseurs

    php artisan vendor:publish --all

## MySQL

Installer MySQL

    sudo apt install mysql-server

Vérifier que vous utilisez MySQL

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

Modifier les paramètres de connexion à la base de données dans le fichier .env :

    ## .env file
    DB_CONNECTION=mysql
    # DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    # Commenter DB_PORT pour pgsql
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

ou (Postgres):

    psql mercator < pg_mercator_data.sql

## Importer la base de données des CPE

Décompresse la base de données

    gzip -d mercator_cpe.sql.gz

Importer la base de données

    sudo mysql mercator < mercator_cpe.sql

## Démarrage

Démarrer l'application avec php

    php artisan serve

ou pour y accéder à l'application depuis un autre serveur

    php artisan serve --host 0.0.0.0 --port 8000

L'application est accessible à l'URL [http://127.0.0.1:8000]

    utilisateur : admin@admin.com
    mot de passe : password

## Configurer Passport

Pour l'API JSON, il faut installer Laravel Passport

    php artisan passport:install

Générer les clés de l'API

    php artisan passport:keys

Changer les permission d'accès à la clé

    sudo chown www-data:www-data storage/oauth-*.key
    sudo chmod 600 storage/oauth-*.key

## Configuration du mail

Si vous souhaitez envoyer des e-mails de notification depuis Deming.
Vous devez configurer l'accès au serveur SMTP dans .env

    MAIL_HOST='smtp.localhost'
    MAIL_PORT=2525
    MAIL_AUTH=true
    MAIL_SMTP_SECURE='ssl'
    MAIL_SMTP_AUTO_TLS=false
    MAIL_USERNAME=
    MAIL_PASSWORD=

Vous pouvez également configurer DKIM :

    MAIL_DKIM_DOMAIN = 'admin.local';
    MAIL_DKIM_PRIVATE = '/path/to/private/key';
    MAIL_DKIM_SELECTOR = 'default'; // Match your DKIM DNS selector
    MAIL_DKIM_PASSPHRASE = '';      // Only if your key has a passphrase

N'oubliez pas de [configurer](https://dbarzin.github.io/deming/config.fr/#notifications) le contenu et la fréquence d'envoi des mails.

## Sheduler

Modifier le crontab

    sudo crontab -e

ajouter cette ligne dans le crontab

    * * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1

## Configuration de la connexion LDAP

Si vous souhaitez connecter Mercator avec un Active Diretory ou un serveur LDAP,
dans le fichier .env, mettez les paramètres de connexion en décommentant les lignes :

    # Plusieurs types possibles : AD, OpenLDAP, FreeIPA, DirectoryServer
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
    LDAP_SCOPE="ou=Accounting,ou=Groups,dc=planetexpress,dc=com"
    # Allows you to restrict access to groups
    LDAP_GROUPS="Delivering,Help Desk"

Retrouvez une documentation plus complète sur la configuration de [LdapRecord](https://ldaprecord.com/docs/laravel/v2/configuration/#using-an-environment-file-env).

## Configuration de Keycloak (optionel)

Pour configurer Keycloak, suivez ces étapes :

- Ouvrez votre fichier .env.
- Décommentez et modifiez les paramètres de configuration de Keycloak comme suit :

```
KEYCLOAK = enable
KEYCLOAK_CLIENT_ID= # Client Name (on Keycloak)
KEYCLOAK_CLIENT_SECRET=  # Client Secret
KEYCLOAK_REDIRECT_URI=<Mercator IP Address>/login/keycloak/callback
KEYCLOAK_BASE_URL=<KeyCloak IP Address>
KEYCLOAK_REALM=   # RealM Name
```

Après avoir défini KEYCLOAK sur enable, un bouton apparaîtra sur la page de connexion, permettant aux utilisateurs de se connecter via Keycloak.

Pour une documentation plus complète sur la configuration de Keycloak, consultez la documentation officielle de Keycloak.

## Apache

Pour configurer Apache, modifiez les propriétés du répertoire mercator et accordez les autorisations appropriées au répertoire de stockage avec la commande suivante

    sudo chown -R www-data:www-data /var/www/mercator
    sudo chmod -R 775 /var/www/mercator/storage

Ensuite, créez un nouveau fichier de configuration d'hôte virtuel Apache pour servir l'application Mercator :

    sudo vi /etc/apache2/sites-available/mercator.conf

Ajouter les lignes suivantes :

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

Enregistrez et fermez le fichier lorsque vous avez terminé. Ensuite, activez l'hôte virtuel Apache et le module de réécriture avec la commande suivante :

    sudo a2enmod rewrite
    sudo a2dissite 000-default.conf
    sudo a2ensite mercator.conf

Enfin, redémarrez le service Apache pour activer les modifications :

    sudo systemctl restart apache2

### HTTPS

Activer le module SSL d'Apache

    sudo a2enmod ssl

Voici le fichier de configuration pour HTTPS

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

Pour forcer le redirection en HTTPS, il faut mettre ce paramètre dans le fichier .env :

    APP_ENV=production

## Problèmes

### Restaurer le mot de passe administrateur

    mysql mercator -e "update users set password=$(php -r "echo password_hash('n3w-p4sSw0rD.', PASSWORD_BCRYPT, ['cost' => 10]);") where id=1;"

### PHP Memory

Si vous générez de gros rapports, vous devrez mettre augmenter la mémoire allouée à PHP dans /etc/php/8.x/apache2/php.ini

    memory_limit = 512M

## Mise à jour

Avant de mettre à jour l'application prenez un backup de la base de données et du projet.

    mysqldump mercator > mercator_backup.sql

ou (Postgres)

    pg_dump mercator > mercator_backup.sql

Récupérer les sources de GIT

    cd /var/www/mercator
    sudo -u www-data git pull

Migrer la base de données

    sudo -u www-data php artisan migrate

Mettre à jour les librairies

    sudo -u www-data composer update

Vider les caches

    sudo -u www-data php artisan config:clear &&  php artisan view:clear

## Tests de non-régression

Pour exécuter les tests de non-régression de Mercator, vous devez d'abord instaler Chromium :

    sudo apt install chromium-browser

Installer le pluggin dusk

    sudo -u www-data php artisan dusk:chrome-driver

Configurer l'environement

    sudo -u www-data cp .env .env.dusk.local

Lancer l'application

    sudo -u www-data php artisan serve

Dans un autre terminal, lancer les tests

    sudo -u www-data php artisan dusk

## Réparer les problèmes de migraton

Mettre à jour les librairies

    sudo -u www-data composer update

Sauvegarder la base de données

    sudo mysqldump mercator \
        --ignore-table=mercator.users \
        --ignore-table=mercator.roles \
        --ignore-table=mercator.permissions \
        --ignore-table=mercator.permission_role \
        --ignore-table=mercator.role_user \
        --ignore-table=mercator.migrations \
        --no-create-db \
        --no-create-info \
        > backup_mercator_data.sql

ou (Postgres) :

    pg_dump --exclude-table=users \
      --exclude-table=roles \
      --exclude-table=permissions \
      --exclude-table=permission_role \
      --exclude-table=role_user  \
      --exclude-table=migrations  \
      mercator > backup_mercator_data.sql

Then backup database users

    sudo mysqldump mercator \
        --tables users roles role_user \
        --add-drop-table \
        > backup_mercator_users.sql

ou (Postgres):

    pg_dump --clean \
      -t users -t roles -t role_user \
      > backup_mercator_users.sql

Supprimer la base de données de Mercator

    sudo mysql -e "drop database mercator;"

ou (Postgres)

    dropdb mercator

Créer une nouvelle base de données

    sudo mysql -e "CREATE DATABASE mercator CHARACTER SET utf8 COLLATE utf8_general_ci;"

ou (Postgres)

    createdb mercator

Exécuter les migrations

    sudo -u www-data php artisan migrate --seed

Générer la clé

    sudo -u www-data php artisan key:generate

Restaurer les données

    sudo mysql mercator < backup_mercator_data.sql

ou (Postgres)

    psql mercator < backup_mercator_data.sql

Restaurer les utilisateurs

    sudo mysql mercator < backup_mercator_users.sql

ou (Postgres)

    psql mercator < backup_mercator_users.sql

Tous les problèmes de migration devraient être résolus.
