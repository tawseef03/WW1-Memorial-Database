<?php
// 在使用 $is_invalid 之前先定义它
$is_invalid = false;

// 包含数据库连接文件
require_once 'db_config.php';

//检查请求方法是否为post，即表单是否已提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 连接到数据库
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    // 检查连接是否成功
    if ($mysqli->connect_error) {
        die("connect fail: " . $mysqli->connect_error);
    }

    // 获取表单提交的用户名字和密码
    $Username = $_POST["Username"];
    $password = $_POST["password"];

    // 查询数据库以验证用户
    $sql = "SELECT * FROM users WHERE Username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $Username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // 验证密码
    if ($user && password_verify($password, $user["password"])) {
        // 登录成功，重定向到主页
        header("Location: ../index.php");
        exit;
    } else {
        // 登录失败，将 $is_invalid 设置为 true
        $is_invalid = true;
    }

    // 关闭数据库连接
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports-Online Log-in</title>
    <link rel="stylesheet" href="../css/login-stylev2.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navigation bar -->
    <nav class='navbar'>
        <!-- Website logo container -->
        <div class='logo'></div>
        <!-- Navigation links -->
        <div class='nav-links'>
            <a href='sections.php'>Sections</a> <!-- Link to Sections page -->
            <a href='about.php'>About</a> <!-- Link to About page -->
        </div>
        <!-- Shopping cart icon -->
        <img src="images/cart1.png" width="30px" height="30px">
    </nav>

    <!-- Login form container -->
    <div class="wrapper">
        <!-- Login form, redirects to index.html upon submission -->
        <form action="index.html">
            <h1>Log-in</h1> <!-- Form title -->

            <!-- Username input field -->
            <div class="input-box">
                <label for="Username"></label>
                <input type="Username" name="Username" id="Username" placeholder="Enter Your Username" required>
                <!-- Username icon -->
                <i class='bx bxs-user-circle'></i>
            </div>

            <!-- Password input field -->
            <div class="input-box">
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                <!-- Password icon -->
                <i class='bx bxs-lock'></i>
            </div>

            <!-- Remember me and forgot password section -->
            <div class="rememberforgot">
                <label><input type="checkbox"> Remember Me </label> <!-- Remember me checkbox -->
                <a href="#">Forgot Password?</a> <!-- Forgot password link -->
            </div>

            <!-- Login button -->
            <button type="submit" class="button">Log-in</button>

            <!-- Registration link -->
            <div class="Register-link">
                <p>Don't have an Account? 
                    <a href="register.html">Register Here</a> <!-- Link to registration page -->
                </p>
            </div>
        </form>
    </div>
</body>
</html>