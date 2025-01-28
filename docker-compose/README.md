This docker-compose folder is intented to launch a SIMPLE & PERSISTENT mercator instance behind nginx reverse https proxy.

**All PATH below are relative to docker-compose folder.**

# Pre-requisite
## Obtain a valid certificate and key files from your organisation. If you don't have you can generate self-signed with these commands (replace with your own domain name) :
```
openssl genpkey -algorithm RSA -out ./PV/nginx/certs/mercator.mycompany.com.key
openssl req -new -x509 -key ./PV/nginx/certs/mercator.mycompany.com.key -out ./PV/nginx/certs/mercator.mycompany.com.crt
```

# Initialize your personal files
## Create persistent sqlite database
```
touch ./PV/mercator/db.sqlite && chmod a+w ./PV/mercator/db.sqlite
```

## Put your mercator's https certificates in the nginx PV folder
```
cp <source .crt and .key files> ./PV/nginx/certs
```

## If you need your company's ca root certificate in Nginx because you want to also reverse proxy another https site
Put CA certificates in ./PV/nginx

Then, uncomment associated volumes in docker-compose.yml, under reverse-proxy service section


# Modify the configuration to match your domain name
Check all CONFIG lines in the following files, and adapt it to your domain name
```
./docker-compose.yml
./env/mercator.env
./PV/nginx/nginx.conf
```

# There we go!
```
docker-compose up -d
```

# If USE_DEMO_DATA is disabled, you need to create a first admin account with :
```
php artisan migrate:fresh --seed --force
```

