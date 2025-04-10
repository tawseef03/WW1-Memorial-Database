<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../LoginPage/login.php");
    exit;
}

// 允许管理员和普通用户访问用户页面
if (!(intval($_SESSION['user_type']) === 0 || intval($_SESSION['user_type']) === 1)) {
    header("Location: ../LoginPage/login.php");
    exit;
}
?>
