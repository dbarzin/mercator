#!/bin/bash
set -e

# Lire la version depuis version.txt
VERSION=$(cat version.txt | tr -d ' \n')
echo "[✔] Using version: $VERSION"

### --- 1. Mettre à jour .env ---
if grep -q "^APP_VERSION=" .env; then
  sed -i "s/^APP_VERSION=.*/APP_VERSION=$VERSION/" .env
else
  echo "APP_VERSION=$VERSION" >> .env
fi
echo "[✔] .env updated"

### --- 2. Mettre à jour package.json ---
if command -v jq > /dev/null; then
  tmpfile=$(mktemp)
  jq --arg v "$VERSION" '.version = $v' package.json > "$tmpfile" && mv "$tmpfile" package.json
  echo "[✔] package.json updated with jq"
else
  sed -i "s/\"version\": *\"[^\"]*\"/\"version\": \"$VERSION\"/" package.json
  echo "[✔] package.json updated with sed"
fi

### --- 3. Build frontend ---
export APP_VERSION=$VERSION
npm install
npm run build
echo "[✔] Frontend built with APP_VERSION=$VERSION"

### --- 4. Construire l'image Docker ---
DOCKER_IMAGE="mercator"
DOCKER_TAG="$VERSION"
DOCKER_LATEST_TAG="latest"

docker build \
  --build-arg APP_VERSION=$VERSION \
  -t $DOCKER_IMAGE:$DOCKER_TAG \
  -t $DOCKER_IMAGE:$DOCKER_LATEST_TAG .

echo "[✔] Docker image built: $DOCKER_IMAGE:$DOCKER_TAG and :latest"

