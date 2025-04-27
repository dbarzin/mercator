FROM php:8.3-fpm-alpine3.19

# Version de l'application
COPY version.txt ./version.txt
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
    pdo pdo_mysql pdo_pgsql pdo_sqlite \
    zip ldap gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && \
    chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Create application user and group
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S mercator -G www

# Set working directory
WORKDIR /var/www/mercator

# Pré-copie uniquement composer.json et composer.lock
COPY --chown=mercator:www composer.json composer.lock ./

# Installer les dépendances PHP sans exécuter les scripts (artisan discover sera fait plus tard)
RUN composer install --no-interaction --prefer-dist --no-scripts

# Puis copier tout le code de l'application
COPY --chown=mercator:www . .

# Ensuite exécuter les scripts artisan
RUN php artisan package:discover --ansi

# Copier les fichiers de configuration nginx et supervisor
COPY --chown=mercator:www docker/nginx.conf /etc/nginx/http.d/default.conf
COPY --chown=mercator:www docker/supervisord.conf /etc/supervisord.conf
COPY --chown=mercator:www docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Copier environnement si besoin
RUN cp .env.sqlite .env

# Préparer base SQLite
RUN mkdir -p sql && touch sql/db.sqlite

# Create log folder
RUN mkdir -p /var/log && chmod g+w /var/log

# Fix permissions
RUN chmod g=u /var/lib/nginx /var/log/nginx && \
    chmod +x /usr/local/bin/entrypoint.sh && \
    chmod g=u /etc/passwd && \
    chgrp www /etc/passwd && \
    chmod g+w /var/www/mercator

# Switch to application user
USER mercator:www

# Expose HTTP port
EXPOSE 8000

# Entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
