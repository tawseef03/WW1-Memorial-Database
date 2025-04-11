<?php
session_start();
require_once '../Global/db_connect.php';

$error_message = '';
$debug_info = '';

if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}
$lockout_duration = 5 * 60;

if ($_SESSION['failed_attempts'] >= 5 && time() < $_SESSION['lockout_time']) {
    $remaining_time = $_SESSION['lockout_time'] - time();
    $error_message = "Too many failed login attempts. Please try again in " . ceil($remaining_time / 60) . " minutes.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }

        // SQL query to match users table in ww1_db
        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("SQL preparation failed: " . $mysqli->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Use existing password verification logic
        if ($user && password_verify($password, $user["Password"])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user["User Type"];
            $_SESSION['user_id'] = $user["UserID"];

            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lockout_time'] = 0;
            
            // Redirect based on user type
            if (intval($user["User Type"]) === 1) {
                header("Location: ../Admin/AdminSection/AdminSection.php");
            } else {
                header("Location: ../Guest/UserSection/userSection.php");
            }
            exit;
        } else {
            $_SESSION['failed_attempts']++;
            if ($_SESSION['failed_attempts'] >= 5) {
                $_SESSION['lockout_time'] = time() + $lockout_duration;
                $error_message = "Too many failed login attempts. Please try again in 5 minutes.";
            } else {
                $remaining_attempts = 5 - $_SESSION['failed_attempts'];
                $error_message = "Invalid username or password. You have $remaining_attempts attempt(s) remaining.";
            }
        }
        
    } catch (Exception $e) {
        $error_message = "System Error: " . $e->getMessage();
        $debug_info .= "Error Info: " . $e->getMessage() . "\n";
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
    <link rel="icon" type="image/x-icon" href="../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="navbar">
        <!-- Website logo -->
        <div class="logo">
            <img src="../Resource/Images/GroupLogo.png" alt="WW1 Group">
        </div>
        <!-- Page title -->
        <div class="title">
            Login
        </div>
        <!-- Navigation buttons -->
        <div class="navbuttons">
            <button type="button" onclick="window.location.href='../php_html/welcomepage.html'">Exit</button>
        </div>
    </div>

    <div class="wrapper">
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
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
    <script>
        // Toggle password visibility
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