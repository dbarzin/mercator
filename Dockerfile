FROM php:8.3-fpm-alpine3.19

COPY version.txt ./version.txt

# Injecter la variable d’environnement
ARG VERSION
ENV APP_VERSION=${VERSION}

# Install system dependencies
RUN apk add --no-cache \
    git curl bash ssmtp graphviz fontconfig ttf-freefont \
    ca-certificates sqlite sqlite-dev \
    postgresql-dev postgresql-client \
    mariadb-client mariadb-connector-c-dev \
    openldap-dev libzip-dev \
    libpng libpng-dev \
    nginx gettext supervisor

# Update font cache
RUN fc-cache -f

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql pdo_sqlite intl \
    zip ldap gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && \
    chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Create application user and group
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S mercator -G www && \
    mkdir -p /var/www/mercator && \
    chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d && \
    chmod -R g=u /var/www/ /var/lib/nginx /var/log/nginx /etc/nginx/http.d

# Set working directory
WORKDIR /var/www/mercator

# Copy application source
COPY . .

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set permissions and ownership
RUN chown -R mercator:www /var/www/mercator && \
    chmod -R g=u /var/www/mercator && \
    chmod +x /usr/local/bin/entrypoint.sh && \
    chmod g=u /etc/passwd && \
    chgrp www /etc/passwd

# Switch to application user
USER mercator:www

# Install PHP dependencies via Composer
RUN composer install --no-interaction --prefer-dist

# Prepare SQLite database
RUN mkdir -p sql && touch sql/db.sqlite

# Copy environment file
RUN cp .env.sqlite .env

# Expose HTTP port
EXPOSE 8080

# Entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
