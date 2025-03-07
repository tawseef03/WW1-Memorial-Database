<?php
session_start();
require_once '../db_config.php';

$error_message = '';
$debug_info = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // SQL查询匹配ww1_db中的users表 / SQL query to match users table in ww1_db
        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("SQL preparation failed: " . $mysqli->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // 使用现有的密码验证逻辑 / Use existing password verification logic
        if ($user && $password === $user["Password"]) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user["User Type"];
            $_SESSION['user_id'] = $user["UserID"];
            
            // 根据用户类型重定向 / Redirect based on user type
            if (intval($user["User Type"]) === 1) {
                header("Location: ../AdminPage/adminpage.html");
            } else {
                header("Location: ../UserSectionPage/userSection.php");
            }
            exit;
        } else {
            $error_message = "Invalid username or password"; // 无效的用户名或密码
        }
        
    } catch (Exception $e) {
        $error_message = "System Error: " . $e->getMessage(); // 系统错误
        $debug_info .= "Error Info: " . $e->getMessage() . "\n"; // 错误信息
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
    <!-- 导航栏 / Navigation bar -->
    <div class="navbar">
        <!-- 网站标志 / Website logo -->
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <!-- 页面标题 / Page title -->
        <div class="title">
            Login
        </div>
        <!-- 导航按钮 / Navigation buttons -->
        <div class="navbuttons">
            <button type="button">Home</button>
            <button type="button">About</button>
            <button type="button">Contact</button>
        </div>
    </div>

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
            </div>

            <button type="submit" class="button">Log-in</button>
        </form>
    </div>
    <script>
        // 切换密码可见性 / Toggle password visibility
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
