<?php
session_start();
require_once 'db_config.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    
    if ($mysqli->connect_error) {
        die("连接失败: " . $mysqli->connect_error);
    }
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // 修改SQL查询以包含用户类型
    $sql = "SELECT * FROM users WHERE Username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user["Password"])) {
        // 设置会话变量
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user["User Type"];
        $_SESSION['user_id'] = $user["UserID"];
        
        // 根据用户类型重定向
        if ($user["User Type"] === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: userSection.php");
        }
        exit;
    } else {
        $error_message = "用户名或密码错误";
    }
    
    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Bradford Group</title>
    <link rel="stylesheet" href="../css/login-styleV2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <nav class='navbar'>
        <div class='logo'>
            <img src="../rsc/WebLogo.png" alt="WW1 Logo">
        </div>
        <div class='nav-links'>
            <a href='sections.php'>Sections</a>
            <a href='about.php'>About</a>
        </div>
    </nav>

    <div class="wrapper">
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1>Log-in</h1>

            <div class="input-box">
                <input type="text" name="username" placeholder="Enter Your Username" required>
                <i class='bx bxs-user-circle'></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Enter Your Password" required>
                <i class='bx bxs-lock'></i>
            </div>

            <div class="rememberforgot">
                <label><input type="checkbox" name="remember"> Remember Me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="button">Log-in</button>

            <div class="Register-link">
                <p>Don't have an Account? <a href="register.php">Register Here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
