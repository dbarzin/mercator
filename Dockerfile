FROM php:8.2-fpm-alpine3.16

# apparently you cannot pass both env variables
# and .env file
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/mercator/db.sqlite
ENV SERVER_NAME="127.0.0.1 localhost"

# system deps
RUN apk update && apk add curl nano bash ssmtp graphviz ca-certificates sqlite sqlite-dev postgresql12 postgresql12-dev nginx gettext supervisor

# php deps
RUN apk add php8-zip \
  php8-curl \
  php8-mbstring \
  php8-dom php8-ldap \
  php8-soap \
  php8-xdebug \
  php8-mysqli \
  php8-sqlite3 \
  php8-pgsql \
  php8-gd \
  php8-xdebug \
  php8-gd \
  php8-pdo php8-pdo_sqlite php8-pdo_mysql php8-pdo_pgsql \
  php8-fileinfo \
  php8-simplexml php8-xml php8-xmlreader php8-xmlwriter \
  php8-tokenizer \
  composer

RUN docker-php-ext-install pgsql pdo_pgsql

# sources
COPY . /var/www/mercator
WORKDIR /var/www/mercator

# the sqlite file must exist
# RUN touch ${DB_DATABASE}


# add mercator:www user
RUN addgroup -S www && \
  adduser -S mercator -G www && \
  chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d

# COPY nginx.conf /etc/nginx/http.d/mercator.conf
# RUN chown -R mercator:www 

RUN mkdir -p /etc/nginx/templates && cp docker/nginx.conf /etc/nginx/templates/mercator.conf
RUN cp docker/supervisord.conf /etc/supervisord.conf 

USER mercator:www

# Install mercator deps
RUN set -ex ; \
    apt-get update ; \
    apt-get install -y git zip ; \
    composer -n validate --strict ; \
    composer -n install --no-scripts --ignore-platform-reqs --no-dev

EXPOSE 8000

CMD ["/usr/bin/supervisord"]
