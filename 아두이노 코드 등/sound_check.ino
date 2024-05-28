const int soundSensorPin = A0; // 소리 센서가 연결된 아날로그 핀
const int ledPin = 2; // LED가 연결된 디지털 핀

void setup() {
  Serial.begin(9600); // 시리얼 통신 시작
  pinMode(ledPin, OUTPUT); // LED 핀을 출력으로 설정
}

void loop() {
  int soundValue = analogRead(soundSensorPin); // 소리 센서로부터 값을 읽음
  Serial.print("Sound Value: ");
  Serial.println(soundValue); // 소리 센서 값을 시리얼 모니터에 출력

  if (soundValue > 33) { // 임계치 값 설정 (조절 필요)
    Serial.println("on"); // 소리가 감지되면 "on"을 시리얼 모니터에 출력
    digitalWrite(ledPin, HIGH); // LED 켜기
    delay(1000); // LED를 1초 동안 켜둠
    digitalWrite(ledPin, LOW); // LED 끄기
  } else {
    Serial.println("off"); // 소리가 감지되지 않으면 "off"를 시리얼 모니터에 출력
    digitalWrite(ledPin, LOW); // LED 끄기
  }

  delay(500); // 잠시 대기 후 다음 측정으로 넘어감
}
