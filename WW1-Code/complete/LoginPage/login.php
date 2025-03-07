<?php
session_start();
require_once '../db_config.php';

$error_message = '';
$debug_info = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($mysqli->connect_error) {
            throw new Exception('数据库连接失败: ' . $mysqli->connect_error);
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // 修改SQL查询以匹配ww1_db中的users表结构
        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("SQL准备失败: " . $mysqli->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // 使用现有的密码验证逻辑
        if ($user && $password === $user["Password"]) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user["User Type"];
            $_SESSION['user_id'] = $user["UserID"];
            
            if (intval($user["User Type"]) === 1) {
                header("Location: ../AdminPage/adminpage.html");
            } else {
                header("Location: ../UserSectionPage/userSection.php");
            }
            exit;
        } else {
            $error_message = "用户名或密码错误";
        }
        
    } catch (Exception $e) {
        $error_message = "系统错误: " . $e->getMessage();
        $debug_info .= "错误信息: " . $e->getMessage() . "\n";
    } finally {
        if (isset($stmt) && $stmt !== false) {
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
    <link rel="stylesheet" href="login-styleV2.css">
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
