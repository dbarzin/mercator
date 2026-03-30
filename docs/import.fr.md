# Interface Import / Export de Données

🇬🇧 [Read in English](/mercator/import)

Cette interface permet d’**importer** et d’**exporter** des données de la cartographie du système d'information via des feuilles de calcul.

### Accès à la page

La page d'import / export des données est accessible via le menu "Configuration" -> "Import".

[<img src="/mercator/fr/images/import.png" width="700">](images/import.fr.png)

### Fonctionnalités disponibles

#### Export de données

L’export permet de télécharger les données d’un modèle métier au format `.xlsx`.

#### Étapes

1. Sélectionner un **filtre** : écosystème, système d'information, applications...
2. Sélectionner un **objet** associé.
3. Cliquer sur **Exporter**.

#### Format du fichier

Le fichier contient : une **première ligne** d'en-têtes (colonnes) et les données de l'objet.

Les colonnes `created_at`, `updated_at`, `deleted_at` sont automatiquement **exclues**

### Import de données

Permet de mettre à jour ou d’ajouter des données à partir d’une feuille de calcul ou d'un fichier CSV.

#### Étapes

1. Choisir le **fichier** à importer.
2. Cliquer sur **Importer**.

#### Format attendu du fichier

- **Première ligne** : noms de colonnes
- **Première colonne** : champ `ID`, l'ordre des autres colonnes n'a pas d'importance.
- Le colonne qui ne sont pas présentes dans le fichier d'import **ne sont pas mises à jour**.

#### Comportement à l’import

| Cas                     | Action appliquée                      |
|-------------------------|---------------------------------------|
| Ligne avec ID + données | 🔁 Mise à jour de l’enregistrement    |
| Ligne avec ID seul      | ❌ Suppression de l’enregistrement     |
| Ligne sans ID           | ➕ Création d’un nouvel enregistrement |

Si une erreur est détectée dans le fichier, aucune modification n’est appliquée.

#### Exemple d’en-tête attendu

```plaintext
id, name, description, type, reference
```
