# Mercator

[![Latest Release](https://img.shields.io/github/release/dbarzin/mercator.svg?style=flat-square)](https://github.com/dbarzin/mercator/releases/latest)
![License](https://img.shields.io/github/license/dbarzin/mercator.svg?style=flat-square)
![Contributors](https://img.shields.io/github/contributors/dbarzin/mercator.svg?style=flat-square)
![Stars](https://img.shields.io/github/stars/dbarzin/mercator?style=flat-square)
[![Artifact Hub](https://img.shields.io/endpoint?url=https://artifacthub.io/badge/repository/mercator)](https://artifacthub.io/packages/search?repo=mercator)

**Mercator** is a powerful and versatile open-source web application designed to facilitate the mapping of information systems, as outlined in the [Mapping The Information System Guide](https://cyber.gouv.fr/en/publications/mapping-information-system) by [ANSSI](https://cyber.gouv.fr/en/). Whether you're an operator of vital importance or part of a broader IT governance framework, Mercator is an essential tool for gaining visibility, control, and ensuring the resilience of your information systems.

📚 [Explore the Documentation](https://dbarzin.github.io/mercator/) | 🔍 [View the Application Sources](https://github.com/dbarzin/mercator/tree/master/app)

📖 _Read this in other languages:_ [French](README.fr.md)

## 🌟 **Key Features**

- 🖥️ **Comprehensive Visualizations:** Generate graphical representations of your ecosystem, including logical, administrative, and physical infrastructure views.
- 📝 **Architecture Reports:** Automatically create detailed architecture reports of your information system.
- 🗺️ **Mapping Diagrams:** Draw and export mapping diagrams to visually communicate system architecture.
- ✅ **Compliance Monitoring:** Assess and compute compliance levels across your systems.
- 🔒 **Security Integrations:** Search for vulnerabilities using [CVE-Search](https://github.com/cve-search/cve-search) integration.
- 📊 **Data Export:** Export data in various formats, including Excel, CSV, and PDF.
- 🌐 **REST API:** Seamlessly integrate with other systems using the REST API with JSON support.
- 👥 **Multi-User Management:** Role-based access control for collaborative environments.
- 🌍 **Multilingual Support:** Available in multiple languages for global teams.
- 🔗 **LDAP/Active Directory Integration:** Connect with existing user directories for streamlined authentication.
- 🛠️ **CPE Support:** Leverage [Common Platform Enumeration (CPE)](https://nvd.nist.gov/products/cpe) for enhanced system identification.

## 🖼️ **Screenshots**

### 🏠 **Main Dashboard**
[<img src="public/screenshots/mercator1.png" width="400" height="300">](public/screenshots/mercator1.png) [<img src="public/screenshots/mercator2.png" width="400" height="300">](public/screenshots/mercator2.png)

### 📊 **Compliance Levels**
[<img src="public/screenshots/mercator3.png" width="400">](public/screenshots/mercator3.png)

### 🔧 **Input Screens**
[<img src="public/screenshots/mercator4.png" width="400" height="200">](public/screenshots/mercator4.png) [<img src="public/screenshots/mercator5.png" width="400" height="200">](public/screenshots/mercator5.png)

### 🗺️ **Cartography Drawing**
[<img src="public/screenshots/mercator6.png" width="400" height="300">](public/screenshots/mercator6.png) [<img src="public/screenshots/mercator7.png" width="400" height="300">](public/screenshots/mercator7.png)

### 🔍 **Data Exploration**
[<img src="public/screenshots/mercator9.png" width="400">](public/screenshots/mercator9.png)

### 🗂️ **Data Model**
[<img src="public/screenshots/mercator8.png" width="400">](public/screenshots/mercator8.png)

## 🛠️ **Technologies Used**

- **Backend:** PHP, Laravel
- **Frontend:** JavaScript
- **Databases:** MariaDB, MySQL, PostgreSQL, and SQLite ([See Laravel Database Documentation](https://laravel.com/docs/master/database#introduction))
- **Additional Libraries:** WebAssembly, Graphviz, ChartJS

## 📦 **Installation**

### 🔧 Manual Installation

For detailed instructions, please refer to the installation guides:
- [Installation on Ubuntu](https://github.com/dbarzin/mercator/blob/master/INSTALL.md)
- [Installation on RedHat](https://github.com/dbarzin/mercator/blob/master/INSTALL.RedHat.md)

### 🐳 Docker Installation

Get up and running quickly using Docker. Run a local instance in development mode:

```bash
docker run -it --rm -e USE_DEMO_DATA=1 -p 8080:8080 --name mercator ghcr.io/dbarzin/mercator:latest
```

If you don't want to use the demo database, the first time you start the Docker you have to seed the database to create the admin user :

```bash
docker run -it --rm -e SEED_DATABASE=1 -p 8080:8080 --name mercator ghcr.io/dbarzin/mercator:latest
```
To persist your data using SQLite:

```bash
touch ./db.sqlite && chmod a+w ./db.sqlite
docker run -it --rm -e APP_ENV=development -p 8080:8080 -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite ghcr.io/dbarzin/mercator:latest
```

Populate the database with demo data:

```bash
docker run -it --rm \
           -e APP_ENV=development \
           -p 8080:8080 \
           -v $PWD/db.sqlite:/var/www/mercator/sql/db.sqlite \
           -e USE_DEMO_DATA=1 \
           ghcr.io/dbarzin/mercator:latest
```

Access your instance at [http://127.0.0.1:8080](http://127.0.0.1:8080).

    user : admin@admin.com
    password : password

For a production-ready environment with HTTPS and automated setup, check out the [docker-compose](docker-compose/) folder.

## 📜 **Changelog**

Stay up to date with the latest improvements and updates in the [Changelog](https://github.com/dbarzin/mercator/blob/master/CHANGELOG.md).

## 📄 **License**

Mercator is open-source software distributed under the [GPL License](https://www.gnu.org/licenses/licenses.html).
