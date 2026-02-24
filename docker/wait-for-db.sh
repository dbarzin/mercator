#!/usr/bin/env bash
set -e

# ---------------------------------------------------------------------------
# Mercator - Wait for Database
# Polls until the database accepts connections, then exec the given command.
# Supports MySQL, MariaDB, PostgreSQL. SQLite: not needed (no network wait).
# ---------------------------------------------------------------------------

echo "⏳ Waiting for database (${DB_CONNECTION}://${DB_HOST}:${DB_PORT:-3306}/${DB_DATABASE})..."

# Normalize driver: MariaDB uses the 'mysql' PDO driver
PDO_DRIVER="${DB_CONNECTION}"
if [ "${PDO_DRIVER}" = "mariadb" ]; then
  PDO_DRIVER="mysql"
fi

MAX_RETRIES=30
RETRY_INTERVAL=3
attempt=0

until php -r "
  try {
    \$dsn = '${PDO_DRIVER}:host=' . getenv('DB_HOST')
          . ';port=' . (getenv('DB_PORT') ?: '3306')
          . ';dbname=' . getenv('DB_DATABASE');
    new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
  } catch (Exception \$e) {
    exit(1);
  }
" 2>/dev/null
do
  attempt=$((attempt + 1))
  if [ "${attempt}" -ge "${MAX_RETRIES}" ]; then
    echo "❌ Database not reachable after $((MAX_RETRIES * RETRY_INTERVAL))s — aborting."
    exit 1
  fi
  echo "   DB not ready (attempt ${attempt}/${MAX_RETRIES})... retrying in ${RETRY_INTERVAL}s"
  sleep "${RETRY_INTERVAL}"
done

echo "✅ Database is ready"

# Pass through to the next command if provided
if [ $# -gt 0 ]; then
  exec "$@"
fi