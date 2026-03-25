#!/bin/bash
set -e

# ---------------------------------------------------------------------------
# Mercator - Docker Entrypoint
#
# Tourne entièrement en tant que mercator (USER mercator dans le Dockerfile).
# Aucune opération root, aucun su-exec, aucun chown.
# Les permissions sont fixées au build (Dockerfile).
#
# Phase 1 : création des répertoires runtime puis init Laravel
# Phase 2 (--init-only) : migrations, passport, cache
# ---------------------------------------------------------------------------

APP_DIR=/var/www/mercator

VERSION=$(cat "${APP_DIR}/version.txt" 2>/dev/null || echo "unknown")
echo "🚀 Starting Mercator version: ${VERSION}"
export APP_VERSION="${VERSION}"

# ---------------------------------------------------------------------------
# PHASE 2 — initialisation Laravel (appelé par la phase 1)
# ---------------------------------------------------------------------------
if [ "$1" = "--init-only" ]; then
  shift

  export LOG_CHANNEL=${LOG_CHANNEL:-single}

  # APP_KEY: s'assurer que la ligne APP_KEY= existe dans .env avant key:generate
  APP_KEY_VALUE="${APP_KEY:-$(sed -n 's/^APP_KEY=//p' "${APP_DIR}/.env" 2>/dev/null | head -n1)}"
  if [ -z "${APP_KEY_VALUE}" ]; then
    echo "⚠️  APP_KEY not set — generating a new one..."
    if ! grep -q '^APP_KEY=' "${APP_DIR}/.env" 2>/dev/null; then
      echo "APP_KEY=" >> "${APP_DIR}/.env"
    fi
    php artisan key:generate --force
    APP_KEY_VALUE=$(sed -n 's/^APP_KEY=//p' "${APP_DIR}/.env" | head -n1)
  fi
  export APP_KEY="${APP_KEY_VALUE}"
  echo "✅ APP_KEY loaded: ${APP_KEY:0:20}..."

  # Database: wait only for MySQL/MariaDB
  if [ "${DB_CONNECTION}" = "mysql" ] || [ "${DB_CONNECTION}" = "mariadb" ]; then
    echo "🔍 DB_CONNECTION=${DB_CONNECTION} — waiting for database..."
    /usr/local/bin/wait-for-db.sh
  fi

  # Migrations
  echo "🗄️  Running database migrations..."
  if [ "${USE_DEMO_DATA}" = "1" ] || [ "${SEED_DATABASE}" = "1" ]; then
    echo "   → Seeding demo/initial data"
    php artisan migrate --seed --force
  else
    php artisan migrate --force
  fi

  # Passport v13 — --force évite une erreur si les clés existent déjà
  echo "🔑 Generating Passport encryption keys..."
  php artisan passport:keys --force || true

  # Créer le client personnel uniquement s'il n'en existe pas
  CLIENT_COUNT=$(php artisan tinker --execute="echo \DB::table('oauth_clients')->count();" 2>/dev/null \
    | grep -E '^[0-9]+$' | tail -1)
  if [ "$CLIENT_COUNT" = "0" ] || [ -z "$CLIENT_COUNT" ]; then
    echo "🔑 Creating personal access client..."
    php artisan passport:client --personal --provider=users --no-interaction
  else
    echo "🔑 Passport client already exists (${CLIENT_COUNT} clients), skipping"
  fi

  echo "🔑 Passport installation complete"

  # Production optimizations
  if [ "${APP_ENV}" = "production" ]; then
    echo "⚡ Caching config, routes and views for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
  fi

  # Storage link
  echo "🔗 Add storage link"
  php artisan storage:link --force 2>/dev/null || true

  echo "✅ Laravel initialization complete"
  exit 0
fi

# ---------------------------------------------------------------------------
# PHASE 1 — répertoires runtime puis init Laravel
# ---------------------------------------------------------------------------

echo "📁 Ensuring storage directories exist..."
mkdir -p \
  "${APP_DIR}/storage/app/purifier" \
  "${APP_DIR}/storage/app/public" \
  "${APP_DIR}/storage/framework/cache/data" \
  "${APP_DIR}/storage/framework/sessions" \
  "${APP_DIR}/storage/framework/views" \
  "${APP_DIR}/storage/logs"

# Créer laravel.log pour que le programme tail de supervisord démarre sans erreur
touch "${APP_DIR}/storage/logs/laravel.log"

echo "🔄 Running Laravel init..."
"$0" "--init-only" "$@"

echo "✅ Mercator ${VERSION} started — env=${APP_ENV:-unknown} db=${DB_CONNECTION:-unknown}" \
  >> "${APP_DIR}/storage/logs/laravel.log"

echo "✅ Mercator initialization complete — starting services"
exec "$@"