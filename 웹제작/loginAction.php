<?php
// 디버그를 위한 오류 출력 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 세션 시작
session_start();

// 데이터베이스 연결 정보
$servername = "azza.gwangju.ac.kr";
$username = "dbuser211927"; // 데이터베이스 관리자에게 확인한 사용자 이름
$password = "ce1234"; // 데이터베이스 관리자에게 확인한 비밀번호
$dbname = "db211927";

// 사용자 입력 값 가져오기
$code = $_POST['code'];
$id = $_POST['id'];
$passwords = $_POST['password'];

// MySQLi로 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 코드 확인
$sql = "SELECT * FROM Users WHERE h_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('코드가 일치하지 않습니다.');history.go(-1);</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// 코드가 일치하면 ID 확인
$sql = "SELECT * FROM Users WHERE h_code = ? AND floor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $code, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('아이디가 일치하지 않습니다.');history.go(-1);</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// 코드와 ID가 일치하면 비밀번호 확인
$sql = "SELECT * FROM Users WHERE h_code = ? AND floor = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $code, $id, $passwords);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.');history.go(-1);</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// 모든 것이 일치하면 로그인 성공
// 세션에 h_code와 floor 값을 저장
$_SESSION['h_code'] = $code;
$_SESSION['floor'] = $id;

// 메인 페이지로 리디렉션
header("Location: main.php");

// 연결 종료
$stmt->close();
$conn->close();
?>
