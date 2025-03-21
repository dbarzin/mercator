#!/bin/bash

VERSION=$(cat version.txt)
echo "Starting Mercator version: $VERSION"

export APP_VERSION=$VERSION

if ! whoami &> /dev/null; then
  if [ -w /etc/passwd ]; then
    echo "${USER_NAME:-default}:x:$(id -u):0:${USER_NAME:-default} user:${HOME}:/sbin/nologin" >> /etc/passwd
  fi
fi

exec "$@"
