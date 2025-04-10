<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查用户是否已登录且为管理员
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || intval($_SESSION['user_type']) !== 1) {
    header('Location: ../LoginPage/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Bradford Admin Page</title>
    <link rel="icon" type="image/x-icon" href="../../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminPage.css">
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            Admin Panel
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='../AdminSectionPage/AdminSection.php'">Back to Sections</button>
            <button type="button" onclick="location.href='../logout.php'">Logout</button>
        </div>
    </div>

    <div class="container">
        <h1>Welcome to the Admin Panel</h1>
        
        <div class="button-grid">
            <!-- Option Row -->
            <div class="button-row">
                <div class="admin-button">
                    <h3>Manage Users</h3>
                    <p>Add, edit, or remove user accounts</p>
                    <button class="action-btn" onclick="window.location.href='AdminUsers.php'">Access</button>
                </div>

                <div class="admin-button">
                    <h3>Database Management</h3>
                    <p>Manage the Bradford records database</p>
                    <button class="action-btn" onclick="window.location.href='../AdminManageDatabasePage/AdminManageDatabase.html'">Access</button>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-bar">
        © 2025 WW1 Memorial Database Management System - Authorized Personnel Only
    </div>
</body>
</html>
