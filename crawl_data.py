import requests
from bs4 import BeautifulSoup
import pandas as pd
import csv
import traceback
from datetime import datetime

def fetch_products(url):
    response = requests.get(url)
    if response.status_code == 200:
        return response.content
    else:
        return

def get_link_product(html_content):
    soup = BeautifulSoup(html_content, 'html.parser')
    products = soup.find_all('div', class_='item-info mt-1')
    link_data = []
    for product in products:
        link = 'https://giadungonline.vn' + product.find('h3', class_='item-title').find('a').get('href')
        link_data.append({'url': link})
    return link_data

def fetch_all_products():
    all_products = [
            [
                'title',
                'description',
                'price',
                'category',
                'image_url',
            ]
        ]

    page = 1

    while True:
        try:
            page += 1
            print(f"Fetching page {page}...")
            
            # html_content = fetch_products(f"https://fancyclassy.shop/collections/handbags/page/{page}/")
            html_content = fetch_products(f"https://giadungonline.vn/collections/all?q=&page={page}&view=grid")
            link_products = get_link_product(html_content)
            # link_products = [f"https://fancyclassy.shop/products/gucci-sandals-3/", f"https://fancyclassy.shop/products/prada-sandals-7/", f"https://fancyclassy.shop/products/prada-sandals-8/"]
            for link_product in link_products:
                print('fetch: ' + link_product.get('url'))
                for prod in get_info_product(link_product.get('url')):
                # print('fetch: ' + link_product)
                # for prod in get_info_product(link_product):
                    all_products.append(prod)
            
            if (page == 10):
                break
            
        except Exception as e:
            print(f"An error occurred: {e}")
            traceback.print_exc()
            break

    return all_products

def get_info_product(url):
    response = requests.get(url)
    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')

        # get title product
        title = soup.find('h1', class_='product-name').text
         
        # get price
        price_text = soup.find('div', class_='product-price').find('span', class_='special-price').text
        price = int(price_text.split('.')[0])
        
        # get image
        images_url = soup.find('picture', class_='position-relative')

        if images_url:
            first_image = images_url.find('source')['srcset']  # Lấy URL của ảnh đầu tiên
            if first_image.startswith("//"):  # Kiểm tra và bổ sung HTTPS nếu thiếu
                first_image = "https:" + first_image

        arr_product = [
            [
                title,
                '',
                price,
                'Noi nieu song chao',
                first_image
            ]
        ]
   

        return arr_product

    else:
        return False

# get_info_product('https://fancyclassy.shop/products/prada-sandals-2/')
# Fetch all products
all_product_data = fetch_all_products()

# Tên file CSV cần lưu
csv_file = 'product_data.csv'

# Lưu dữ liệu vào file CSV
with open(csv_file, mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    
    # Ghi từng hàng của mảng vào file CSV
    writer.writerows(all_product_data)

print("Data has been successfully saved to all_product.csv")
