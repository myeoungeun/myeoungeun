<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 300px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 10px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            var code = document.forms["loginForm"]["code"].value;
            var id = document.forms["loginForm"]["id"].value;
            var password = document.forms["loginForm"]["password"].value;

            if (code == "") {
                alert("입력하신 코드가 없습니다.");
                return false;
            }
            if (id == "") {
                alert("아이디를 입력하세요.");
                return false;
            }
            if (password == "") {
                alert("비밀번호를 입력하세요.");
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>로그인</h2>
        <form name="loginForm" action="loginAction.php" onsubmit="return validateForm()" method="post">
            <div class="form-group">
                <label for="code">Code:</label>
                <input type="text" id="code" name="code">
            </div>
            <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <input type="submit" value="로그인">
        </form>
    </div>
</body>
</html>
