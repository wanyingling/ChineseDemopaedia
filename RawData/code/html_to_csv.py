import csv
from bs4 import BeautifulSoup

def extract_content(html_content):
    soup = BeautifulSoup(html_content, 'html.parser')
    data = []
    current_h1 = current_h2 = current_h3 = current_definition = ""

    for element in soup.find_all(['h1', 'h2', 'h3', 'div', 'ol']):
        if element.name == 'h1':
            if current_h1:
                data.append([current_h1, current_h2, current_h3, current_definition])
            current_h1 = element.text.strip()
            current_h2 = current_h3 = current_definition = ""
        elif element.name == 'h2':
            if current_h2:
                data.append([current_h1, current_h2, current_h3, current_definition])
            current_h2 = element.text.strip()
            current_h3 = current_definition = ""
        elif element.name == 'h3':
            if current_h3:
                data.append([current_h1, current_h2, current_h3, current_definition])
            current_h3 = element.text.strip()
            current_definition = ""
        elif element.name == 'div' and element.get('class') == ['definition']:
            current_definition = element.text.strip()
        elif element.name == 'ol' and element.get('class') == ['notes']:
            notes = ' '.join([li.text.strip() for li in element.find_all('li')])
            if notes:
                if current_definition:
                    current_definition += "\n참고：" + notes
                else:
                    current_definition = "참고：" + notes

    # Add the last entry
    if current_h1 or current_h2 or current_h3:
        data.append([current_h1, current_h2, current_h3, current_definition])

    return data

def write_to_csv(data, filename):
    with open(filename, 'w', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)
        writer.writerow(["First Level Heading", "Second Level Heading", "Third Level Heading", "Definition"])
        writer.writerows(data)

# Read the HTML content from a file
with open('Demopedia_kr.html', 'r', encoding='utf-8') as file:
    html_content = file.read()

# Extract the content
extracted_data = extract_content(html_content)

# Write to CSV
write_to_csv(extracted_data, 'demopedia_kr.csv')

print("CSV file has been created successfully.")