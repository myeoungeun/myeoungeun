import requests

url = 'http://azza.gwangju.ac.kr/~ce211927/Logout_Team/FallSaving.php'

data = {
    'BEDCODE': 'B107',
    'FROOM': '101',
    'HCODE': 'H101',
    'FLOOR': '1'
}

response = requests.post(url, data=data)

if response.status_code == 200:
    print('Request successful')
    print('Response from PHP:', response.text)
else:
    print('Request failed with status code:', response.status_code)
    print('Response from PHP:', response.text)