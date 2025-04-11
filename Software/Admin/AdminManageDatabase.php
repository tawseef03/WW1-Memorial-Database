<?php
require_once '../Global/admin_auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Management - WW1 Bradford</title>
    <link rel="icon" type="image/x-icon" href="../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="adminPage.css">
    <link rel="stylesheet" href="databaseManagement.css">
</head>
<body>  
    <!-- Navigation bar -->
    <div class="navbar">
        <!-- Website logo -->
        <div class="logo">
            <img src="../Resource/Images/GroupLogo.png" alt="WW1 Group">
        </div>
        <!-- Page title -->
        <div class="title">
            Admin Database
        </div>
        <!-- Navigation buttons -->
        <div class="navbuttons">
            <button type="button" onclick="window.location.href='adminpage.php'">Back</button>
        </div>
    </div>

    <div class="container">
        <h1>Select Database to Manage</h1>
        
        <div class="button-grid">
            <!-- Top Row -->
            <div class="button-row top-row">
                <button class="database-button" onclick="window.location.href='RecordsManage/AdminTownship2.php'">
                    <h3>Bradford and surrounding townships</h3>
                </button>

                <button class="database-button" onclick="window.location.href='RecordsManage/AdminMemorial.php'">
                    <h3>Names recorded on Bradford Memorials</h3>
                </button>

                <button class="database-button" onclick="window.location.href='RecordsManage/AdminBurials.php'">
                    <h3>Buried in Bradford</h3>
                </button>
            </div>

            <!-- Bottom Row -->
            <div class="button-row bottom-row">
                <button class="database-button" onclick="window.location.href='RecordsManage/AdminNewspaper.php'">
                    <h3>Newspaper references</h3>
                </button>

                <button class="database-button" onclick="window.location.href='RecordsManage/AdminBiographies.php'">
                    <h3>Biographies</h3>
                </button>
            </div>
        </div>
    </div>
</body>
</html>