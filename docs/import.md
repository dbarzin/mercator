## Data Import/Export Interface

This interface allows you to import and export information system mapping data via Excel files.

### Accessing the Page

The data import/export page is accessible via the "Configuration" -> "Import" menu.

[<img src="/mercator/images/import.png" width="700">](/mercator/images/import.png)

### Available Features

#### Data Export

Export allows you to download business model data in `.xlsx` format.

#### Steps

1. Select a **filter**: ecosystem, information system, applications, etc.
2. Select an associated **object**.
3. Click **Export**.

#### File format

The file contains: a **first row** of headers (columns) and the object data.

The `created_at`, `updated_at`, and `deleted_at` columns are automatically **excluded**.

### Data import

Allows you to update or add data from a structured Excel file.

#### Steps

1. Choose the **Excel file** to import.
2. Click **Import**.

#### Expected file format

- **First row**: column names
- **First column**: `ID` field, the order of the other columns does not matter.
- Columns not present in the import file **are not updated**.

#### Import behavior

| Case | Action applied |
|-----|------------------|
| Row with ID + data | 🔁 Record update |
| Row with ID only | ❌ Record deletion |
| Row without ID | ➕ New record creation |

If an error is detected in the file, no changes are applied.

#### Example of expected Excel header

```plaintext
id, name, description, type, reference
```
