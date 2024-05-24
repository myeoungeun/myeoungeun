import serial
import subprocess
import time

ser = serial.Serial('/dev/ttyACM0', 9600)

camera_process = None

while True:
    if ser.readline().decode().strip() == 'on':
        if camera_process is None or camera_process.poll() is not None:
            camera_process = subprocess.Popen(['sudo', 'libcamera-hello','-t','3000'])
            time.sleep(3000)
            camera_process.kill()