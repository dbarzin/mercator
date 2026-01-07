FROM php:8.4-fpm-alpine

# Injecter la variable d'environnement
ARG VERSION
ENV APP_VERSION=${VERSION}

# Install system dependencies and PHP extensions in a single layer
RUN apk add --no-cache \
    git curl bash ssmtp graphviz fontconfig ttf-freefont \
    ca-certificates sqlite sqlite-dev \
    postgresql-dev postgresql-client \
    mariadb-client mariadb-connector-c-dev \
    openldap-dev libzip-dev \
    libpng libpng-dev \
    nginx gettext supervisor \
    # Dépendances temporaires pour la compilation
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    # Install PHP extensions
    && docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql pdo_sqlite intl \
    zip ldap gd \
    # Cleanup build dependencies
    && apk del .build-deps \
    # Update font cache
    && fc-cache -f \
    # Install composer
    && curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer \
    # Create application user and group
    && addgroup -g 1000 -S www \
    && adduser -u 1000 -S mercator -G www \
    && mkdir -p /var/www/mercator /var/lib/nginx /var/log/nginx /etc/nginx/http.d \
    && chown -R mercator:www /var/www /var/lib/nginx /var/log/nginx /etc/nginx/http.d \
    && chmod -R g=u /var/www/ /var/lib/nginx /var/log/nginx /etc/nginx/http.d \
    && chmod g=u /etc/passwd \
    && chgrp www /etc/passwd

# Set working directory
WORKDIR /var/www/mercator

# Copy configuration files (avant le code pour meilleur cache)
COPY --chown=mercator:www docker/nginx.conf /etc/nginx/http.d/default.conf
COPY --chown=mercator:www docker/supervisord.conf /etc/supervisord.conf
COPY --chown=mercator:www docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --chown=mercator:www docker/wait-for-db.sh /usr/local/bin/wait-for-db.sh

# Set permissions for scripts
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-db.sh

# Switch to application user
USER mercator:www

# Copy composer files first (pour cache layer)
COPY --chown=mercator:www composer.json composer.lock ./

# Install PHP dependencies via Composer (sans dev dependencies en production)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && composer clear-cache

# Copy application source (après composer pour meilleur cache)
COPY --chown=mercator:www . .

# Copy version file et environment file
COPY --chown=mercator:www version.txt ./version.txt
RUN cp .env.sqlite .env

# Run composer scripts après avoir copié le code
RUN composer run-script post-install-cmd --no-interaction || true

# Prepare SQLite database
RUN mkdir -p sql && touch sql/db.sqlite

# Expose HTTP port
EXPOSE 8080

# Entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
