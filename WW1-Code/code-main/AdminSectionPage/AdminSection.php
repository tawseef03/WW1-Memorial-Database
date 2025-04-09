<?php
session_start();
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
    <title>Admin Section</title>
    <link rel="icon" type="image/x-icon" href="../../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminSection.css">
    <script src="AdminSection.js" defer></script>
</head>
<body>
    <div class="page-container">
        <div class="content-wrap">
            <div class="navbar">
                <div class="logo">
                    <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
                </div>
                <div class="title">
                    WW1 Memorial Sections
                </div>
                <div class="navbuttons">
                    <button onclick="window.location.href='../AdminPage/AdminPage.php'">Manage</button>
                    <button onclick="window.location.href='../logout.php'">Logout</button>
                </div>
            </div>

            <div class="view" style="background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url(../../rsc/poppyfield.png);">
                <div class="description">
                    <div id="description">Townships and Surrounding Areas</div>
                </div>
                <div class="sections">
                    <div id="section1" class="section" data-description="Townships and Surrounding Areas"></div>
                    <div id="section2" class="section" data-description="Memorial Information"></div>
                    <div id="section3" class="section" data-description="Burial Records"></div>
                    <div id="section4" class="section" data-description="Newspaper Archives"></div>
                    <div id="section5" class="section" data-description="Biographical Data"></div>
                </div>
                <div class="names">
                    <div class="name">Townships</div>
                    <div class="name">Memorials</div>
                    <div class="name">Burials</div>
                    <div class="name">Newspapers</div>
                    <div class="name">Biographies</div>
                </div>
            </div>
        </div>

        <div class="copyright-bar">
            © 2025 WW1 Memorial Database Management System - Authorized Personnel Only
        </div>
    </div>

    <div class="hidden" id="sec1" style="display: none">
        <h3>Bradford and Surrounding Townships</h3>
        <p>Information about townships in the Bradford area.</p>
    </div>
    <div class="hidden" id="sec2" style="display: none">
        <h3>Memorial Information</h3>
        <p>Details of memorials and monuments.</p>
    </div>
    <div class="hidden" id="sec3" style="display: none">
        <h3>Burial Records</h3>
        <p>Cemetery and burial information.</p>
    </div>
    <div class="hidden" id="sec4" style="display: none">
        <h3>Newspaper Archives</h3>
        <p>Historical newspaper records and articles.</p>
    </div>
    <div class="hidden" id="sec5" style="display: none">
        <h3>Biographical Data</h3>
        <p>Personal histories and biographical information.</p>
    </div>
</body>
</html>
