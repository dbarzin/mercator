# Guide de Configuration de Mercator

Ce document décrit l’ensemble des options de configuration disponibles dans Mercator, notamment l’intégration LDAP, la prise en charge des groupes imbriqués pour Active Directory, la configuration du mail, le cache et les fonctionnalités
optionnelles.

---

## Variables d’Environnement

Mercator utilise des variables d’environnement (`.env`) pour configurer les fonctionnalités essentielles :
authentification, LDAP, auto-provisionnement, mail, sécurité, etc.

Vous trouverez ci-dessous la liste complète des paramètres pris en charge.

---

## 1. Configuration LDAP

Mercator supporte l’authentification **locale** et l’authentification **LDAP**.  
Les comptes locaux restent toujours disponibles en fallback si vous les activez.

### **Activer / désactiver l’authentification LDAP**

```
LDAP_ENABLED=true
```

### **Autoriser la connexion locale si l’authentification LDAP échoue**

```
LDAP_FALLBACK_LOCAL=true
```

### **Créer automatiquement un utilisateur Mercator à partir du LDAP**

Si un utilisateur LDAP existe mais n’a pas de compte Mercator associé,
Mercator peut créer automatiquement le compte local correspondant.

```
LDAP_AUTO_PROVISION=true
```

L'utilisateur sera créé avec le rôle suivant :

```
LDAP_AUTO_PROVISION_ROLE=user
```

---

### 1.1 Paramètres de Connexion LDAP

```
LDAP_HOST=ldap.example.com
LDAP_USERNAME="CN=ldap-reader,OU=Service Accounts,DC=example,DC=com"
LDAP_PASSWORD="secret"
LDAP_PORT=389
LDAP_SSL=false
LDAP_TLS=false
```

Ces valeurs sont transmises à la couche LDAPRecord de Laravel.

---

### 1.2 Base de Recherche des Utilisateurs

Définit où les utilisateurs doivent être recherchés :

```
LDAP_USERS_BASE_DN="OU=Users,DC=example,DC=com"
```

Si vide, Mercator recherchera dans tout l’annuaire.

---

### 1.3 Attributs LDAP Utilisés pour le Login

Définit les attributs LDAP pouvant servir d’identifiant :

```
LDAP_LOGIN_ATTRIBUTES=sAMAccountName,uid,mail
```

Mercator testera ces attributs avec un filtre OR.

---

### 1.4 Restriction par Groupe LDAP

Vous pouvez limiter l’accès à Mercator aux membres d’un groupe LDAP spécifique :

```
LDAP_GROUP="CN=Mercator-Users,OU=Groups,DC=example,DC=com"
```

Si vide, tous les utilisateurs authentifiés via LDAP pourront se connecter.

---

## 2. Support des Groupes Imbriqués (Active Directory uniquement)

Mercator peut vérifier l’appartenance **récursive** (groupes imbriqués) lors de l’utilisation de **Microsoft Active
Directory**.

Ce support est désactivé par défaut.

### Activer les groupes imbriqués :

```
LDAP_NESTED_GROUPS=true
```

### Fonctionnement

Lorsque cette option est activée, Mercator utilise la règle de correspondance AD :

```
1.2.840.113556.1.4.1941   (LDAP_MATCHING_RULE_IN_CHAIN)
```

Exemple de filtre LDAP généré :

```
(memberOf:1.2.840.113556.1.4.1941:=CN=Mercator-Users,OU=Groups,DC=example,DC=com)
```

Cela permet de reconnaître :

* L’appartenance directe
* L’appartenance indirecte
* Les groupes imbriqués multi-niveaux

### Limitations importantes

* Fonctionne **uniquement** avec **Microsoft Active Directory**
* Ne fonctionne **pas** avec OpenLDAP ou d’autres annuaires
* Si activé sur un annuaire non AD, l’authentification LDAP échouera

---

## 3. Configuration Email

Mercator utilise le système d’e-mails de Laravel pour envoyer :

* les notifications
* les réinitialisations de mot de passe (comptes locaux)
* les alertes système

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

## 4. Paramètres Applicatifs

### Nom de l’application
Ce nom s'affiche en haut a gauche de chaque page de l'application.
```
APP_NAME=Mercator
```
### Environnement de l'instance mercator
permet de définir le type de l'instance mercator:  
- Production, Développement, Intégration, Préproduction, Maquette...


```
APP_ENV=Production
```
- 📢 *Note: APP_ENV=Production est obligatoire pour que https fonctionne*

### Paramètre liés à l'utilisation de l'API
Limite les requêtes API afin de protéger les ressources du serveur.  
Format : API_RATE_LIMIT requêtes par API_RATE_LIMIT_DECAY minute(s)

Exemples :
```
    60,1 = 60 requêtes/min (par défaut – usage normal)
    120,1 = 120 requêtes/min (développement/tests)
    1000,60 = 1000 requêtes/heure (API publique)
    10000,1440 = 10000 requêtes/jour (intégrations tierces)
```
Retourne HTTP 429 (Trop de requêtes) lorsque la limite est dépassée

```
API_RATE_LIMIT=60
API_RATE_LIMIT_DECAY=1
```

### URL de l’application

```
APP_URL=https://mercator.example.com
```

Utilisé dans les liens, notifications, exports, etc.

### Mode Debug

```
APP_DEBUG=false
```

En mode debug, les erreurs sont affichées.
**Ne jamais activer en production.**

### Durée de Session

```
SESSION_LIFETIME=120
```

En minutes.

---

## 5. Logs

Mercator s’appuie sur le système de logs de Laravel.

### Activer les logs LDAPRecord :

```
LDAP_LOGGING=true
```

Les logs apparaissent dans :

```
storage/logs/ldap.log
```

Si le fichier n’est pas généré, vérifiez les permissions du dossier.

---

## 6. Export / Import de Fichiers

Mercator prend en charge l’export Excel, CSV, Word et PDF via :

* `maatwebsite/excel`
* `phpoffice/phpword`

Veillez à ce que `storage/` et `bootstrap/cache/` soient accessibles en écriture.

---

## 7. Configuration Docker

Si vous utilisez Docker :

### Surcharger les variables dans `docker-compose.yml`

#### LDAP
```yaml
environment:
  - LDAP_ENABLED=true
  - LDAP_HOST=ad.example.com
  - LDAP_GROUP=CN=Mercator-Users,OU=Groups,DC=example,DC=com
  - LDAP_NESTED_GROUPS=true
```

#### Volumes recommandés :
```yaml
volumes:
  - ./storage:/var/www/mercator/storage
  - ./bootstrap/cache:/var/www/mercator/bootstrap/cache
```

---

## 8. Recommandations pour la Production

### Recommandé :

* Désactiver `APP_DEBUG`
* Toujours utiliser HTTPS
* Mettre un reverse proxy (Traefik, Nginx)
* Sauvegarder régulièrement la base de données
* Protéger `.env` et `storage/` par des permissions strictes
* N’activer les groupes imbriqués que si vous utilisez **Active Directory**

---

## 9. Tableau Récapitulatif

| Fonction            | Variable                   | Défaut           | Notes                                           |
|---------------------|----------------------------|------------------|-------------------------------------------------|
| Activer LDAP        | `LDAP_ENABLED`             | false            | Active la connexion LDAP                        |
| Fallback local      | `LDAP_FALLBACK_LOCAL`      | false            | Permet le login local si LDAP échoue            |
| Auto-provision      | `LDAP_AUTO_PROVISION`      | false            | Crée automatiquement l’utilisateur              |
| Auto-provision role | `LDAP_AUTO_PROVISION_ROLE` | null             | Role assigné aux utilisateur nouvellement créés |
| Serveur LDAP| `LDAP_HOST` | ldap.example.com | pour la connexion au LDAP | 
| Utilisateur LDAP | `LDAP_USERNAME` | "CN=ldap-reader,OU=Service Accounts,DC=example,DC=com" | pour la connexion au LDAP | 
| Mot de passe LDAP | `LDAP_PASSWORD` | "secret" | pour la connexion au LDAP | 
| Port Ip du LDAP serveur | `LDAP_PORT` | 389 | pour la connexion au LDAP |
| Encryption SSL | `LDAP_SSL` | false | pour la connexion au LDAP | 
| Encryption TLS | `LDAP_TLS` | false | pour la connexion au LDAP |
| Groupes imbriqués   | `LDAP_NESTED_GROUPS`       | false            | AD uniquement                                   |
| Groupe requis       | `LDAP_GROUP`               | “”               | Restreint l’accès                               |
| Base LDAP           | `LDAP_USERS_BASE_DN`       | “”               | DN de recherche                                 |
| Attributs login     | `LDAP_LOGIN_ATTRIBUTES`    | `sAMAccountName` | Liste CSV                                       |
| Logs LDAP           | `LDAP_LOGGING`             | false            | Fichier `ldap.log`                              |
| Limite du nombre d'appels API | `API_RATE_LIMIT` | 60 | Défini le nombre de requête API possible dans l'intervale `API_RATE_LIMIT_DECAY` |
| Délai permettant le nombre d'appels API | `API_RATE_LIMIT_DECAY` | 1 | Durée en minutes pour le nombre d'appel API |
| Durée de sessions | `SESSION_LIFETIME` | 120 | Durée de session en minutes |
| Mode debug | `APP_DEBUG` | false | Active ou désactive le mode debug |
| Url de l'application | `APP_URL` | https://mercator.example.com | permet de changer url de l'application |
| Environnement d'instance | `APP_ENV` | Production | Défini l'environnement de cette instance mercator |
| Nom de l'application | `APP_NAME` | Mercator | permet de définir le nom de l’application si besoin de différencier |
| Type de courriel | `MAIL_MAILER` | smtp | Pour la configuration de la connexion au serveur de courriel |
| Serveur du courriel| `MAIL_HOST` | smtp.example.com |Pour la configuration de la connexion au serveur de courriel | 
| Port ip de courriel | `MAIL_PORT` | 587 | Pour la configuration de la connexion au serveur de courriel | 
| Adresse courriel | `MAIL_USERNAME` | mailer@example.com | Pour la configuration de la connexion au serveur de courriel |
| Mot de passe de l'adresse de courriel| `MAIL_PASSWORD` | secret | Pour la configuration de la connexion au serveur de courriel |
| Type d'encryption | `MAIL_ENCRYPTION` | tls | Pour la configuration de la connexion au serveur de courriel |
| Utilisateur émetteur | `MAIL_FROM_ADDRESS` | noreply@example.com | Pour la configuration de la connexion au serveur de courriel |
| Appli émettrice | `MAIL_FROM_NAME` | Mercator| Pour la configuration de la connexion au serveur de courriel |