
import re

input_file = r'd:\NTT_WEBSITE\backups\database_backup.sql'
output_file = r'd:\NTT_WEBSITE\full_mappings_base.sql'

target_tables = ['categories', 'tags', 'post_categories', 'post_tags', 'medias']
pattern = re.compile(r"INSERT INTO `(" + "|".join(target_tables) + r")`")

with open(input_file, 'r', encoding='utf-8', errors='ignore') as infile:
    with open(output_file, 'w', encoding='utf-8') as outfile:
        for line in infile:
            if pattern.search(line):
                outfile.write(line)

print(f"Extraction complete to {output_file}")
