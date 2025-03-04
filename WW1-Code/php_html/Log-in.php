<?php
// 在使用 $is_invalid 之前先定义它
$is_invalid = false;

//检查请求方法是否为post，即表单是否已提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 连接到数据库
    $mysqli = new mysqli("localhost", "username", "password", "database");

    // 检查连接是否成功
    if ($mysqli->connect_error) {
        die("连接失败: " . $mysqli->connect_error);
    }

    // 获取表单提交的电子邮件和密码
    $email = $_POST["email"];
    $password = $_POST["password"];

    // 查询数据库以验证用户
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
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
            <h1> Log-in</h1>

            <?php if ($is_invalid): ?>
                <div style="text-align: center; color: red; margin-bottom: 50px;">
                  <em> Invalid Login</em>
                </div>
            <?php endif; ?>

            <div class="input-box">
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="Enter Your Email" required
                       value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"> <!-- 防止xss攻击 -->
                <i class='bx bxs-user-circle'></i>
            </div>
            <div class="input-box">
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                <i class='bx bxs-lock'></i>
            </div>

            <div class="rememberforgot">
                <label><input type="checkbox"> Remember Me </label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="button">Log-in</button>

            <div class="Register-link">
                <p>Don't have an Account? 
                    <a href="../register.html">Register Here</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>