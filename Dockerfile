FROM php:8.3-fpm-alpine3.19

# Add packages
RUN apk update && \
    apk add git curl nano bash ssmtp graphviz fontconfig ttf-freefont ca-certificates sqlite sqlite-dev nginx gettext supervisor \
    php-zip \
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
RUN docker-php-ext-install gd zip ldap

# run font cache
RUN fc-cache -f

# Install composer
RUN curl -sS https://getcomposer.org/installer | php  && \
    chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Add mercator:www user
RUN addgroup --g 1000 -S www && \
  adduser -u 1000 -S mercator -G www && \
  chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d

# Clone sources from Github
WORKDIR /var/www/
RUN git clone https://www.github.com/dbarzin/mercator
WORKDIR /var/www/mercator

# Copy config files
RUN cp docker/nginx.conf /etc/nginx/http.d/default.conf && \
    cp docker/supervisord.conf /etc/supervisord.conf && \
    chown -R mercator:www /var/www/mercator

# Now work with Mercator user
USER mercator:www

# Run composer and publish Laravel Vendor resources
RUN composer -n update && php artisan vendor:publish --all

# Create database folder and copy environement varaibles file
# It must be done at the end
RUN cp .env.sqlite .env && mkdir sql && touch sql/db.sqlite

# Start surpervisord
EXPOSE 8000
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
