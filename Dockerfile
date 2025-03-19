FROM php:8.3-fpm-alpine3.19

# system deps
RUN apk update && apk add git curl nano bash ssmtp graphviz fontconfig ttf-freefont ca-certificates sqlite sqlite-dev nginx gettext supervisor
RUN apk add postgresql-dev postgresql-client mariadb-client mariadb-connector-c-dev

# run font cache
RUN fc-cache -f

# php deps
RUN apk add php-zip \
  php-curl \
  php-mbstring \
  php-dom \
  php-ldap \
  php-soap \
  php-xdebug \
  php-sqlite3 \
  php-gd \
  php-xdebug \
  php-gd \
  php-pdo php-pdo_sqlite \
  php-fileinfo \
  php-simplexml php-xml php-xmlreader php-xmlwriter \
  php-tokenizer \
  libzip-dev \
  openldap-dev \
  nodejs \
  npm \
  libpng \
  libpng-dev

# Install PHP extensions
RUN docker-php-ext-install gd zip ldap pdo pdo_mysql pdo_pgsql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Add mercator:www user
RUN addgroup --g 1000 -S www && \
  adduser -u 1000 -S mercator -G www && \
  chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d && \
  chmod -R g=u /var/www/ /var/lib/nginx /var/log/nginx /etc/nginx/http.d

# Clone sources from Github
#WORKDIR /var/www/
#RUN git clone https://github.com/dbarzin/mercator.git/
RUN mkdir /var/www/mercator
WORKDIR /var/www/mercator
COPY . .

# Copy config files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# change owner
RUN chown -R mercator:www /var/www/mercator && \
  chmod -R g=u /var/www/mercator && \
  chmod +x /usr/local/bin/entrypoint.sh

RUN chmod g=u /etc/passwd && \
  chgrp www /etc/passwd

# Now work with Mercator user
USER mercator:www

# Run Composer
RUN composer install

# Node Package Management
RUN npm install
RUN npm run build

# Create database folder
RUN mkdir sql

# Create the SQLite database file
RUN touch sql/db.sqlite

# copy environement varaibles file
RUN cp .env.sqlite .env

# Start surpervisord
EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
