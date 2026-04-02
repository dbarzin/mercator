# Mercator Configuration Guide

🇫🇷 [Lire en français](/mercator/fr/config)

This document describes all configuration options available in Mercator, including LDAP integration, Active Directory nested group support, mail settings, caching, and optional features.

---

## Environment Variables

Mercator relies on environment variables (`.env`) to configure core features such as authentication, LDAP, auto-provisioning, mail, and security.

Below is the list of supported configuration variables.

---

## 1. LDAP Configuration

Mercator supports both **local authentication** and **LDAP authentication**.
Local accounts always remain available for fallback if configured.

### **Enable / Disable LDAP Authentication**

```
LDAP_ENABLED=true
```

### **Allow local login when LDAP authentication fails**

```
LDAP_FALLBACK_LOCAL=true
```

### **Automatically create Mercator users from LDAP**

If the LDAP user exists but no matching Mercator user is found, Mercator can auto-create the corresponding local
account.

```
LDAP_AUTO_PROVISION=true
```

The local account will be created with the following role:

```
LDAP_AUTO_PROVISION_ROLE=user
```

---

### 1.1 LDAP Connection Settings

```
LDAP_HOST=ldap.example.com
LDAP_USERNAME="CN=ldap-reader,OU=Service Accounts,DC=example,DC=com"
LDAP_PASSWORD="secret"
LDAP_PORT=389
LDAP_SSL=false
LDAP_TLS=false
```

These values are passed directly to Laravel’s LDAPRecord connection layer.

---

### 1.2 LDAP User Search Base

Define where users should be searched:

```
LDAP_USERS_BASE_DN="OU=Users,DC=example,DC=com"
```

If empty, Mercator searches the entire directory.

---

### 1.3 LDAP Login Attributes

Defines which LDAP attributes can be used as a login identifier:

```
LDAP_LOGIN_ATTRIBUTES=sAMAccountName,uid,mail
```

Mercator will try these attributes with an OR filter.

---

### 1.4 LDAP Group Restriction

You can restrict access to Mercator to members of a specific LDAP group.

```
LDAP_GROUP="CN=Mercator-Users,OU=Groups,DC=example,DC=com"
```

If empty, all LDAP-authenticated users may log in.

---

## 2. Nested Group Support (Active Directory Only)

Mercator can check recursive (nested) group membership when using **Microsoft Active Directory**.

This is disabled by default.

### Enable nested group lookups:

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

## 3. Email Configuration

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

## 4. Application-Level Settings

### Application Name

This name is displayed in the top left corner of each page of the application.

```
APP_NAME=Mercator
```

### Mercator instance environnement
used to specify the type of the Mercator instance:
- Production, Development, Integration, Pre-production, Prototype, Mockup...


```
APP_ENV=Production
```
- 📢 *Note: APP_ENV=Production is mandatory to allow https working*


### API Rate Limit

Limits API requests to protect server resources.  
Format: API_RATE_LIMIT requests per API_RATE_LIMIT_DECAY minute(s)

Examples:
```
    60,1 = 60 req/min (default - normal usage)
    120,1 = 120 req/min (development/testing)
    1000,60 = 1000 req/hour (public API)
    10000,1440 = 10000 req/day (third-party integrations)
```

Returns HTTP 429 (Too Many Requests) when exceeded

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

When enabled, errors will be shown on screen.
**Do not enable in production.**

### Session Lifetime

```
SESSION_LIFETIME=120
```

---

## 5. Logging

Mercator uses Laravel's logging system.
To activate LDAPRecord logging:

```
LDAP_LOGGING=true
```

Logs will appear in:

```
storage/logs/ldap.log
```

If no file appears, ensure directory permissions are correct.

---

## 6. File Export / Import

Mercator supports Excel and PDF export via:

* `maatwebsite/excel`
* `phpoffice/phpword`

Ensure `storage/` and `bootstrap/cache/` are writable.

---

## 7. Docker Configuration

If using Docker:

### Override environment variables in `docker-compose.yml`

```yaml
environment:
  - LDAP_ENABLED=true
  - LDAP_HOST=ad.example.com
  - LDAP_GROUP=CN=Mercator-Users,OU=Groups,DC=example,DC=com
  - LDAP_NESTED_GROUPS=true
```

### Volumes required:

```yaml
volumes:
  - ./storage:/var/www/mercator/storage
  - ./bootstrap/cache:/var/www/mercator/bootstrap/cache
```

---

## 8. Tips for Production Deployment

### Recommendation:

* Disable `APP_DEBUG`
* Enable HTTPS
* Use a reverse proxy (Traefik, Nginx)
* Configure automatic backups of the database
* Protect `.env` and `storage/` with proper permissions
* Use LDAP nested groups only if you’re on **Active Directory**

---

## 10. Summary Table

| Feature             | Variable                   | Default          | Notes                                  |
|---------------------|----------------------------|------------------|----------------------------------------|
| Enable LDAP         | `LDAP_ENABLED`             | false            | Activates LDAP login                   |
| Local fallback      | `LDAP_FALLBACK_LOCAL`      | false            | Allows local login when LDAP fails     |
| Auto-provision      | `LDAP_AUTO_PROVISION`      | false            | Creates user in DB on first LDAP login |
| Auto-provision role | `LDAP_AUTO_PROVISION_ROLE` | null             | Role assigned to newly created users   |
| LDAP Server          | `LDAP_HOST`      | ldap.example.com                                   | for connecting to the LDAP server |
| LDAP User            | `LDAP_USERNAME`  | "CN=ldap-reader,OU=Service Accounts,DC=example,DC=com" | for connecting to the LDAP server |
| LDAP Password        | `LDAP_PASSWORD`  | "secret"                                            | for connecting to the LDAP server |
| LDAP Server Port     | `LDAP_PORT`      | 389                                                 | for connecting to the LDAP server |
| SSL Encryption       | `LDAP_SSL`       | false                                               | for connecting to the LDAP server |
| TLS Encryption       | `LDAP_TLS`       | false                                               | for connecting to the LDAP server |
| Nested groups       | `LDAP_NESTED_GROUPS`       | false            | AD only                                |
| Required group      | `LDAP_GROUP`               | “”               | Restricts login                        |
| LDAP user base      | `LDAP_USERS_BASE_DN`       | “”               | Search base                            |
| Login attributes    | `LDAP_LOGIN_ATTRIBUTES`    | `sAMAccountName` | CSV list                               |
| LDAP logging        | `LDAP_LOGGING`             | false            | Writes to `ldap.log`                   |
| API call limit                | `API_RATE_LIMIT`        | 60   | Defines the number of API requests allowed within the `API_RATE_LIMIT_DECAY` interval |
| API call interval             | `API_RATE_LIMIT_DECAY`  | 1    | Duration in minutes for the allowed number of API calls |
| Session duration              | `SESSION_LIFETIME`      | 120  | Session duration in minutes |
| Debug mode                    | `APP_DEBUG`             | false | Enables or disables debug mode |
| Application URL               | `APP_URL`               | https://mercator.example.com | Allows changing the application's base URL |
| Instance environment          | `APP_ENV`               | Production | Defines the environment of this Mercator instance |
| Application name              | `APP_NAME`              | Mercator | Defines the application name, useful when differentiating instances |
| Mailer type                   | `MAIL_MAILER`           | smtp | For configuring the connection to the mail server |
| Mail server                   | `MAIL_HOST`             | smtp.example.com | For configuring the connection to the mail server |
| Mail server port              | `MAIL_PORT`             | 587 | For configuring the connection to the mail server |
| Mail address                  | `MAIL_USERNAME`         | mailer@example.com | For configuring the connection to the mail server |
| Mail password                 | `MAIL_PASSWORD`         | secret | For configuring the connection to the mail server |
| Encryption type               | `MAIL_ENCRYPTION`       | tls | For configuring the connection to the mail server |
| Sender address                | `MAIL_FROM_ADDRESS`     | noreply@example.com | For configuring the connection to the mail server |
| Sender name                   | `MAIL_FROM_NAME`        | Mercator | For configuring the connection to the mail server |


