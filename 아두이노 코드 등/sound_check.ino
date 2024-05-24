const int soundSensorPin = A0; // 소리 센서가 연결된 아날로그 핀

void setup() {
  Serial.begin(9600); // 시리얼 통신 시작
}

void loop() {
  int soundValue = analogRead(soundSensorPin); // 소리 센서로부터 값을 읽음

  if (soundValue > 33) { // 임계치 값 설정 (조절 필요)
    Serial.println("on"); // 소리가 감지되면 "on"을 시리얼 모니터에 출력
  } else {
    Serial.println("off"); // 소리가 감지되지 않으면 "off"를 시리얼 모니터에 출력
  }

  delay(100); // 잠시 대기 후 다음 측정으로 넘어감
}