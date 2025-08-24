# Mercator Data

This repository contains test data for the [Mercator](https://github.com/dbarzin/mercator) application,
along with utility scripts for database backup and translation of data content between languages.

## 📁 Contents

| File / Pattern        | Description |
|------------------------|-------------|
| `backup.sh`            | Dumps the Mercator database to a file named `mercator_data.sql`. |
| `translateSQL.py`      | Translates the content of `mercator_data.sql` into English, producing `mercator_data_translated.sql`. |
| `*.sql`                | SQL files with sample/test data for development and demonstrations. |

## 🛠 Requirements

To use `translateSQL.sh`, you need to install [deep-translator](https://pypi.org/project/deep-translator/):

```bash
pip install deep-translator
````

We recommend running the translation in a virtual environment to avoid interfering with system packages.

## 🌍 Translation Logic

The `translateSQL` script:

* Extracts all textual content from SQL `INSERT INTO` statements.
* Ignores numbers, dates, NULL values, and empty fields.
* Detects and handles escaped characters (e.g., `\\'`, `\\n`).
* Uses Google Translate (via `deep-translator`) to translate French content to English.
* Outputs a fully translated SQL file: `mercator_data_translated.sql`.

## Import

To import a test database use these commands, first, clear the database :

```bash
php artisan migrate:fresh --seed
```

Import the selected test file :

```bash
mysql mercator < data/mercator_data_en.sql
```
