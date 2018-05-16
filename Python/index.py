'''
    created at May 16 2018
    user annhuny
'''
import os
import requests
from bs4 import BeautifulSoup as bs

def httpGet (url):
    try:
        r = requests.get(url)
        r.encoding = 'utf-8'
        return r.text
    except:
        print('network can not reach')

def perPage (url):
    soup = bs(httpGet(url), 'html.parser')
    urlPerPage = [x['href'] for x in soup.find_all('a', class_ = 'fancyimg')]
    return urlPerPage

def extractPage(url):
    soup = bs(httpGet(url), 'html.parser')

    title = soup.title.text
    path = os.path.join('img', title)
    if not os.path.exists(path):
        os.makedirs(path)

    imgUrls = [x['src'] for x in soup.select('.single-text p img')]
    for i, imgUrl in enumerate(imgUrls, 1):
        filePath = os.path.join(path, str(i) + '.jpg')
        downLoad(filePath, imgUrl)
        print('%s %d / %d' % (title, i, len(imgUrls)))

def downLoad (filePath, url):
    with open(filePath, 'wb') as file:
        file.write(requests.get(url).content)


if __name__ == '__main__':
    baseUrl = 'http://www.zhainanmao.com/nvshen/page/'

    for page in range(1, 28):
        for titlesPerPage in perPage(baseUrl + str(page)):
            extractPage(titlesPerPage)