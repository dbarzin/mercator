#!/bin/bash
set -e

# ---------------------------------------------------------------------------
# Mercator - Docker Entrypoint
# Handles initialization before handing off to supervisord
# ---------------------------------------------------------------------------

VERSION=$(cat /var/www/mercator/version.txt 2>/dev/null || echo "unknown")
echo "🚀 Starting Mercator version: ${VERSION}"
export APP_VERSION="${VERSION}"

# ---------------------------------------------------------------------------
# OpenShift: dynamic UID support (nss_wrapper workaround)
# ---------------------------------------------------------------------------
if ! whoami &>/dev/null; then
  if [ -w /etc/passwd ]; then
    echo "${USER_NAME:-default}:x:$(id -u):0:${USER_NAME:-default} user:${HOME}:/sbin/nologin" >> /etc/passwd
  fi
fi

# ---------------------------------------------------------------------------
# APP_KEY: generate if missing and reload into current process env
# key:generate écrit dans .env — il faut ensuite l'exporter manuellement
# car le process bash courant ne relit pas .env automatiquement
# ---------------------------------------------------------------------------
if [ -z "${APP_KEY}" ]; then
  echo "⚠️  APP_KEY not set — generating a new one..."
  php artisan key:generate --force
  # Recharger APP_KEY depuis .env dans l'environnement courant
  APP_KEY=$(grep "^APP_KEY=" /var/www/mercator/.env | cut -d '=' -f2-)
  export APP_KEY
  echo "✅ APP_KEY loaded: ${APP_KEY:0:20}..."
fi

# ---------------------------------------------------------------------------
# Database: wait only for MySQL/MariaDB (SQLite needs no wait)
# ---------------------------------------------------------------------------
if [ "${DB_CONNECTION}" = "mysql" ] || [ "${DB_CONNECTION}" = "mariadb" ]; then
  echo "🔍 DB_CONNECTION=${DB_CONNECTION} — waiting for database..."
  /usr/local/bin/wait-for-db.sh
fi

# ---------------------------------------------------------------------------
# Passport: clean legacy v12 tables before migrating
# (idempotent — les tables peuvent ne pas exister sur une fresh install)
# ---------------------------------------------------------------------------
echo "🔧 Cleaning legacy Passport v12 tables (if any)..."
php artisan tinker --execute="
  Schema::dropIfExists('oauth_personal_access_clients');
  Schema::dropIfExists('oauth_access_tokens');
  Schema::dropIfExists('oauth_refresh_tokens');
  Schema::dropIfExists('oauth_auth_codes');
  Schema::dropIfExists('oauth_clients');
  Schema::dropIfExists('oauth_device_codes');
  DB::table('migrations')->where('migration', 'like', '%oauth%')->delete();
  DB::table('migrations')->where('migration', 'like', '%passport%')->delete();
" 2>/dev/null || true

# ---------------------------------------------------------------------------
# Migrations AVANT passport:install
# passport:install a besoin que les tables oauth_ soient créées par migrate
# ---------------------------------------------------------------------------
echo "🗄️  Running database migrations..."
if [ "${USE_DEMO_DATA}" = "1" ] || [ "${SEED_DATABASE}" = "1" ]; then
  echo "   → Seeding demo/initial data"
  php artisan migrate --seed --force
else
  php artisan migrate --force
fi

# ---------------------------------------------------------------------------
# Passport v13: generate encryption keys + personal access client
# Doit être lancé APRES migrate (les tables oauth_ doivent exister)
# ---------------------------------------------------------------------------
echo "🔑 Installing Passport..."
php artisan passport:install --force
php artisan passport:client --personal --provider=users --no-interaction || true

# ---------------------------------------------------------------------------
# Production optimizations
# ---------------------------------------------------------------------------
if [ "${APP_ENV}" = "production" ]; then
  echo "⚡ Caching config, routes and views for production..."
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

# ---------------------------------------------------------------------------
# Storage link (safe to run multiple times)
# ---------------------------------------------------------------------------
php artisan storage:link --force 2>/dev/null || true

echo "✅ Mercator initialization complete — starting services"

exec "$@"
