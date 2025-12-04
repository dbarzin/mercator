#!/usr/bin/env bash
set -e

echo "⏳ Wait fo DB..."

until php -r "new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1
do
    echo "DB not ready... wait for 3s"
    sleep 3
done

echo "✅ DB ready, application will start..."
exec "$@"
