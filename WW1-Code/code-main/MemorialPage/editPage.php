<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\WW1-Code\complete\MemorialPage\editPage.php -->
<?php
session_start();
// 检查用户是否登录
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../LoginPage/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit WW1 Database Records</title>
    <link rel="icon" type="image/x-icon" href="../../../rsc/WebLogo.png">
    <link rel="stylesheet" href="../TotalCss/database.css">
    <link rel="stylesheet" href="memorial.css">
    <script src="editPage.js" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            Edit WW1 Database Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='memorial.php'">Back to Records</button>
        </div>
    </div>

    <div class="container">
        <div class="content-panel">
            <div class="database-title">
                <h2>Edit Records</h2>
            </div>
            
            <div class="records-container">
                <div class="display">
                    <!-- 编辑表格内容将由JavaScript加载 -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>