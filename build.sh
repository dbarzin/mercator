#!/bin/bash
set -e

VERSION=$(cat version.txt | tr -d ' \n')
echo "[✔] Using version: $VERSION"

### --- 1. Update .env ---
if grep -q "^APP_VERSION=" .env; then
  sed -i "s/^APP_VERSION=.*/APP_VERSION=$VERSION/" .env
else
  echo "APP_VERSION=$VERSION" >> .env
fi
echo "[✔] .env updated"

### --- 2. Update package.json ---
if command -v jq > /dev/null; then
  tmpfile=$(mktemp)
  jq --arg v "$VERSION" '.version = $v' package.json > "$tmpfile" && mv "$tmpfile" package.json
  echo "[✔] package.json updated with jq"
else
  sed -i "s/\"version\": *\"[0-9]\+\.[0-9]\+\.[0-9]\+\"/\"version\": \"$VERSION\"/" package.json
  echo "[✔] package.json updated with sed"
fi

### --- 3. Build frontend ---
export APP_VERSION=$VERSION
npm install
npm run build
echo "[✔] Frontend built"

### --- 4. Git commit & tag ---
git add .env package.json
git commit -m "chore: release v$VERSION" || echo "[ℹ️] Nothing to commit"

# Crée le tag s’il n'existe pas déjà
if git rev-parse "v$VERSION" >/dev/null 2>&1; then
  echo "[ℹ️] Tag v$VERSION already exists"
else
  git tag -a "v$VERSION" -m "Version $VERSION"
  git push origin "v$VERSION"
  echo "[✔] Git tag v$VERSION created and pushed"
fi
