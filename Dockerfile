# ubuntu / focal (20.04-LTS)
FROM ubuntu:focal

ENV DEBIAN_FRONTEND noninteractive

RUN set -x ; \
  apt update ; apt upgrade ; \
  apt install -y --no-install-recommends \
    php php-zip \
    php-curl \
    php-mbstring \
    php-dom php-ldap \
    php-soap \
    php-xdebug \
    php-mysql \
    php-gd \
    php-xdebug \
    php-mysql \
    php-gd \
    curl less ca-certificates netcat-traditional ; \
    apt-get autoremove --yes ; rm -fr /var/cache/apt

# install composer
RUN set -x ; \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/mercator
COPY . /var/www/mercator

# USER www:www
# RUN set -x ; \
#  groupadd www ; \
#  useradd -g www -ms /bin/bash www ; \
#  chown -R www:www /var/www ; \


# install mercator dependancies
RUN set -x ; \
  cd /var/www/mercator ; composer install

EXPOSE 8000
ENTRYPOINT /var/www/mercator/docker/entrypoint.sh
