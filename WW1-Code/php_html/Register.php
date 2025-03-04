<?php
// 包含数据库连接文件
require_once 'db_config.php';

//检查请求方法是否为post，即表单是否已提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 连接到数据库
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    // 检查连接是否成功
    if ($mysqli->connect_error) {
        die("连接失败: " . $mysqli->connect_error);
    }

    // 获取表单提交的电子邮件和密码
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // 对密码进行加密

    // 插入新用户到数据库
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    // 关闭数据库连接
    $stmt->close();
    $mysqli->close();

    // 注册成功，重定向到登录页面
    header("Location: Log-in.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports-Online Register</title>
    <link rel="stylesheet" href="../css/register-style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="header header-width">
        <div class="container">
            <div class="navbar navbar-backlogin">
                <div class="logo">
                    <img src="../rsc/WebLogo.png" width="200px">
                </div>
                <nav>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../products.php">Products</a></li>
                        <li><a href="../about.html">About</a></li>
                        <li><a href="../contactus.html">Contact Us</a></li>
                    </ul>
                </nav>
                <img src="images/cart1.png" width="30px" height="30px">
                <img src="../rsc/cart1.png" width="30px" height="30px">
            </div> 
        </div>
    </div>

    <div class="wrapper">
        <form method="post">
            <h1> Register</h1>

            <div class="input-box">
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="Enter Your Email" required>
                <i class='bx bxs-user-circle'></i>
            </div>
            <div class="input-box">
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                <i class='bx bxs-lock'></i>
            </div>

            <button type="submit" class="button">Register</button>

            <div class="Login-link">
                <p>Already have an Account? 
                    <a href="Log-in.php">Log-in Here</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
