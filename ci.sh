#!/bin/bash -e

# -----------------------------------------------------------------------------
#  _                                   ___                                    
# /   _  ._ _|_ o ._       _       _    |  ._ _|_  _   _  ._ _. _|_ o  _  ._  
# \_ (_) | | |_ | | | |_| (_) |_| _>   _|_ | | |_ (/_ (_| | (_|  |_ | (_) | | 
#                                                      _|                     
# -----------------------------------------------------------------------------
# This script deploy Mercator on /tmp with mercator_test database,
# check code quality and run unit testing with dusk.
# -----------------------------------------------------------------------------

cd /tmp
rm -rf /tmp/mercator

# Project
tput setaf 2; echo "Clone git"; tput setaf 7
git clone https://github.com/dbarzin/mercator.git/

# Composer
tput setaf 2; echo "Composer update"; tput setaf 7
cd /tmp/mercator
composer update

# Drop old test database
tput setaf 2; echo "Drop test database"; tput setaf 7
mysql -e "DROP DATABASE IF EXISTS mercator_test;"
mysql -e "DROP USER IF EXISTS 'mercator_test'@'localhost';"

# Create database
tput setaf 2; echo "Create database"; tput setaf 7
mysql -e " 
CREATE DATABASE mercator_test CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'mercator_test'@'localhost' IDENTIFIED BY 's3cr3t';
GRANT ALL PRIVILEGES ON mercator_test.* TO 'mercator_test'@'localhost';
FLUSH PRIVILEGES;
"

# Config file
tput setaf 2; echo "Update config file"; tput setaf 7
cp .env.example .env
sed -i "s/\(DB_DATABASE *= *\).*/\1mercator_test/" .env 
sed -i "s/\(DB_USERNAME *= *\).*/\1mercator_test/" .env 

# Application key
tput setaf 2; echo "Generate application key"; tput setaf 7
php artisan key:generate

# Publush vendor packages
tput setaf 2; echo "Publish"; tput setaf 7
php artisan vendor:publish --all

# Migrate and seed the database
tput setaf 2; echo "Migrate and seed the application"; tput setaf 7
php artisan migrate --seed 

# Insert test data
tput setaf 2; echo "Insert test data"; tput setaf 7
pv ./mercator_data.sql | mysql mercator_test 

# Check code quality
tput setaf 2; echo "Check code quality"; tput setaf 7
php artisan insights -s

# Configure dusk
cp .env .env.dusk.local

# Start server
tput setaf 2; echo "Start server"; tput setaf 7
php artisan serve --no-reload > /dev/null &
sleep 3

# start dusk
tput setaf 2; echo "Dusk test"; tput setaf 7

php artisan dusk:chrome-driver
php artisan dusk --stop-on-error --stop-on-failure

# kill server
kill $(lsof -t -i:8000)

tput setaf 2; echo "Done."; tput setaf 7


