FROM php:8.3-fpm-alpine3.19

# apparently you cannot pass both env variables
# and .env file
RUN mkdir -p /var/www/mercator/sql
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/mercator/sql/db.sqlite
ENV SERVER_NAME="127.0.0.1 localhost"

# system deps
RUN apk update && apk add curl nano bash ssmtp graphviz fontconfig ttf-freefont ca-certificates sqlite sqlite-dev nginx gettext supervisor

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
  libpng \
  libpng-dev

# Install extensions
RUN docker-php-ext-install ldap gd zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Copy sources
COPY . /var/www/mercator
WORKDIR /var/www/mercator

# Add mercator:www user
RUN addgroup --g 1000 -S www && \
  adduser -u 1000 -S mercator -G www && \
  chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d

RUN cp docker/nginx.conf /etc/nginx/http.d/default.conf
RUN cp docker/supervisord.conf /etc/supervisord.conf
RUN chown -R mercator:www /etc/nginx/http.d/default.conf
RUN chown -R mercator:www /etc/supervisord.conf

USER mercator:www

# Run composer
RUN composer -n update

# Publish Laravel Vendor resources
RUN php artisan vendor:publish --all

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
