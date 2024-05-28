import serial
import subprocess
import time

# 아두이노와 시리얼 통신 설정
ser = serial.Serial('/dev/ttyACM0', 9600) # 아두이노 연결 포트와 속도 설정
time.sleep(20)  # 시리얼 통신이 안정될 때까지 잠시 대기

process = None

while True:
    # 아두이노로부터 메시지 읽기
    if ser.in_waiting > 0:
        message = ser.readline().decode().strip()
        print(f"Received: {message}")

        # "on" 메시지가 수신되면 YOLOv5 카메라 실행
        if message == "on" and process is None:
            print("Starting YOLOv5 camera...")
            # YOLOv5 카메라 실행
            process = subprocess.Popen(['python3', 'detect.py', '--source', '0', '--weights', 'best.pt', '--conf', '0.25'])
            time.sleep(5000)  # 5초 동안 실행

            # 5초 후에 프로세스 종료
            if process.poll() is None:
                process.terminate()  # 프로세스 종료 시도
                process.wait()  # 프로세스가 완전히 종료될 때까지 대기
                print("YOLOv5 camera stopped.")

    # 프로세스가 시작되었는지 확인
    if process is not None:
        print("YOLOv5 camera process is running.")
    else:
        print("YOLOv5 camera process is not running.")

    time.sleep(10)  # 잠시 대기 후 다시 확인
