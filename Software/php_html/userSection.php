<?php
require_once 'auth_check.php';
require 'db_connect.php';

function getCount($mysqli, $table) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $mysqli->query($sql);
    $count = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
    $result->free();
    return $count;
}

$township = getCount($mysqli, "township"); // Records count for Bradford and surrounding townships
$memorial = getCount($mysqli, "memorials"); // Records count for Names recorded on Bradford Memorials
$buried = getCount($mysqli, "buried"); // Records count for Buried in Bradford
$newspaper = getCount($mysqli, "newspapers"); // Records count for Newspaper references
$biography = getCount($mysqli, "biographyinfo"); // Records count for Biographies

$query = "SELECT filePath FROM about ORDER BY aboutID ASC";
$result = $mysqli->query($query);
if ($result) {
    // Fetch all file paths into an array
    $filePaths = [];
    while ($row = $result->fetch_assoc()) {
        $filePaths[] = $row['filePath'];
    }

    // Assign variables based on the sequence
    list($townshipabout, $memorialabout, $buriedabout, $newspaperabout, $biographyabout) = $filePaths;
    
    // Free result set
    $result->free();
} else {
    die("Error fetching file paths.");
}

$townshipabout .= '#toolbar=0';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set character encoding to UTF-8 -->
    <meta charset="UTF-8">
    <!-- Set viewport for proper scaling on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>Database</title>
    <!-- Set page favicon -->
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <!-- Link to external stylesheet -->
    <link rel="stylesheet" href="../css/userSection.css">
    <!-- Link to external JavaScript file -->
    <script src="../js/userSection.js"></script>
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <!-- Website logo -->
        <div class="logo">
            <img src="../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <!-- Page title -->
        <div class="title">
            Sections
        </div>
        <!-- Navigation buttons -->
        <div class="navbuttons">
            <button type="button" onclick="window.location.href='login.html'">Exit</button>
        </div>
    </div>

    <!-- Main content area -->
    <div class="view">
        <!-- Description section -->
        <div class="bgimg" id="bgimg">
            <div id="description" class="description">
                <div style="width:250px;">
                    <div style="height:330px;">
                        <h1>Bradford and Surrounding Townships</h1>
                    </div>
                    <button type="button" id="openModal1">About</button>
                </div>
                <div style="width:350px;">
                    <p>Source of information and knowledge relating to Bradford and its 
                    surrounding townships and their contributions to World War 1.</p>
                </div>
                <div style="width:250px;">
                    <div style="height:330px;">
                        <h1>Total records:</h1>
                        <p><?php echo $township; ?></p>
                    </div>
                    <button type="button" onclick="openPage(1)">Database</button>
                </div>
            </div>
        </div>
        <!-- Sections area -->
        <div class="sections">
            <div id="section1" class="section"></div>
            <div id="section2" class="section"></div>
            <div id="section3" class="section"></div>
            <div id="section4" class="section"></div>
            <div id="section5" class="section"></div>
        </div>
        <!-- Section names area -->
        <div class="names">
            <div class="name">Bradford and surrounding townships</div>
            <div class="name">Names recorded on Bradford Memorials</div>
            <div class="name">Buried in Bradford</div>
            <div class="name">Newspaper references</div>
            <div class="name">Biographies</div>
        </div>
    </div>

    <!--- Hidden section of the HTML --->
    <div class="hidden" id="sec1" style="display: none">
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Bradford and Surrounding Townships</h1>
            </div>
            <button type="button" onclick="openAbout(1);">About</button>
        </div>
        <div style="width:350px;">
            <p>Source of information and knowledge relating to Bradford and its 
            surrounding townships and their contributions to World War 1.</p>
        </div>
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Total records:</h1>
                <p><?php echo $township; ?></p>
            </div>
            <button type="button" onclick="openPage(1)">Database</button>
        </div>
    </div>
    <div class="hidden" id="sec2" style="display: none">
        <div class="title" style="width:250px;">
            <div style="height:330px;">
                <h1>Names Recorded on Bradford Memorials</h1>
            </div>
            <button type="button" onclick="openAbout(2);">About</button>
        </div>
        <div style="width:350px;">
            <p>Here you can search and find in our archives all those that are 
            remembered for their services in World War 1 on our Bradford Memorials in Bradford.</p>
        </div>
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Total records:</h1>
                <p><?php echo $memorial; ?></p>
            </div>
            <button type="button" onclick="openPage(2)">Database</button>
        </div>
    </div>
    <div class="hidden" id="sec3" style="display: none">
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Buried in Bradford</h1>
            </div>
            <button type="button" onclick="openAbout(3);">About</button>
        </div>
        <div style="width:350px;">
            <p>Here you can search and find in our archives all those who that 
            served and were related to Bradford in World War 1, that died and are buried in Bradford.</p>
        </div>
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Total records:</h1>
                <p><?php echo $buried; ?></p>
            </div>
            <button type="button" onclick="openPage(3)">Database</button>
        </div>
    </div>
    <div class="hidden" id="sec4" style="display: none">
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Newspaper references</h1>
            </div>
            <button type="button" onclick="openAbout(4);">About</button>
        </div>
        <div style="width:350px;">
            <p>A collection of project pages documenting the events, newspaper 
            articles and different perspectives related to what all occured with Bradford from World War 1.</p>
        </div>
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Total records:</h1>
                <p><?php echo $newspaper; ?></p>
            </div>
            <button type="button" onclick="openPage(4)">Database</button>
        </div>
    </div>
    <div class="hidden" id="sec5" style="display: none">
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Biographies</h1>
            </div>
            <button type="button" onclick="openAbout(5);">About</button>
        </div>
        <div style="width:350px;">
            <p>An archive of all the biographies of the men and women who served in World 
            War 1 from Bradford, each biography documenting his or her's experiences.</p>
        </div>
        <div style="width:250px;">
            <div style="height:330px;">
                <h1>Total records:</h1>
                <p><?php echo $biography; ?></p>
            </div>
            <button type="button" onclick="openPage(5)">Database</button>
        </div>
    </div>
</body>
</html>