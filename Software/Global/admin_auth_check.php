<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../LoginPage/login.php");
    exit;
}

if (!isset($_SESSION['user_type']) || intval($_SESSION['user_type']) !== 1) {
    header("Location: ../Guest/UserSection/userSection.php");
    exit;
}
?>