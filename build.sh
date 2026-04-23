#!/bin/bash
set -e

# ---------------------------------------------------------------------------
# CONFIG
# ---------------------------------------------------------------------------
DOCKER_REGISTRY="ghcr.io/dbarzin"
DOCKER_IMAGE="mercator"
CHART_DIR="docs/_helm_chart/chart"
CHART_INDEX_DIR="docs/_helm_chart/index"
CHART_REPO_URL="https://github.com/dbarzin/mercator/releases/download"

# ---------------------------------------------------------------------------
# 0. Version
# ---------------------------------------------------------------------------
date +%Y.%m.%d > version.txt
VERSION=$(tr -d ' \n' < version.txt)
echo "[✔] Using version: $VERSION"

# Clean logs
truncate --size 0 ./storage/logs/laravel.log

# ---------------------------------------------------------------------------
# 1. Mettre à jour .env
# ---------------------------------------------------------------------------
if grep -q "^APP_VERSION=" .env; then
  sed -i "s/^APP_VERSION=.*/APP_VERSION=$VERSION/" .env
else
  echo "APP_VERSION=$VERSION" >> .env
fi
echo "[✔] .env updated"

# ---------------------------------------------------------------------------
# 2. Mettre à jour package.json
# ---------------------------------------------------------------------------
if command -v jq > /dev/null; then
  tmpfile=$(mktemp)
  jq --arg v "$VERSION" '.version = $v' package.json > "$tmpfile" && mv "$tmpfile" package.json
  echo "[✔] package.json updated (jq)"
else
  sed -i "s/\"version\": *\"[^\"]*\"/\"version\": \"$VERSION\"/" package.json
  echo "[✔] package.json updated (sed)"
fi

# ---------------------------------------------------------------------------
# 2b. Composer update (|| true pour éviter l'échec si la clé est absente)
# ---------------------------------------------------------------------------
composer update
echo "[✔] composer updated"

# ---------------------------------------------------------------------------
# 3. Build frontend
# ---------------------------------------------------------------------------
export APP_VERSION=$VERSION
npm run build

if [ -n "$(git status --porcelain public/build/assets/)" ]; then
    git add public/build/assets
    git commit -m "build: npm assets $VERSION"
    git push
else
    echo "[✔] No frontend changes to commit"
fi
echo "[✔] Frontend built with APP_VERSION=$VERSION"

# ---------------------------------------------------------------------------
# 4. Mettre à jour le Helm chart
# ---------------------------------------------------------------------------

# Lire la version actuelle du chart et l'incrémenter (patch)
CHART_YAML="${CHART_DIR}/Chart.yaml"

if [ ! -f "$CHART_YAML" ]; then
  echo "[✘] $CHART_YAML introuvable — vérifier le chemin" >&2
  exit 1
fi

CURRENT_CHART_VERSION=$(grep '^version:' "$CHART_YAML" | awk '{print $2}')
echo "[✔] Current chart version: $CURRENT_CHART_VERSION"

# Incrément du patch (ex: 2.0.5 → 2.0.6)
NEW_CHART_VERSION="$VERSION"
echo "[✔] New chart version: $NEW_CHART_VERSION"

# Mettre à jour Chart.yaml
sed -i "s/^version:.*/version: $NEW_CHART_VERSION/" "$CHART_YAML"
sed -i "s/^appVersion:.*/appVersion: $VERSION/" "$CHART_YAML"
echo "[✔] Chart.yaml updated (version: $NEW_CHART_VERSION, appVersion: $VERSION)"

# Packager le chart
helm dependency update "$CHART_DIR"
helm package "$CHART_DIR" --destination "$CHART_INDEX_DIR"
CHART_TGZ="${CHART_INDEX_DIR}/${DOCKER_IMAGE}-${NEW_CHART_VERSION}.tgz"
echo "[✔] Helm chart packaged: $CHART_TGZ"
# Régénérer l'index en fusionnant avec l'existant
helm repo index "$CHART_INDEX_DIR" \
  --url "${CHART_REPO_URL}/mercator-${NEW_CHART_VERSION}" \
  --merge "${CHART_INDEX_DIR}/index.yaml"
echo "[✔] Helm index updated"

# Committer l'index mis à jour (sans le .tgz — il ira dans la release GitHub)
git add "${CHART_INDEX_DIR}/index.yaml" "$CHART_YAML"
git commit -m "helm: chart $NEW_CHART_VERSION (app $VERSION)"
git push

# ---------------------------------------------------------------------------
# 5. Git tag & GitHub release
# ---------------------------------------------------------------------------
RELEASE_TAG="mercator-${NEW_CHART_VERSION}"

git tag -a "$RELEASE_TAG" -m "Release $RELEASE_TAG — app $VERSION"
git push origin "$RELEASE_TAG"

# Upload du .tgz comme asset de la release
gh release create "$RELEASE_TAG" \
  --title "Mercator $NEW_CHART_VERSION" \
  --notes "App version: $VERSION" \
  "$CHART_TGZ"
echo "[✔] GitHub release created: $RELEASE_TAG"

# ---------------------------------------------------------------------------
# 6. Construire et pousser l'image Docker
# ---------------------------------------------------------------------------
FULL_IMAGE="${DOCKER_REGISTRY}/${DOCKER_IMAGE}"

docker build \
  --build-arg APP_VERSION=$VERSION \
  -t "${FULL_IMAGE}:${VERSION}" \
  -t "${FULL_IMAGE}:latest" .

echo "[✔] Docker build: ${FULL_IMAGE}:${VERSION} and :latest"
echo ""
echo "========================================="
echo " Build terminé — version: $VERSION"
echo " Chart: $NEW_CHART_VERSION"
echo " Image: ${FULL_IMAGE}:${VERSION}"
echo "========================================="
