# Configuration

Ce document décrit toutes les options de configuration disponibles dans Mercator, notamment l'intégration LDAP, la prise en charge des groupes imbriqués Active Directory, la configuration de la messagerie, la mise en cache et les fonctionnalités optionnelles.

Mercator s'appuie sur des variables d'environnement (`.env`) pour configurer les fonctionnalités principales telles que l'authentification, LDAP, l'auto-provisionnement, la messagerie et la sécurité.

---

## Configuration LDAP

Mercator prend en charge à la fois l'**authentification locale** et l'**authentification LDAP**.
Les comptes locaux restent toujours disponibles comme solution de repli si cela est configuré.

### Activer / Désactiver l'authentification LDAP

```
LDAP_ENABLED=true
```

### Autoriser la connexion locale en cas d'échec de l'authentification LDAP

```
LDAP_FALLBACK_LOCAL=true
```

### Créer automatiquement les utilisateurs Mercator depuis LDAP

Si l'utilisateur LDAP existe mais qu'aucun utilisateur Mercator correspondant n'est trouvé, Mercator peut créer automatiquement le compte local correspondant.

```
LDAP_AUTO_PROVISION=true
```

Le compte local sera créé avec le rôle suivant :

```
LDAP_AUTO_PROVISION_ROLE=user
```

---

### Paramètres de connexion LDAP

```
LDAP_HOST=ldap.example.com
LDAP_USERNAME="CN=ldap-reader,OU=Service Accounts,DC=example,DC=com"
LDAP_PASSWORD="secret"
LDAP_PORT=389
LDAP_SSL=false
LDAP_TLS=false
```

Ces valeurs sont transmises directement à la couche de connexion LDAPRecord de Laravel.

---

### Base de recherche des utilisateurs LDAP

Définit l'emplacement où les utilisateurs doivent être recherchés :

```
LDAP_USERS_BASE_DN="OU=Users,DC=example,DC=com"
```

Si vide, Mercator recherche dans l'ensemble de l'annuaire.

---

### Attributs de connexion LDAP

Définit les attributs LDAP pouvant être utilisés comme identifiant de connexion :

```
LDAP_LOGIN_ATTRIBUTES=sAMAccountName,uid,mail
```

Mercator essaiera ces attributs avec un filtre OR.

---

### Restriction par groupe LDAP

Il est possible de restreindre l'accès à Mercator aux membres d'un groupe LDAP spécifique.

```
LDAP_GROUP="CN=Mercator-Users,OU=Groups,DC=example,DC=com"
```

Si vide, tous les utilisateurs authentifiés par LDAP peuvent se connecter.

---

## Prise en charge des groupes imbriqués (Active Directory uniquement)

Mercator peut vérifier l'appartenance récursive (groupes imbriqués) lors de l'utilisation de **Microsoft Active Directory**.

Cette fonctionnalité est désactivée par défaut.

### Activer la recherche de groupes imbriqués

```
LDAP_NESTED_GROUPS=true
```

### Fonctionnement

Lorsque cette option est activée, Mercator utilise la règle de correspondance spécifique à AD :

```
1.2.840.113556.1.4.1941   (LDAP_MATCHING_RULE_IN_CHAIN)
```

Exemple de filtre LDAP généré :

```
(memberOf:1.2.840.113556.1.4.1941:=CN=Mercator-Users,OU=Groups,DC=example,DC=com)
```

Cela permet de reconnaître :

* Les membres directs
* Les membres indirects
* Les groupes profondément imbriqués (multi-niveaux)

### Limitations importantes

* **Pris en charge uniquement sur Microsoft Active Directory**
* Ne fonctionnera **pas** sur OpenLDAP ou d'autres serveurs LDAP
* Si activé sur des systèmes non-AD, l'authentification LDAP échouera

---

## Configuration de la messagerie

Mercator envoie des notifications, des réinitialisations de mot de passe (pour les comptes locaux) et des e-mails système.

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

## Paramètres applicatifs

### Nom de l'application

Ce nom est affiché dans le coin supérieur gauche de chaque page de l'application.

```
APP_NAME=Mercator
```

### Environnement de l'instance Mercator

Permet de préciser le type de l'instance Mercator : Production, Développement, Intégration, Pré-production, Prototype, Maquette…

```
APP_ENV=Production
```

📢 *Remarque : `APP_ENV=Production` est obligatoire pour que le HTTPS fonctionne.*

### Limite du taux d'appels API

Limite les requêtes API pour protéger les ressources du serveur.
Format : `API_RATE_LIMIT` requêtes par `API_RATE_LIMIT_DECAY` minute(s).

```
    60,1       = 60 req/min    (défaut - usage normal)
    120,1      = 120 req/min   (développement/tests)
    1000,60    = 1000 req/h    (API publique)
    10000,1440 = 10000 req/j   (intégrations tierces)
```

Retourne HTTP 429 (Too Many Requests) en cas de dépassement.

```
API_RATE_LIMIT=60
API_RATE_LIMIT_DECAY=1
```

### URL de l'application

```
APP_URL=https://mercator.example.com
```

Utilisée dans les liens, les notifications, les URL d'export, etc.

### Mode débogage

```
APP_DEBUG=false
```

Lorsqu'il est activé, les erreurs sont affichées à l'écran. **Ne pas activer en production.**

### Durée de session

```
SESSION_LIFETIME=120
```

---

## Journalisation

Mercator utilise le système de journalisation de Laravel. Pour activer la journalisation LDAPRecord :

```
LDAP_LOGGING=true
```

Les journaux apparaîtront dans :

```
storage/logs/ldap.log
```

Si aucun fichier n'apparaît, vérifiez les permissions du répertoire.

---

## Export / Import de fichiers

Mercator prend en charge l'export Excel et PDF via :

* `maatwebsite/excel`
* `phpoffice/phpword`

Assurez-vous que `storage/` et `bootstrap/cache/` sont accessibles en écriture.

---

## Configuration Docker

### Surcharger les variables d'environnement dans `docker-compose.yml`

```yaml
environment:
  - LDAP_ENABLED=true
  - LDAP_HOST=ad.example.com
  - LDAP_GROUP=CN=Mercator-Users,OU=Groups,DC=example,DC=com
  - LDAP_NESTED_GROUPS=true
```

### Volumes requis

```yaml
volumes:
  - ./storage:/var/www/mercator/storage
  - ./bootstrap/cache:/var/www/mercator/bootstrap/cache
```

---

## Conseils pour le déploiement en production

* Désactiver `APP_DEBUG`
* Activer HTTPS
* Utiliser un reverse proxy (Traefik, Nginx)
* Configurer des sauvegardes automatiques de la base de données
* Protéger `.env` et `storage/` avec les permissions appropriées
* N'utiliser les groupes imbriqués LDAP que sur **Active Directory**

---

## Tableau récapitulatif

| Fonctionnalité | Variable | Défaut | Notes |
|----------------|----------|--------|-------|
| Activer LDAP | `LDAP_ENABLED` | false | Active la connexion LDAP |
| Repli local | `LDAP_FALLBACK_LOCAL` | false | Autorise la connexion locale en cas d'échec LDAP |
| Auto-provisionnement | `LDAP_AUTO_PROVISION` | false | Crée l'utilisateur en base lors de la première connexion LDAP |
| Rôle auto-provisionnement | `LDAP_AUTO_PROVISION_ROLE` | null | Rôle attribué aux utilisateurs nouvellement créés |
| Serveur LDAP | `LDAP_HOST` | ldap.example.com | Pour la connexion au serveur LDAP |
| Utilisateur LDAP | `LDAP_USERNAME` | CN=ldap-reader,… | Pour la connexion au serveur LDAP |
| Mot de passe LDAP | `LDAP_PASSWORD` | secret | Pour la connexion au serveur LDAP |
| Port du serveur LDAP | `LDAP_PORT` | 389 | Pour la connexion au serveur LDAP |
| Chiffrement SSL | `LDAP_SSL` | false | Pour la connexion au serveur LDAP |
| Chiffrement TLS | `LDAP_TLS` | false | Pour la connexion au serveur LDAP |
| Groupes imbriqués | `LDAP_NESTED_GROUPS` | false | AD uniquement |
| Groupe requis | `LDAP_GROUP` | "" | Restreint la connexion |
| Base de recherche LDAP | `LDAP_USERS_BASE_DN` | "" | Base de recherche |
| Attributs de connexion | `LDAP_LOGIN_ATTRIBUTES` | `sAMAccountName` | Liste séparée par des virgules |
| Journalisation LDAP | `LDAP_LOGGING` | false | Écrit dans `ldap.log` |
| Limite d'appels API | `API_RATE_LIMIT` | 60 | Nombre de requêtes API autorisées dans la fenêtre de temps |
| Intervalle de limite API | `API_RATE_LIMIT_DECAY` | 1 | Durée en minutes de la fenêtre de limite de taux |
| Durée de session | `SESSION_LIFETIME` | 120 | Durée de session en minutes |
| Mode débogage | `APP_DEBUG` | false | Active ou désactive le mode débogage |
| URL de l'application | `APP_URL` | https://mercator.example.com | URL de base utilisée dans les liens et les exports |
| Environnement de l'instance | `APP_ENV` | Production | Définit l'environnement de cette instance Mercator |
| Nom de l'application | `APP_NAME` | Mercator | Nom affiché, utile pour différencier les instances |
| Type de messagerie | `MAIL_MAILER` | smtp | Connexion au serveur de messagerie |
| Serveur de messagerie | `MAIL_HOST` | smtp.example.com | Connexion au serveur de messagerie |
| Port du serveur de messagerie | `MAIL_PORT` | 587 | Connexion au serveur de messagerie |
| Adresse de messagerie | `MAIL_USERNAME` | mailer@example.com | Connexion au serveur de messagerie |
| Mot de passe de messagerie | `MAIL_PASSWORD` | secret | Connexion au serveur de messagerie |
| Type de chiffrement | `MAIL_ENCRYPTION` | tls | Connexion au serveur de messagerie |
| Adresse d'expéditeur | `MAIL_FROM_ADDRESS` | noreply@example.com | Connexion au serveur de messagerie |
| Nom de l'expéditeur | `MAIL_FROM_NAME` | Mercator | Connexion au serveur de messagerie |