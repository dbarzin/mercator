FROM php:8.3-fpm-alpine3.19

# system deps
RUN apk update && apk add git curl nano bash ssmtp graphviz fontconfig ttf-freefont ca-certificates sqlite sqlite-dev nginx gettext supervisor

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

# Install PHP extensions
RUN docker-php-ext-install gd zip ldap pdo pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Add mercator:www user
RUN addgroup --g 1000 -S www && \
  adduser -u 1000 -S mercator -G www && \
  chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d

# Clone sources from Github
WORKDIR /var/www/
RUN git clone https://github.com/dbarzin/mercator.git/
WORKDIR /var/www/mercator

# Copy config files
RUN cp docker/nginx.conf /etc/nginx/http.d/default.conf
RUN cp docker/supervisord.conf /etc/supervisord.conf

# change owner
RUN chown -R mercator:www /var/www/mercator

# Now work with Mercator user
USER mercator:www

# Run composer
RUN composer -n update

# Publish Laravel Vendor resources
RUN php artisan vendor:publish --all

# Create database folder
RUN mkdir sql

# copy environement varaibles file
RUN cp .env.sqlite .env

# Start surpervisord
EXPOSE 8000
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
