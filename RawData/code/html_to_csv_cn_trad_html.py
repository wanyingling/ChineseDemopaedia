import csv
from bs4 import BeautifulSoup
from zhconv import convert


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
            current_definition = element.encode_contents(formatter='html').decode('utf-8')  # 保留 HTML 格式
        elif element.name == 'ol' and element.get('class') == ['notes']:
            notes = ' '.join([li.encode_contents(formatter='html').decode('utf-8') for li in element.find_all('li')])  # 保留 HTML 格式
            if notes:
                if current_definition:
                    current_definition += "<br>\n备注：" + notes  # 使用 HTML 换行符 <br>
                else:
                    current_definition = "\n备注：" + notes

    # Add the last entry
    if current_h1 or current_h2 or current_h3:
        data.append([current_h1, current_h2, current_h3, current_definition])

    return data


def write_to_csv(data, filename):
    with open(filename, 'w', newline='', encoding='utf-8-sig') as file:
        writer = csv.writer(file)
        writer.writerow(
            ["First Level Heading", "Second Level Heading", "Third Level Heading", "Definition (Simplified)",
             "Definition (Traditional HK)"])
        for row in data:
            traditional_definition = convert(row[3], 'zh-hk')  # Convert the definition to Traditional Chinese (HK) while preserving HTML tags
            traditional_section = convert(row[1], 'zh-hk')  # Convert the section to Traditional Chinese (HK)
            new_row = row[:4] + [traditional_section] + [traditional_definition]
            writer.writerow(new_row)


# Read the HTML content from a file
with open('Demopaedia_cn_simple.html', 'r', encoding='utf-8') as file:
    html_content = file.read()

# Extract the content
extracted_data = extract_content(html_content)

# Write to CSV
write_to_csv(extracted_data, 'demopaedia_cn.csv')

print("CSV file has been created successfully.")