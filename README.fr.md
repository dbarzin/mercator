# Mercator

[![Latest Release](https://img.shields.io/github/release/dbarzin/mercator.svg?style=flat-square)](https://github.com/dbarzin/mercator/releases/latest)
![License](https://img.shields.io/github/license/dbarzin/mercator.svg?style=flat-square)
![Contributors](https://img.shields.io/github/contributors/dbarzin/mercator.svg?style=flat-square)
![Stars](https://img.shields.io/github/stars/dbarzin/mercator?style=flat-square)
[![Artifact Hub](https://img.shields.io/endpoint?url=https://artifacthub.io/badge/repository/mercator)](https://artifacthub.io/packages/search?repo=mercator)
[![OW2 Project](https://img.shields.io/badge/OW2-Mercator-blue)](https://projects.ow2.org/view/mercator/)

**Mercator** est une application web open-source conçue pour faciliter la gestion de la cartographie des systèmes d'information, comme décrit dans le [Guide de Cartographie du Système d'Information](https://cyber.gouv.fr/publications/cartographie-du-systeme-dinformation) de l'[ANSSI](https://cyber.gouv.fr/). Que vous soyez une entité essentielle ou partie intégrante d'un cadre de gouvernance IT plus large, Mercator est un outil vital pour améliorer la visibilité, maintenir le contrôle et assurer la résilience de vos systèmes d'information.

📚 [Explorer la Documentation](https://dbarzin.github.io/mercator/) | 🔍 [Voir les Sources de l'Application](https://github.com/dbarzin/mercator/tree/master/app)

📖 _Lire ceci dans d'autres langues :_ [Anglais](README.md)

## 🌟 **Principales fonctionnalités**

- 🖥️ **Visualisations Complètes :** Générer des représentations graphiques de votre écosystème, y compris les vues logiques, administratives et de l'infrastructure physique.
- 📝 **Rapports d'Architecture :** Créez automatiquement des rapports détaillés sur l'architecture de votre système d'information.
- 🗺️ **Diagrammes de Cartographie :** Dessinez et exportez des diagrammes de cartographie pour communiquer visuellement l'architecture du système.
- ✅ **Suivi de la Conformité :** Évaluez et calculez les niveaux de conformité de vos systèmes.
- 🔒 **Intégrations de Sécurité :** Recherchez des vulnérabilités en utilisant l'intégration [CVE-Search](https://github.com/cve-search/cve-search).
- 📊 **Exportation de Données :** Exportez des données dans divers formats, y compris Excel, CSV et PDF.
- 🌐 **API REST :** Intégrez facilement avec d'autres systèmes en utilisant l'API REST avec support JSON.
- 👥 **Gestion Multi-Utilisateurs :** Contrôle d'accès basé sur les rôles pour les environnements collaboratifs.
- 🌍 **Support Multilingue :** Disponible en plusieurs langues pour les équipes internationales.
- 🔗 **Intégration LDAP/Active Directory :** Connectez-vous avec des annuaires d'utilisateurs existants pour une authentification simplifiée.
- 🛠️ **Support CPE :** Exploitez [Common Platform Enumeration (CPE)](https://nvd.nist.gov/products/cpe) pour une identification améliorée du système.

## 🖼️ **Captures d'écran**

### 🏠 **Tableau de bord principal**
[<img src="public/screenshots/mercator1.png" width="400" height="300">](public/screenshots/mercator1.png) [<img src="public/screenshots/mercator2.png" width="400" height="300">](public/screenshots/mercator2.png)

### 📊 **Niveaux de Conformité**
[<img src="public/screenshots/mercator3.png" width="400">](public/screenshots/mercator3.png)

### 🔧 **Écrans de Saisie**
[<img src="public/screenshots/mercator4.png" width="400" height="200">](public/screenshots/mercator4.png) [<img src="public/screenshots/mercator5.png" width="400" height="200">](public/screenshots/mercator5.png)

### 🗺️ **Cartographie**
[<img src="public/screenshots/mercator6.png" width="400" height="300">](public/screenshots/mercator6.png) [<img src="public/screenshots/mercator7.png" width="400" height="300">](public/screenshots/mercator7.png)

### 🔍 **Exploration de Données**
[<img src="public/screenshots/mercator9.png" width="400">](public/screenshots/mercator9.png)

### 🗂️ **Modèle de Données**
[<img src="public/screenshots/mercator8.png" width="400">](public/screenshots/mercator8.png)

## 🛠️ **Technologies Utilisées**

- **Backend:** PHP, Laravel
- **Frontend:** JavaScript
- **Bases de Données:** MariaDB, MySQL, PostgreSQL, and SQLite ([Voir Documentation Laravel Database](https://laravel.com/docs/master/database#introduction))
- **Bibliothèques Supplémentaires:** WebAssembly, Graphviz, ChartJS

## 📦 **Installation**

### 🔧 Installation Manuelle

Pour des instructions détaillées, veuillez vous référer aux guides d'installation :
- [Installation sur Ubuntu](https://github.com/dbarzin/mercator/blob/master/INSTALL.md)
- [Installation sur RedHat](https://github.com/dbarzin/mercator/blob/master/INSTALL.RedHat.md)

### 🐳 Installation via Docker

Démarrez rapidement avec Docker. Exécutez une instance locale en mode développement avec la base de données de démonstration :

```bash
docker run -it --rm -e USE_DEMO_DATA=1 -p 8080:8080 --name mercator ghcr.io/dbarzin/mercator:latest
```

Si vous ne souhaitez pas utiliser la base de données de démonstration, la première fois que vous démarrez Docker, vous devez initialiser la base de données pour créer l'utilisateur administrateur avec l'option SEED_DATABASE:

```bash
docker run -it --rm -e SEED_DATABASE=1 -p 8080:8080 --name mercator ghcr.io/dbarzin/mercator:latest
```

Pour rendre vos données persistantes avec SQLite :

```bash
touch ./db.sqlite && chmod a+w ./db.sqlite
docker run -it --rm -e APP_ENV=development -p 8080:8080 -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite ghcr.io/dbarzin/mercator:latest
```

Populez la base de données avec des données de démonstration :

```bash
docker run -it --rm \
           -e APP_ENV=development \
           -p 8080:8080 \
           -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite \
           -e USE_DEMO_DATA=1 \
           ghcr.io/dbarzin/mercator:latest
```

Accédez à votre instance via [http://127.0.0.1:8080](http://127.0.0.1:8080).

    user : admin@admin.com
    password : password

Pour un environnement de production prêt à l'emploi avec HTTPS et une configuration automatisée, consultez le dossier [docker-compose](docker-compose/).

## 📜 **Changelog**

Restez informé des dernières améliorations et mises à jour dans le [Changelog](https://github.com/dbarzin/mercator/blob/master/CHANGELOG.md).

## 📄 **Licence**

Mercator est un logiciel open-source distribué sous la licence [GPL](https://www.gnu.org/licenses/licenses.html).

## 🤝 Partenariats

Mercator est un projet open source soutenu par l'[organisation OW2](https://www.ow2.org/view/Mercator/), qui promeut des logiciels libres fiables, industriels et interopérables.

[![OW2 Project](https://img.shields.io/badge/OW2-Mercator-blue)](https://projects.ow2.org/view/mercator)
