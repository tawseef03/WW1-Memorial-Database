<?php
session_start();
require_once 'db_config.php';

try {
    // 清除remember token
    if(isset($_SESSION['user_id'])) {
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        if ($mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $mysqli->connect_error);
        }
        $sql = "UPDATE users SET remember_token = NULL WHERE UserID = ?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception('Statement preparation failed');
        }
        $stmt->bind_param("i", $_SESSION['user_id']);
        if (!$stmt->execute()) {
            throw new Exception('Execute failed');
        }
        $mysqli->close();
    }

    // 清除cookie
    setcookie('remember_token', '', time() - 3600, '/');
    setcookie('user_name', '', time() - 3600, '/');

    // 清除session
    session_unset();
    session_destroy();
    
    // 根据参数决定重定向位置
    if (isset($_GET['redirect']) && $_GET['redirect'] === 'welcome') {
        header("Location: /WW1-Memorial-Database-总/code-main/WelcomePage/welcome.html");
    } else {
        header("Location: /WW1-Memorial-Database-总/code-main/LoginPage/login.php");
    }
    exit;
} catch (Exception $e) {
    error_log("Logout error: " . $e->getMessage());
    header("Location: /WW1-Memorial-Database-总/code-main/LoginPage/login.php?error=1");
    exit;
}
?>
