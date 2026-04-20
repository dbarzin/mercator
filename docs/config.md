# Configuration

This document describes all configuration options available in Mercator, including LDAP integration, Active Directory nested group support, mail settings, caching, and optional features.

Mercator relies on environment variables (`.env`) to configure core features such as authentication, LDAP, auto-provisioning, mail, and security.

---

## LDAP Configuration

Mercator supports both **local authentication** and **LDAP authentication**.
Local accounts always remain available for fallback if configured.

### Enable / Disable LDAP Authentication

```
LDAP_ENABLED=true
```

### Allow local login when LDAP authentication fails

```
LDAP_FALLBACK_LOCAL=true
```

### Automatically create Mercator users from LDAP

If the LDAP user exists but no matching Mercator user is found, Mercator can auto-create the corresponding local account.

```
LDAP_AUTO_PROVISION=true
```

The local account will be created with the following role:

```
LDAP_AUTO_PROVISION_ROLE=user
```

---

### LDAP Connection Settings

```
LDAP_HOST=ldap.example.com
LDAP_USERNAME="CN=ldap-reader,OU=Service Accounts,DC=example,DC=com"
LDAP_PASSWORD="secret"
LDAP_PORT=389
LDAP_SSL=false
LDAP_TLS=false
```

These values are passed directly to Laravel's LDAPRecord connection layer.

---

### LDAP User Search Base

Define where users should be searched:

```
LDAP_USERS_BASE_DN="OU=Users,DC=example,DC=com"
```

If empty, Mercator searches the entire directory.

---

### LDAP Login Attributes

Defines which LDAP attributes can be used as a login identifier:

```
LDAP_LOGIN_ATTRIBUTES=sAMAccountName,uid,mail
```

Mercator will try these attributes with an OR filter.

---

### LDAP Group Restriction

You can restrict access to Mercator to members of a specific LDAP group.

```
LDAP_GROUP="CN=Mercator-Users,OU=Groups,DC=example,DC=com"
```

If empty, all LDAP-authenticated users may log in.

---

## Nested Group Support (Active Directory Only)

Mercator can check recursive (nested) group membership when using **Microsoft Active Directory**.

This is disabled by default.

### Enable nested group lookups

```
LDAP_NESTED_GROUPS=true
```

### How it works

When enabled, Mercator uses the AD-specific matching rule:

```
1.2.840.113556.1.4.1941   (LDAP_MATCHING_RULE_IN_CHAIN)
```

Example LDAP filter generated:

```
(memberOf:1.2.840.113556.1.4.1941:=CN=Mercator-Users,OU=Groups,DC=example,DC=com)
```

This allows recognition of:

* Direct membership
* Indirect membership
* Deeply nested groups (multi-level)

### Important limitations

* **Supported only on Microsoft Active Directory**
* Will **not** work on OpenLDAP or other LDAP servers
* If enabled on non-AD systems, LDAP authentication will fail

---

## Email Configuration

Mercator sends notifications, password resets (for local accounts), and system emails.

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=mailer@example.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Mercator"
```

---

## Application-Level Settings

### Application Name

This name is displayed in the top left corner of each page of the application.

```
APP_NAME=Mercator
```

### Mercator Instance Environment

Used to specify the type of the Mercator instance: Production, Development, Integration, Pre-production, Prototype, Mockup…

```
APP_ENV=Production
```

📢 *Note: `APP_ENV=Production` is mandatory to allow HTTPS to work.*

### API Rate Limit

Limits API requests to protect server resources.
Format: `API_RATE_LIMIT` requests per `API_RATE_LIMIT_DECAY` minute(s).

```
    60,1       = 60 req/min    (default - normal usage)
    120,1      = 120 req/min   (development/testing)
    1000,60    = 1000 req/hour (public API)
    10000,1440 = 10000 req/day (third-party integrations)
```

Returns HTTP 429 (Too Many Requests) when exceeded.

```
API_RATE_LIMIT=60
API_RATE_LIMIT_DECAY=1
```

### Application URL

```
APP_URL=https://mercator.example.com
```

Used in links, notifications, export URLs, etc.

### Debug Mode

```
APP_DEBUG=false
```

When enabled, errors will be shown on screen. **Do not enable in production.**

### Session Lifetime

```
SESSION_LIFETIME=120
```

---

## Logging

Mercator uses Laravel's logging system. To activate LDAPRecord logging:

```
LDAP_LOGGING=true
```

Logs will appear in:

```
storage/logs/ldap.log
```

If no file appears, ensure directory permissions are correct.

---

## File Export / Import

Mercator supports Excel and PDF export via:

* `maatwebsite/excel`
* `phpoffice/phpword`

Ensure `storage/` and `bootstrap/cache/` are writable.

---

## Docker Configuration

### Override environment variables in `docker-compose.yml`

```yaml
environment:
  - LDAP_ENABLED=true
  - LDAP_HOST=ad.example.com
  - LDAP_GROUP=CN=Mercator-Users,OU=Groups,DC=example,DC=com
  - LDAP_NESTED_GROUPS=true
```

### Volumes required

```yaml
volumes:
  - ./storage:/var/www/mercator/storage
  - ./bootstrap/cache:/var/www/mercator/bootstrap/cache
```

---

## Tips for Production Deployment

* Disable `APP_DEBUG`
* Enable HTTPS
* Use a reverse proxy (Traefik, Nginx)
* Configure automatic backups of the database
* Protect `.env` and `storage/` with proper permissions
* Use LDAP nested groups only if you're on **Active Directory**

---

## Summary Table

| Feature | Variable | Default | Notes |
|---------|----------|---------|-------|
| Enable LDAP | `LDAP_ENABLED` | false | Activates LDAP login |
| Local fallback | `LDAP_FALLBACK_LOCAL` | false | Allows local login when LDAP fails |
| Auto-provision | `LDAP_AUTO_PROVISION` | false | Creates user in DB on first LDAP login |
| Auto-provision role | `LDAP_AUTO_PROVISION_ROLE` | null | Role assigned to newly created users |
| LDAP Server | `LDAP_HOST` | ldap.example.com | For connecting to the LDAP server |
| LDAP User | `LDAP_USERNAME` | CN=ldap-reader,… | For connecting to the LDAP server |
| LDAP Password | `LDAP_PASSWORD` | secret | For connecting to the LDAP server |
| LDAP Server Port | `LDAP_PORT` | 389 | For connecting to the LDAP server |
| SSL Encryption | `LDAP_SSL` | false | For connecting to the LDAP server |
| TLS Encryption | `LDAP_TLS` | false | For connecting to the LDAP server |
| Nested groups | `LDAP_NESTED_GROUPS` | false | AD only |
| Required group | `LDAP_GROUP` | "" | Restricts login |
| LDAP user base | `LDAP_USERS_BASE_DN` | "" | Search base |
| Login attributes | `LDAP_LOGIN_ATTRIBUTES` | `sAMAccountName` | CSV list |
| LDAP logging | `LDAP_LOGGING` | false | Writes to `ldap.log` |
| API call limit | `API_RATE_LIMIT` | 60 | Number of API requests allowed within the decay interval |
| API call interval | `API_RATE_LIMIT_DECAY` | 1 | Duration in minutes for the rate limit window |
| Session duration | `SESSION_LIFETIME` | 120 | Session duration in minutes |
| Debug mode | `APP_DEBUG` | false | Enables or disables debug mode |
| Application URL | `APP_URL` | https://mercator.example.com | Base URL used in links and exports |
| Instance environment | `APP_ENV` | Production | Defines the environment of this Mercator instance |
| Application name | `APP_NAME` | Mercator | Displayed name, useful when running multiple instances |
| Mailer type | `MAIL_MAILER` | smtp | Mail server connection |
| Mail server | `MAIL_HOST` | smtp.example.com | Mail server connection |
| Mail server port | `MAIL_PORT` | 587 | Mail server connection |
| Mail address | `MAIL_USERNAME` | mailer@example.com | Mail server connection |
| Mail password | `MAIL_PASSWORD` | secret | Mail server connection |
| Encryption type | `MAIL_ENCRYPTION` | tls | Mail server connection |
| Sender address | `MAIL_FROM_ADDRESS` | noreply@example.com | Mail server connection |
| Sender name | `MAIL_FROM_NAME` | Mercator | Mail server connection |