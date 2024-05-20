<?php
// 디버그를 위한 오류 출력 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET 파라미터 확인
$room = $_GET['room'] ?? '';
$year = $_GET['year'] ?? '';
$month = $_GET['month'] ?? '';

// 쿼리 작성
$sql = "SELECT * FROM room_data WHERE 1=1";

if ($room != '') {
    $sql .= " AND room_number = '$room'";
}
if ($year != '') {
    $sql .= " AND year = $year";
}
if ($month != '') {
    $sql .= " AND month = $month";
}

// 쿼리 실행 및 결과 확인
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Data Visualization</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        fieldset {
            border: 2px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px; /* 둥근 테두리 */
        }
        legend {
            padding: 0 10px;
            font-weight: bold;
        }
        form {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        form > div {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        form > div:last-child {
            margin-right: 0;
        }
        canvas {
            max-width: 800px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>Search Filters</legend>
        <form method="GET" action="">
            <div>
                <label for="room">Room:</label>
                <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($room); ?>">
            </div>
            <div>
                <label for="year">Year:</label>
                <select id="year" name="year">
                    <option value="">Select Year</option>
                    <?php
                    $currentYear = date("Y");
                    for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                        $selected = ($i == $year) ? "selected" : "";
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="month">Month:</label>
                <select id="month" name="month">
                    <option value="">Select Month</option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        $monthValue = str_pad($i, 2, "0", STR_PAD_LEFT);
                        $selected = ($monthValue == $month) ? "selected" : "";
                        echo "<option value='$monthValue' $selected>$monthValue</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Filter</button>
        </form>
    </fieldset>

    <canvas id="myChart"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            fetch('index.php?<?php echo $_SERVER['QUERY_STRING']; ?>')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => `${item.year}-${item.month}`);
                    const values = data.map(item => item.value);

                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Room Data',
                                data: values,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>
