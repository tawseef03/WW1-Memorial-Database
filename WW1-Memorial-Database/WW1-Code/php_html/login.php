<?php
session_start();
require_once 'db_config.php';

$error_message = '';
$debug_info = '';  // 添加调试信息变量

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($mysqli->connect_error) {
            throw new Exception("数据库连接失败: " . $mysqli->connect_error);
        }
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        // 检查SQL准备语句是否成功
        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("准备语句失败: " . $mysqli->error);
        }
        
        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            throw new Exception("执行查询失败: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // 添加调试信息
        $debug_info .= "输入的用户名: " . $username . "<br>";
        $debug_info .= "输入的密码: " . $password . "<br>";
        if ($user) {
            $debug_info .= "数据库中的密码: " . $user["Password"] . "<br>";
            
            // 修改验证逻辑：先检查是否是哈希密码，如果不是则直接比较
            $passwordValid = password_verify($password, $user["Password"]) || 
                           ($password === $user["Password"]); // 添加明文密码比较
            
            $debug_info .= "密码验证结果: " . ($passwordValid ? "成功" : "失败") . "<br>";
        } else {
            $debug_info .= "未找到用户<br>";
        }
        
        if ($user && ($password === $user["Password"] || password_verify($password, $user["Password"]))) {
            // 设置会话变量
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user["User Type"];
            $_SESSION['user_id'] = $user["UserID"];
            
            // 添加用户类型调试信息
            $debug_info .= "用户类型: " . $user["User Type"] . "<br>";
            
            // 根据用户类型跳转到不同页面
            if (intval($user["User Type"]) === 1) {  // 管理员
                header("Location: admindashboard.html");
            } else {  // 普通用户
                header("Location: userSection.html");
            }
            exit;
        } else {
            $error_message = "用户名或密码错误";
        }
        
    } catch (Exception $e) {
        $error_message = "系统错误: " . $e->getMessage();
        error_log("Login error: " . $e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }
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
    <style>
        .input-box {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
    </style>
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
        
        <?php if (!empty($debug_info)): ?>
            <div style="background-color: #f0f0f0; padding: 10px; margin-bottom: 10px;">
                <pre><?php echo $debug_info; ?></pre>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1>Log-in</h1>

            <div class="input-box">
                <input type="text" name="username" placeholder="Enter Your Username" required>
                <i class='bx bxs-user-circle'></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                <i class='bx bx-hide toggle-password' onclick="togglePassword()"></i>
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
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        }
    </script>
</body>
</html>
