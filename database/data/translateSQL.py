from pathlib import Path
import re
from deep_translator import GoogleTranslator

# Fichiers d'entrée/sortie
input_file = "mercator_data.sql"
output_file = "mercator_data_translated.sql"

# Lire le contenu du fichier
sql_text = Path(input_file).read_text(encoding="utf-8")

# Étape 1 : extraire tous les blocs `INSERT INTO` multi-lignes
insert_blocks = []
capture = False
buffer = []

for line in sql_text.splitlines():
    if line.strip().lower().startswith("insert into"):
        capture = True
        buffer = [line]
    elif capture:
        buffer.append(line)
        if line.strip().endswith(";"):
            insert_blocks.append("\n".join(buffer))
            capture = False
            buffer = []

# Étape 2 : extraire les chaînes SQL (gérant les échappements)
pattern = re.compile(r"'((?:[^'\\]|\\.)*)'")

raw_strings = []
corrected_strings = []

for block in insert_blocks:
    matches = pattern.findall(block)
    for raw in matches:
        corrected = raw.replace("\\'", "'").replace("\\\\", "\\")
        if (
            re.search(r'[a-zA-Z]', corrected) and     # contient des lettres
            corrected.upper() != "NULL" and
            not re.match(r"\d{4}-\d{2}-\d{2}", corrected)  # n'est pas une date
        ):
            raw_strings.append(raw)
            corrected_strings.append(corrected)
            print(f"🔍 Chaîne retenue : {corrected}")

# Étape 3 : traduction des chaînes uniques
translator = GoogleTranslator(source='fr', target='en')
translation_map = {}
unique_corrected = sorted(set(corrected_strings))

print("\n🌍 Traduction en cours...\n")
for original in unique_corrected:
    try:
        translated = translator.translate(original)
        translation_map[original] = translated
        print(f"✅ '{original}' → '{translated}'")
    except Exception as e:
        print(f"❌ Erreur pour '{original}': {e}")
        translation_map[original] = original  # on garde la version d'origine si erreur

# Étape 4 : remplacement dans le texte SQL original
translated_sql = sql_text
for raw, corrected in zip(raw_strings, corrected_strings):
    if corrected in translation_map:
        translated = translation_map[corrected]
        translated_escaped = translated.replace("'", "\\'")
        translated_sql = translated_sql.replace(f"'{raw}'", f"'{translated_escaped}'")

# Étape 5 : sauvegarde du fichier traduit
Path(output_file).write_text(translated_sql, encoding="utf-8")
print(f"\n✅ Fichier traduit enregistré : {output_file}")
