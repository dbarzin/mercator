# **Mercator Configuration Guide**

This document describes all configuration options available in Mercator, including LDAP integration, Active Directory
nested group support, mail settings, caching, and optional features.

---

## 1. Environment Variables

Mercator relies on environment variables (`.env`) to configure core features such as authentication, LDAP,
auto-provisioning, mail, and security.

Below is the list of supported configuration variables.

---

## 2. LDAP Configuration

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

---

### 2.1 LDAP Connection Settings

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

### 2.2 LDAP User Search Base

Define where users should be searched:

```
LDAP_USERS_BASE_DN="OU=Users,DC=example,DC=com"
```

If empty, Mercator searches the entire directory.

---

### 2.3 LDAP Login Attributes

Defines which LDAP attributes can be used as a login identifier:

```
LDAP_LOGIN_ATTRIBUTES=sAMAccountName,uid,mail
```

Mercator will try these attributes with an OR filter.

---

### 2.4 LDAP Group Restriction

You can restrict access to Mercator to members of a specific LDAP group.

```
LDAP_GROUP="CN=Mercator-Users,OU=Groups,DC=example,DC=com"
```

If empty, all LDAP-authenticated users may log in.

---

## 3. Nested Group Support (Active Directory Only)

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

## 4. Email Configuration

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

## 5. Application-Level Settings

### **Application Name**

This name is displayed in the top left corner of each page of the application.

```
APP_NAME="Mercator"
```

### **Application URL**

```
APP_URL=https://mercator.example.com
```

Used in links, notifications, export URLs, etc.

### **Debug Mode**

```
APP_DEBUG=false
```

When enabled, errors will be shown on screen.
**Do not enable in production.**

### **Session Lifetime**

```
SESSION_LIFETIME=120
```

---

## 6. Logging

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

## 7. File Export / Import

Mercator supports Excel and PDF export via:

* `maatwebsite/excel`
* `phpoffice/phpword`

Ensure `storage/` and `bootstrap/cache/` are writable.

---

## 8. Docker Configuration

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

## 9. Tips for Production Deployment

### Recommended:

* Disable `APP_DEBUG`
* Enable HTTPS
* Use a reverse proxy (Traefik, Nginx)
* Configure automatic backups of the database
* Protect `.env` and `storage/` with proper permissions
* Use LDAP nested groups only if you’re on Active Directory

---

## 10. Summary Table

| Feature          | Variable                | Default          | Notes                                  |
|------------------|-------------------------|------------------|----------------------------------------|
| Enable LDAP      | `LDAP_ENABLED`          | false            | Activates LDAP login                   |
| Local fallback   | `LDAP_FALLBACK_LOCAL`   | false            | Allows local login when LDAP fails     |
| Auto-provision   | `LDAP_AUTO_PROVISION`   | false            | Creates user in DB on first LDAP login |
| Nested groups    | `LDAP_NESTED_GROUPS`    | false            | AD only                                |
| Required group   | `LDAP_GROUP`            | “”               | Restricts login                        |
| LDAP user base   | `LDAP_USERS_BASE_DN`    | “”               | Search base                            |
| Login attributes | `LDAP_LOGIN_ATTRIBUTES` | `sAMAccountName` | CSV list                               |
| LDAP logging     | `LDAP_LOGGING`          | false            | Writes to `ldap.log`                   |

