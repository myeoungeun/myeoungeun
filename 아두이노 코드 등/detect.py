import torch
import cv2
import mysql.connector
from datetime import datetime
import time

# YOLOv5 모델 로드
model = torch.hub.load('ultralytics/yolov5', 'yolov5s', pretrained=True)

def detect_person(image):
    results = model(image)
    detected = any(result['name'] == 'person' for result in results.pandas().xyxy[0].to_dict(orient='records'))
    return detected

def update_fall_status(h_code, floor, bed_code):
    try:
        conn = mysql.connector.connect(
            host='your_mysql_host',
            user='your_mysql_user',
            password='your_mysql_password',
            database='your_mysql_database'
        )
        cursor = conn.cursor()

        now = datetime.now()
        f_date = now.strftime('%Y-%m-%d')
        f_time = now.strftime('%H:%M:%S')

        sql_update = ''' 
        UPDATE Fall
        SET F_WHETHER = 'Y', F_DATE = %s, F_TIME = %s
        WHERE h_code = %s AND floor = %s AND BedCODE = %s 
        '''
        cursor.execute(sql_update, (f_date, f_time, h_code, floor, bed_code))
        conn.commit()

        print("Fall status updated successfully")
    except mysql.connector.Error as error:
        print("Failed to update fall status:", error)
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

def get_user_info():
    try:
        conn = mysql.connector.connect(
            host='your_mysql_host',
            user='your_mysql_user',
            password='your_mysql_password',
            database='your_mysql_database'
        )
        cursor = conn.cursor()
        cursor.execute("SELECT h_code, floor FROM Users WHERE user_id = 'logged_in_user_id'")
        user_info = cursor.fetchone()
        return user_info
    except mysql.connector.Error as error:
        print("Failed to get user info:", error)
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

# 카메라 캡처
cap = cv2.VideoCapture(0)
person_detected = False
start_time = None

while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    if detect_person(frame):
        if not person_detected:
            person_detected = True
            start_time = time.time()
        elif time.time() - start_time >= 2:
            h_code, floor = get_user_info()
            if h_code and floor:
                update_fall_status(h_code, floor, 'B123')  # 실제 BedCODE 값으로 변경
                break
    else:
        person_detected = False
        start_time = None

cap.release()
cv2.destroyAllWindows()
