#!/usr/bin/bash

sudo mysqldump -n -t -c \
    --ignore-table=mercator.audit_logs \
    --ignore-table=mercator.migrations \
    --ignore-table=mercator.users \
    --ignore-table=mercator.permissions \
    --ignore-table=mercator.roles \
    --ignore-table=mercator.role_user \
    --ignore-table=mercator.permission_role \
    --ignore-table=mercator.password_resets \
    --ignore-table=mercator.oauth_access_tokens \
    --ignore-table=mercator.oauth_auth_codes \
    --ignore-table=mercator.oauth_refresh_tokens \
    --ignore-table=mercator.oauth_personal_access_clients \
    --ignore-table=mercator.oauth_clients \
    --ignore-table=mercator.cpe_vendors \
    --ignore-table=mercator.cpe_versions \
    --ignore-table=mercator.cpe_products \
    --ignore-table=mercator.graphs \
mercator > mercator_data.sql
