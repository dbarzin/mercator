FROM php:8.2-fpm-alpine3.16

# apparently you cannot pass both env variables
# and .env file
RUN mkdir -p /var/www/mercator/sql
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/mercator/sql/db.sqlite
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
  php8-tokenizer
  
RUN apk update && \
    apk add --no-cache \
    libzip-dev \
    && docker-php-ext-install zip

RUN docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

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

RUN cp docker/nginx.conf /etc/nginx/http.d/default.conf
RUN cp docker/supervisord.conf /etc/supervisord.conf
RUN chown -R mercator:www /etc/nginx/http.d/default.conf


USER mercator:www

# Install mercator deps
RUN set -ex ; \
    composer -n validate --strict ; \
    composer -n install --no-scripts --ignore-platform-reqs --no-dev

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
