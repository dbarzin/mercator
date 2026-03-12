#!/bin/bash
set -e

# ---------------------------------------------------------------------------
# Mercator - Docker Entrypoint
# Phase 1 (root)    : répertoires runtime, permissions sur volumes montés
#                     puis appel de l'init Laravel en tant que mercator,
#                     puis exec supervisord en ROOT (requis pour spawner
#                     les programmes avec leurs utilisateurs respectifs)
# Phase 2 (mercator): initialisation Laravel uniquement (--init-only)
# ---------------------------------------------------------------------------

APP_DIR=/var/www/mercator
APP_USER=mercator

VERSION=$(cat "${APP_DIR}/version.txt" 2>/dev/null || echo "unknown")
echo "🚀 Starting Mercator version: ${VERSION}"
export APP_VERSION="${VERSION}"

# ---------------------------------------------------------------------------
# PHASE 2 — mercator : initialisation Laravel (appelé par la phase 1)
# ---------------------------------------------------------------------------
if [ "$1" = "--init-only" ]; then
  shift

  # Logs Laravel : tail -F laravel.log vers stdout → visibles dans docker logs
  export LOG_CHANNEL=${LOG_CHANNEL:-single}

  # APP_KEY: generate if missing
  APP_KEY_VALUE="${APP_KEY:-$(sed -n 's/^APP_KEY=//p' "${APP_DIR}/.env" 2>/dev/null | head -n1)}"
  if [ -z "${APP_KEY_VALUE}" ]; then
    echo "⚠️  APP_KEY not set — generating a new one..."
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
  # On filtre la sortie de tinker pour ne garder que la dernière ligne numérique
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
# PHASE 1 — root : création des répertoires, permissions, puis init + démarrage
# ---------------------------------------------------------------------------

# OpenShift: dynamic UID support (nss_wrapper workaround)
if ! whoami &>/dev/null; then
  if [ -w /etc/passwd ]; then
    echo "${USER_NAME:-default}:x:$(id -u):0:${USER_NAME:-default} user:${HOME}:/sbin/nologin" >> /etc/passwd
  fi
fi

echo "📁 Ensuring storage directories exist..."
mkdir -p \
  "${APP_DIR}/storage/app/purifier" \
  "${APP_DIR}/storage/app/public" \
  "${APP_DIR}/storage/framework/cache/data" \
  "${APP_DIR}/storage/framework/sessions" \
  "${APP_DIR}/storage/framework/views" \
  "${APP_DIR}/storage/logs"

chown -R "${APP_USER}:www" "${APP_DIR}/storage"
chmod -R g=u "${APP_DIR}/storage"
# Créer laravel.log pour que le programme tail de supervisord démarre sans erreur
touch "${APP_DIR}/storage/logs/laravel.log"
chown "${APP_USER}:www" "${APP_DIR}/storage/logs/laravel.log"

# Lancer l'init Laravel en tant que mercator (bloquant, exit 0 attendu)
# Note: on ne spécifie PAS le groupe dans su-exec pour éviter l'appel setgroups()
# qui nécessite CAP_SETGID (absent sur les serveurs Linux avec profil seccomp strict).
# Le groupe primaire de mercator (www) est utilisé automatiquement.
echo "🔄 Running Laravel init as ${APP_USER}..."
su-exec "${APP_USER}" "$0" "--init-only" "$@"

# Log de démarrage dans laravel.log (sans tinker pour éviter une dépendance à la DB ici)
echo "✅ Mercator ${VERSION} started — env=${APP_ENV:-unknown} db=${DB_CONNECTION:-unknown}" \
  >> "${APP_DIR}/storage/logs/laravel.log"

# supervisord doit tourner en root pour spawner les programmes
# avec leurs utilisateurs respectifs (user= dans supervisord.conf)
echo "✅ Mercator initialization complete — starting services"
exec "$@"