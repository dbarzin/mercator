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
    nginx gettext supervisor su-exec \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    && docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql pdo_sqlite intl \
    zip ldap gd \
    && apk del .build-deps \
    && fc-cache -f \
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
    # php-fpm : répertoires de run accessibles à mercator
    && mkdir -p /var/run/php-fpm \
    && chown mercator:www /var/run/php-fpm \
    && true

# PHP error reporting for Docker logs (static — created at build time)
RUN printf '; Assure que les erreurs PHP fatales remontent dans les logs Docker\nlog_errors = On\nerror_log = /proc/self/fd/2\nerror_reporting = E_ALL\ndisplay_errors = Off\n' \
    > /usr/local/etc/php/conf.d/mercator-errors.ini

# Set working directory
WORKDIR /var/www/mercator

# Copy configuration files
COPY --chown=root:root docker/nginx-main.conf /etc/nginx/nginx.conf
COPY --chown=mercator:www docker/nginx.conf /etc/nginx/http.d/default.conf
COPY --chown=root:root docker/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=mercator:www docker/supervisord.conf /etc/supervisord.conf
COPY --chown=mercator:www docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --chown=mercator:www docker/wait-for-db.sh /usr/local/bin/wait-for-db.sh

RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-db.sh

# Copy composer files first (pour optimiser le cache des layers)
COPY --chown=mercator:www composer.json composer.lock ./

# Install PHP dependencies via Composer
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && composer clear-cache

# Copy application source
COPY --chown=mercator:www . .

# Permissions groupe-writable (OpenShift) + répertoires runtime pré-créés
RUN chmod -R g=u /var/www/mercator \
    && mkdir -p \
       storage/app/purifier \
       storage/app/public \
       storage/framework/cache/data \
       storage/framework/sessions \
       storage/framework/views \
       storage/logs \
    && touch storage/logs/laravel.log \
    && chown -R mercator:www storage \
    && chmod -R g=u storage

# Copy version file and set default environment (SQLite for standalone mode)
COPY --chown=mercator:www version.txt ./version.txt
RUN cp .env.sqlite .env \
    && chown mercator:www .env

# Run composer scripts after copying the full source
RUN composer run-script post-install-cmd --no-interaction || true

# Prepare SQLite database (for standalone/demo mode)
RUN mkdir -p sql && touch sql/db.sqlite \
    && chown -R mercator:www sql

# Expose HTTP port
EXPOSE 8080

# Tout tourne en mercator — aucun besoin de root au runtime
USER mercator

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]