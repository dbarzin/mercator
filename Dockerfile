FROM php:8.2-fpm-alpine3.16

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
RUN apk add php8-zip \
  php8-curl \
  php8-mbstring \
  php8-dom php8-ldap \
  php8-soap \
  php8-xdebug \
  php8-sqlite3 \
  php8-gd \
  php8-xdebug \
  php8-gd \
  php8-pdo php8-pdo_sqlite \
  php8-fileinfo \
  php8-simplexml php8-xml php8-xmlreader php8-xmlwriter \
  php8-tokenizer \
  php8-ldap \
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
