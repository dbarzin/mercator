# Mercator

[![Latest Release](https://img.shields.io/github/release/dbarzin/mercator.svg?style=flat-square)](https://github.com/dbarzin/mercator/releases/latest)
![License](https://img.shields.io/github/license/dbarzin/mercator.svg?style=flat-square)
![Contributors](https://img.shields.io/github/contributors/dbarzin/mercator.svg?style=flat-square)
![Stars](https://img.shields.io/github/stars/dbarzin/mercator?style=flat-square)

**Mercator** est une application web open-source puissante et polyvalente, conçue pour faciliter la cartographie des systèmes d'information, comme décrit dans le [Guide de cartographie du système d'information](https://cyber.gouv.fr/publications/cartographie-du-systeme-dinformation) de l'[ANSSI](https://cyber.gouv.fr/). Que vous soyez un opérateur d'importance vitale ou que vous participiez à la gouvernance informatique plus large, Mercator est un outil essentiel pour acquérir de la visibilité, du contrôle et assurer la résilience de vos systèmes d'information.

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
- **Bases de Données:** MySQL, PostgreSQL, SQLite, SQL Server ([Voir Documentation Laravel Database](https://laravel.com/docs/master/database#introduction))
- **Bibliothèques Supplémentaires:** WebAssembly, Graphviz, ChartJS

## 📦 **Installation**

### 🔧 Installation Manuelle

Pour des instructions détaillées, veuillez vous référer aux guides d'installation :
- [Installation sur Ubuntu](https://github.com/dbarzin/mercator/blob/master/INSTALL.md)
- [Installation sur RedHat](https://github.com/dbarzin/mercator/blob/master/INSTALL.RedHat.md)

### 🐳 Installation via Docker

Démarrez rapidement avec Docker. Exécutez une instance locale en mode développement :

```bash
docker run -it --rm -e USE_DEMO_DATA=1 -p 8000:80 ghcr.io/dbarzin/mercator:latest
```
Pour rendre vos données persistantes avec SQLite :

```bash
touch ./db.sqlite && chmod a+w ./db.sqlite
docker run -it --rm -e APP_ENV=development -p 8000:80 -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite ghcr.io/dbarzin/mercator:latest
```

Populez la base de données avec des données de démonstration :

```bash
docker run -it --rm \
           -e APP_ENV=development \
           -p 8000:80 \
           -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite \
           -e USE_DEMO_DATA=1 \
           ghcr.io/dbarzin/mercator:latest
```

Accédez à votre instance via [http://127.0.0.1:8000](http://127.0.0.1:8000).

    user : admin@admin.com
    password : password

Pour un environnement de production prêt à l'emploi avec HTTPS et une configuration automatisée, consultez le dossier [docker-compose](docker-compose/).

## 📜 **Changelog**

Restez informé des dernières améliorations et mises à jour dans le [Changelog](https://github.com/dbarzin/mercator/blob/master/CHANGELOG.md).

## 📄 **Licence**

Mercator est un logiciel open-source distribué sous la licence [GPL](https://www.gnu.org/licenses/licenses.html).
