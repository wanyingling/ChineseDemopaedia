import csv
import re

# Read the content from the file
with open('demopedia_cn_en_html.csv', 'r', encoding='utf-8') as file:
    lines = file.readlines()[1:]  # Skip the header line

# Regular expression patterns
textterm_pattern = r'<strong class="textterm">(.+?)</strong><sup class="textterm">(\d+)</sup>'

# Initialize variables
data = []

# Iterate over each line
for line in lines:
    print(f"Processing line: {line.strip()}")

    # Split the line into section, index, and content
    parts = line.strip().split(',', 2)
    if len(parts) == 3:
        section = parts[0]
        index = parts[1].strip('"')
        content = parts[2].strip('"')

        print(f"Section: {section}")
        print(f"Index: {index}")
        print(f"Content: {content}")

        # Replace the double quotes with single quotes in the content
        content = content.replace('""', '"')

        # Find all the textterm concepts using regular expressions
        textterm_matches = re.findall(textterm_pattern, content)
        for match in textterm_matches:
            concept = match[0]
            concept_index = f"{index}-{match[1]}"
            data.append([section, index, concept, concept_index])

            print(f"Extracted concept: {concept}")
            print(f"Corresponding index: {concept_index}")
    else:
        print("Skipping line due to incorrect format")

    print("---")

# Write the data to a CSV file
with open('output.csv', 'w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['Section', 'Index', 'Concept', 'Corresponding Index'])
    writer.writerows(data)

print("Data extracted and saved to output.csv")