<?php 
require '../Global/admin_auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Bradford Admin Page</title>
    <link rel="icon" type="image/x-icon" href="../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="adminPage.css">
</head>
<body>
    <div class="navbar">
        <!-- Website logo -->
        <div class="logo">
            <img src="../Resource/Images/GroupLogo.png" alt="WW1 Group">
        </div>
        <!-- Page title -->
        <div class="title">
            Admin Page
        </div>
        <!-- Navigation buttons -->
        <div class="navbuttons">
            <button class="adminSection-button" onclick="window.location.href = 'AdminSection/AdminSection.php';">Section Page</button>
        </div>
    </div>
    <div class="container">
        <h1>Welcome to the Admin Page</h1>
        
        <div class="admin-options">
            <div class="option-card" onclick="window.location.href='RecordsManage/AdminUsers.php'">
                <h2>Manage Users</h2>
                <p>Add, edit, or remove user accounts</p>
                <button>Manage Users</button>
            </div>
            
            
            <div class="option-card" onclick="window.location.href='AdminManageDatabase.php'">
                <h2>Database Management</h2>
                <p>Manage the Bradford records database</p>
                <button>Manage Database</button>
            </div>
            
        </div>
    </div>
</body>
</html>