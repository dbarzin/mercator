FROM php:8.4-fpm-alpine

# Injecter la variable d'environnement
ARG VERSION
ENV APP_VERSION=${VERSION}

# Install system dependencies and PHP extensions in a single layer
RUN apk add --no-cache \
    git curl bash ssmtp graphviz fontconfig ttf-freefont dcron \
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
    && chgrp www /etc/passwd \
    # Configure crontab for mercator user (Laravel scheduler)
    && mkdir -p /etc/crontabs \
    && echo "* * * * * cd /var/www/mercator && php artisan schedule:run >> /dev/null 2>&1" > /etc/crontabs/mercator \
    && chmod 600 /etc/crontabs/mercator \
    && chown mercator /etc/crontabs/mercator

# Set working directory
WORKDIR /var/www/mercator

# Copy configuration files (avant le code pour meilleur cache Docker)
COPY --chown=mercator:www docker/nginx.conf /etc/nginx/http.d/default.conf
COPY --chown=mercator:www docker/supervisord.conf /etc/supervisord.conf
COPY --chown=mercator:www docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --chown=mercator:www docker/wait-for-db.sh /usr/local/bin/wait-for-db.sh

# Set permissions for scripts
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-db.sh

# Switch to application user
USER mercator:www

# Copy composer files first (pour optimiser le cache des layers)
COPY --chown=mercator:www composer.json composer.lock ./

# Install PHP dependencies via Composer
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && composer clear-cache

# Copy application source (après composer pour meilleur cache)
COPY --chown=mercator:www . .

# For Openshift environment: ensure group-writable permissions
RUN chmod -R g=u /var/www/mercator

# Copy version file and set default environment (SQLite for standalone mode)
COPY --chown=mercator:www version.txt ./version.txt
RUN cp .env.sqlite .env

# Run composer scripts after copying the full source
RUN composer run-script post-install-cmd --no-interaction || true

# Prepare SQLite database (for standalone/demo mode)
RUN mkdir -p sql && touch sql/db.sqlite

# Expose HTTP port
EXPOSE 8080

# entrypoint.sh handles all init (wait-for-db, migrate, passport, key:generate)
# then execs supervisord which manages only long-running daemons
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
