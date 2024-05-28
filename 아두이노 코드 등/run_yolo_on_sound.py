import serial
import subprocess
import time

# 아두이노와 시리얼 통신 설정
ser = serial.Serial('/dev/ttyACM0', 9600) # 아두이노 연결 포트와 속도 설정
time.sleep(2)  # 시리얼 통신이 안정될 때까지 잠시 대기

while True:
    # 아두이노로부터 메시지 읽기
    if ser.in_waiting > 0:
        message = ser.readline().decode().strip()
        print(f"Received: {message}")

        # "on" 메시지가 수신되면 YOLOv5 카메라 실행
        if message == "on":
            print("Starting YOLOv5 camera...")
            # YOLOv5 카메라 실행
            process = subprocess.Popen(['python3', 'detect.py', '--source', '0', '--weights', 'best.pt', '--conf', '0.25'])
            time.sleep(5)  # 5초 동안 실행
            process.terminate()  # YOLOv5 카메라 종료
            print("YOLOv5 camera stopped.")