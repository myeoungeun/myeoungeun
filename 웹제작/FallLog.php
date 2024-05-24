<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fall Log</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Fall Log</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Fall Detection</th>
            <th>Action</th>
        </tr>
        <?php
        // DB 연결
        $servername = "azza.gwangju.ac.kr";
        $username = "dbuser211927";
        $password = "ce1234";
        $dbname = "db211927";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fall 테이블에서 데이터 가져오기
        $sql = "SELECT F_DATE, F_TIME, F_ROOM, F_WHETHER FROM Fall WHERE F_WHETHER = 'Y'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                // Date
                echo "<td>" . $row["F_DATE"] . " " . $row["F_TIME"] . "</td>";
                // Fall Detection
                echo "<td>" . $row["F_ROOM"] . "호실 낙상이 감지되었습니다.</td>";
                // Action (미구현)
                echo "<td>미구현</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No falls detected.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>