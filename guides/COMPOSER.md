Mettre à jour composer
====

# 1. Supprimer complètement l'ancien Composer

    sudo apt remove --purge composer
    sudo rm -f /usr/bin/composer /usr/local/bin/composer

# 2. Télécharger et installer la dernière version

    cd /tmp
    curl -sS https://getcomposer.org/installer | php

# 3. Installer globalement

    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer

# 4. Recharger le cache des commandes

    hash -r

# 5. Vérifier que c'est bien la nouvelle version

    which composer
    composer --version



