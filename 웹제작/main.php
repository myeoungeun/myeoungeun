<?php
    session_start();

     // 데이터베이스 연결
     $servername = "localhost";
     $username = "dbuser211927"; // 자신의 MySQL 사용자 이름
     $password = "ce1234"; // 자신의 MySQL 암호
     $dbname = "db211927"; // 자신의 데이터베이스 이름
 
     // MySQL 연결 생성
     $conn = new mysqli($servername, $username, $password, $dbname);
 
     // 연결 확인
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
 
     // 병원 이름을 가져오는 쿼리 작성
     $sql = "SELECT h_name FROM Hospital";
 
     // 쿼리 실행
     $result = $conn->query($sql);
 
     // 결과 처리
     if ($result->num_rows > 0) {
         // 결과에서 한 행씩 가져오기
         while($row = $result->fetch_assoc()) {
             $hospitalName = $row["h_name"];
         }
     } else {
         $hospitalName = "병원 이름을 가져올 수 없음";
     }


    $sql = "SELECT floor FROM Users";
 
     // 쿼리 실행
     $result = $conn->query($sql);
 
     // 결과 처리
     if ($result->num_rows > 0) {
         // 결과에서 한 행씩 가져오기
         while($row = $result->fetch_assoc()) {
             $hospitalfloor = $row["floor"];
         }
     } else {
         $hospitalfloor = "병원 층수를 가져올 수 없음";
     }
     
 
     // MySQL 연결 종료
     $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-left, .header-right {
            display: flex;
        }

        .header-center {
            text-align: center;
        }

        .header button {
            margin-right: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            grid-gap: 10px;
            padding: 20px;
        }

        .room {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .room:hover {
            background-color: #f2f2f2;
        }

        .alarm-location{
            margin-right: 20px;
        }
        
        .alert-bubble {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 100;
        top: 50px; /* 알람 이미지 아래에 위치 */
        left : -15px; /* 알람 이미지에 맞게 위치 조정 */
        }

        .alert-bubble::before {
            content: "";
            position: absolute;
            top: -10px;
            left: 20px; /* 꼬리표 위치 조정 */
            border-width: 0 10px 10px 10px;
            border-style: solid;
            border-color: transparent transparent #ccc transparent;
        }

        .alert-bubble::after {
            content: "";
            position: absolute;
            top: -8px;
            left: 21px; /* 꼬리표 위치 조정 */
            border-width: 0 9px 9px 9px;
            border-style: solid;
            border-color: transparent transparent #fff transparent;
        }

        .header-right {
            position: relative;
        }

        .header img {
            cursor: pointer;
        }

        .alert-bubble p .close-btn {
            color: red; /* closeBtn의 색상을 빨간색으로 설정 */
            font-weight: bold; /* 글씨를 굵게 */
            size: 100px;
        }
    
    </style>
    <script>
        let socket = new WebSocket("ws://azza.gwangju.ac.kr:22022");

        function confirmAction(roomNumber) {                
            if (socket.readyState === WebSocket.OPEN) {
                socket.send(roomNumber);
                console.log('서버로 메시지 전송:' + roomNumber);
            } else {
                console.log('웹소켓 연결이 열려 있지 않습니다.');
            }
        }

        socket.onmessage = function(event) {
            var bubble = document.getElementById("alert-bubble");
            var p = document.createElement("p");
            var closeBtn = document.createElement("span");
            console.log(event.data);

            closeBtn.textContent = ' •';
            closeBtn.className = 'close-btn';
            closeBtn.onclick = function() {
                bubble.removeChild(p);
            };


            p.textContent = event.data + ' 호실 낙상이 감지되었습니다.';
            p.appendChild(closeBtn);
            bubble.appendChild(p);
            showAlertBubble()
        };

        function showAlert(roomNumber) {
            alert(roomNumber + "호실 낙상이 감지되었습니다.");
        }


        function showAlertBubble() { //알람 이미지
            var bubble = document.getElementById("alert-bubble");
            if (bubble.style.display === "none" || bubble.style.display === "") {
                bubble.style.display = "block";
            } else {
                bubble.style.display = "none";
            }
        }

        function redirectToFallCheck() {
            window.location.href = "FallCheck.php";
        }

        function redirectToFallLog() {
            window.location.href = "FallLog.php";
        }

        function logout() {
            <?php session_unset(); session_destroy(); ?>
            window.location.href = 'login.php'; // 로그인 페이지로 이동
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="header-left">
        <button onclick="redirectToFallCheck()">낙상 빈도수 확인</button>
        <button onclick="redirectToFallLog()">낙상 로그</button>
        </div>
        <div class="header-center">
            <p><?php echo $hospitalName?> : <?php echo $hospitalfloor ?>F</p>
        </div>
        <div class="header-right">
            <img class="alarm-location" src="alarm.png" alt="알람" onclick="showAlertBubble()" />
            <button onclick="logout()">로그아웃</button>
            <div id="alert-bubble" class="alert-bubble">

            </div>
        </div>
    </div>
    <div class="room-grid">
        <div class="room" onclick="confirmAction('1')">1호실</div>
        <div class="room" onclick="confirmAction('2')">2호실</div>
        <div class="room" onclick="confirmAction('3')">3호실</div>
        <div class="room" onclick="confirmAction('4')">4호실</div>
        <div class="room" onclick="confirmAction('5')">5호실</div>
        <div class="room" onclick="confirmAction('6')">6호실</div>
        <div class="room" onclick="confirmAction('7')">7호실</div>
        <div class="room" onclick="confirmAction('8')">8호실</div>
        <div class="room" onclick="confirmAction('9')">9호실</div>
        <div class="room" onclick="confirmAction('10')">10호실</div>
        <div class="room" onclick="confirmAction('11')">11호실</div>
        <div class="room" onclick="confirmAction('12')">12호실</div>
    </div>
</body>
</html>