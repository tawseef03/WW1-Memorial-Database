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
    <title>WW1 Memorial Records</title>
    <link rel="icon" type="image/x-icon" href="../../../rsc/WebLogo.png">
    <link rel="stylesheet" href="../TotalCss/database.css">
    <link rel="stylesheet" href="memorial.css">
    <script src="memorial.js" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            Memorial Records
        </div>
        <div class="navbuttons">
            <button onclick="window.location.href='../UserSectionPage/userSection.php'">Back to Sections</button>
            <button onclick="window.location.href='../LoginPage/login.php'">Logout</button>
        </div>
    </div>

    <div class="container">
        <div class="search-panel">
            <h3>Search Criteria</h3>
            <form id="searchForm">
                <div class="form-group">
                    <label for="surname">Surname:</label>
                    <input type="text" id="surname" name="surname" placeholder="Enter surname...">
                </div>
                
                <div class="form-group">
                    <label for="forename">Forename:</label>
                    <input type="text" id="forename" name="forename" placeholder="Enter forename...">
                </div>
                
                <div class="form-group">
                    <label for="regiment">Regiment:</label>
                    <input type="text" id="regiment" name="regiment" placeholder="Enter regiment...">
                </div>
                
                <div class="field-selector">
                    <h4>Display Fields</h4>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="fields" value="MemorialID" checked> ID</label>
                        <label><input type="checkbox" name="fields" value="Surname" checked> Surname</label>
                        <label><input type="checkbox" name="fields" value="Forename" checked> Forename</label>
                        <label><input type="checkbox" name="fields" value="Regiment" checked> Regiment</label>
                        <label><input type="checkbox" name="fields" value="Unit"> Unit</label>
                        <label><input type="checkbox" name="fields" value="Cemetery"> Cemetery</label>
                        <label><input type="checkbox" name="fields" value="GraveRef"> Grave Ref</label>
                        <label><input type="checkbox" name="fields" value="Country"> Country</label>
                        <label><input type="checkbox" name="fields" value="Memorial" checked> Memorial</label>
                        <label><input type="checkbox" name="fields" value="Location"> Location</label>
                        <label><input type="checkbox" name="fields" value="Info"> Info</label>
                        <label><input type="checkbox" name="fields" value="Postcode"> Postcode</label>
                        <label><input type="checkbox" name="fields" value="District"> District</label>
                        <label><input type="checkbox" name="fields" value="Photo"> Photo</label>
                    </div>
                    <div class="field-selector-buttons">
                        <button type="button" id="selectAllFields">Select All</button>
                        <button type="button" id="deselectAllFields">Deselect All</button>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="button" id="searchButton">Search</button>
                    <button type="button" id="resetButton">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Names on Bradford Memorials</h2>
            </div>
            
            <div class="records-container">
                <h3 id="resultsHeading">Records Display</h3>
                
                <div id="resultsInfo" class="results-info">
                    Showing all records. Use the search form to filter results.
                </div>
                
                <div class="display">
                    <!-- Results will be loaded here by JavaScript -->
                </div>
                
                <div class="pagination">
                    <button id="prevPage">Previous</button>
                    <span id="pageInfo">Page 1 of 1</span>
                    <button id="nextPage">Next</button>
                </div>
                <div class="database-stats">
                    <!-- Database stats will be displayed here -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
