# Démarrage de Mercator avec Docker

## Vue d'ensemble

Mercator est distribué sous forme d'image Docker disponible sur le GitHub Container Registry :

```
ghcr.io/dbarzin/mercator:latest
```

Le conteneur est conçu pour fonctionner **sans privilèges root**. Toutes les opérations système sont réalisées au moment
du build de l'image. Au démarrage, le conteneur s'exécute entièrement sous l'utilisateur `mercator` (UID 1000).

---

## Architecture du conteneur

Un seul conteneur `mercator-app` fait tourner trois processus gérés par **supervisord** :

```
supervisord (mercator)
├── php-fpm        — exécute le code PHP Laravel
├── nginx          — sert l'application sur le port 8080
├── queue          — traite les jobs en arrière-plan
├── scheduler      — exécute les tâches planifiées Laravel
└── laravel-log    — relaie laravel.log vers docker logs
```

---

## Séquence de démarrage

Lors de chaque `docker run` ou redémarrage du conteneur, l'`entrypoint.sh` s'exécute en deux phases avant de lancer
supervisord.

### Phase 1 — Préparation des répertoires

```
entrypoint.sh (phase 1)
│
├── Création des répertoires runtime si absents
│   ├── storage/app/purifier
│   ├── storage/app/public
│   ├── storage/framework/cache/data
│   ├── storage/framework/sessions
│   ├── storage/framework/views
│   └── storage/logs/
│       └── laravel.log  ← créé vide si absent
│
└── Appel de la phase 2 (--init-only)
```

### Phase 2 — Initialisation Laravel

```
entrypoint.sh (phase 2 : --init-only)
│
├── APP_KEY
│   ├── Lecture depuis la variable d'environnement APP_KEY
│   ├── ou depuis le fichier .env
│   └── Génération automatique si absente (key:generate)
│
├── Attente de la base de données (MySQL/MariaDB uniquement)
│   └── Boucle jusqu'à ce que le serveur réponde
│
├── Migrations
│   ├── migrate --seed  si USE_DEMO_DATA=1 ou SEED_DATABASE=1
│   └── migrate         sinon
│
├── Laravel Passport
│   ├── passport:keys --force  (génère/renouvelle les clés RSA)
│   └── passport:client --personal  (créé uniquement si aucun client n'existe)
│
├── Optimisations (uniquement si APP_ENV=production)
│   ├── config:cache
│   ├── route:cache
│   └── view:cache
│
└── Lien symbolique storage:link
```

### Phase 3 — Démarrage des services

Une fois l'initialisation terminée, supervisord prend le relais et démarre tous les processus en parallèle.

---

## Prérequis sur l'hôte

Avant le premier démarrage, si tu utilises des volumes montés localement, créer les répertoires avec les bonnes
permissions :

```bash
mkdir -p ./docs ./app
chown -R 1000:1000 ./docs ./app
```

> **Pourquoi ?** Docker crée les répertoires manquants en `root`. Le conteneur tourne en UID 1000 (mercator) et ne
> pourrait pas y écrire.

---

## Déploiement avec Docker Compose

### Fichier `docker-compose.yml` minimal

```yaml
services:
  mercator-db:
    image: mariadb:11
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: changeme
      MYSQL_DATABASE: mercator
      MYSQL_USER: mercator
      MYSQL_PASSWORD: changeme
    healthcheck:
      test: [ "CMD-SHELL", "mariadb-admin ping -h 127.0.0.1 -u root -p$$MYSQL_ROOT_PASSWORD || exit 1" ]
      interval: 5s
      timeout: 5s
      retries: 20
    volumes:
      - mercator_db_data:/var/lib/mysql

  mercator-app:
    image: ghcr.io/dbarzin/mercator:latest
    restart: unless-stopped
    ports:
      - "8080:8080"
    volumes:
      - ./docs:/var/www/mercator/storage/docs
      - ./app:/var/www/mercator/storage/app
    environment:
      APP_ENV: production
      APP_URL: https://mercator.yourdomain.com
      DB_CONNECTION: mariadb
      DB_HOST: mercator-db
      DB_PORT: 3306
      DB_DATABASE: mercator
      DB_USERNAME: mercator
      DB_PASSWORD: changeme
    depends_on:
      mercator-db:
        condition: service_healthy

volumes:
  mercator_db_data:
```

### Démarrage

```bash
docker compose up -d
```

### Suivi des logs de démarrage

```bash
docker logs -f mercator-app
```

---

## Variables d'environnement

| Variable          | Valeur par défaut | Description                                                                   |
|-------------------|-------------------|-------------------------------------------------------------------------------|
| `APP_ENV`         | `production`      | Environnement Laravel (`production`, `local`)                                 |
| `APP_URL`         | —                 | URL publique de l'application                                                 |
| `APP_KEY`         | auto-généré       | Clé de chiffrement Laravel (base64, 32 octets)                                |
| `APP_DEBUG`       | `false`           | Activer les messages d'erreur détaillés                                       |
| `APP_FORCE_HTTPS` | `false`           | Forcer les redirections HTTPS                                                 |
| `DB_CONNECTION`   | `sqlite`          | Driver base de données (`mariadb`, `mysql`, `pgsql`, `sqlite`)                |
| `DB_HOST`         | —                 | Hôte du serveur de base de données                                            |
| `DB_PORT`         | `3306`            | Port du serveur de base de données                                            |
| `DB_DATABASE`     | —                 | Nom de la base de données                                                     |
| `DB_USERNAME`     | —                 | Utilisateur de la base de données                                             |
| `DB_PASSWORD`     | —                 | Mot de passe de la base de données                                            |
| `USE_DEMO_DATA`   | `0`               | Injecter les données de démonstration au premier démarrage (`1` pour activer) |
| `SEED_DATABASE`   | `0`               | Injecter les données initiales au premier démarrage (`1` pour activer)        |
| `CACHE_DRIVER`    | `file`            | Driver de cache (`file`, `redis`, `database`)                                 |
| `SESSION_DRIVER`  | `file`            | Driver de session (`file`, `redis`, `database`)                               |

---

## Mise à jour de Mercator

```bash
# Récupérer la nouvelle image
docker compose pull

# Redémarrer le conteneur (les migrations s'exécutent automatiquement)
docker compose up -d
```

> Les migrations sont **idempotentes** : elles ne s'exécutent que si de nouvelles migrations sont détectées. La base de
> données et les données existantes sont préservées.

---

## Mode standalone (SQLite)

Sans aucune configuration de base de données, Mercator démarre en mode SQLite avec une base locale :

```bash
docker run -p 8080:8080 ghcr.io/dbarzin/mercator:latest
```

Ce mode est adapté pour une démonstration ou un test rapide. Les données sont perdues à l'arrêt du conteneur sauf si un
volume est monté sur `/var/www/mercator/sql`.

---

## Déploiement dans Portainer

1. Aller dans **Stacks** → **Add stack**
2. Coller le contenu du `docker-compose.yml` dans l'éditeur
3. Cliquer sur **Deploy the stack**

> ⚠️ Ne pas définir de directive `user:` dans la configuration du stack. Le conteneur gère lui-même son utilisateur (
`mercator`, UID 1000) via le `Dockerfile`.

---

## Résolution des problèmes courants

### `Unable to set application key. No APP_KEY variable was found`

Le fichier `.env` ne contient pas de ligne `APP_KEY=`. Mercator la crée automatiquement depuis la version 2026.03.12. Si
le problème persiste sur une image plus ancienne, ajouter la variable dans le `docker-compose.yml` :

```yaml
environment:
  APP_KEY: base64:VOTRE_CLE_ICI
```

Générer une clé :

```bash
docker run --rm ghcr.io/dbarzin/mercator:latest php artisan key:generate --show
```

### `Error: Can't drop privilege as nonroot user`

supervisord tente de démarrer avec des changements d'utilisateur mais tourne déjà en non-root. Vérifier que :

- La directive `user:` est absente du `docker-compose.yml`
- Portainer ne force pas le mode non-root dans ses paramètres de sécurité

### Erreurs de permissions sur les volumes montés

Les répertoires `./docs` et `./app` doivent appartenir à UID 1000 :

```bash
chown -R 1000:1000 ./docs ./app
```
