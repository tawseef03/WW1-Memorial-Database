<?php
session_start();
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
    <title>WW1 Honour Records - User View</title>
    <link rel="icon" type="image/x-icon" href="../../../rsc/WebLogo.png">
    <link rel="stylesheet" href="../TotalCss/database.css">
    <link rel="stylesheet" href="userHonour.css">
    <script src="userHonour.js" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Honour Records
        </div>
        <div class="navbuttons">
            <button onclick="window.location.href='<?php echo (isset($_SESSION['user_type']) && intval($_SESSION['user_type']) === 1) ? '../AdminSectionPage/AdminSection.php' : '../UserSectionPage/userSection.php'; ?>'">Back to Sections</button>
            <button type="button">About</button>
            <button type="button">Contact</button>
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
                        <label><input type="checkbox" name="fields" value="HonourID" checked> ID</label>
                        <label><input type="checkbox" name="fields" value="Surname" checked> Surname</label>
                        <label><input type="checkbox" name="fields" value="Forename" checked> Forename</label>
                        <label><input type="checkbox" name="fields" value="Address"> Address</label>
                        <label><input type="checkbox" name="fields" value="Electoral Ward"> Electoral Ward</label>
                        <label><input type="checkbox" name="fields" value="Town"> Town</label>
                        <label><input type="checkbox" name="fields" value="Rank"> Rank</label>
                        <label><input type="checkbox" name="fields" value="Regiment" checked> Regiment</label>
                        <label><input type="checkbox" name="fields" value="Unit"> Unit</label>
                        <label><input type="checkbox" name="fields" value="Company"> Company</label>
                        <label><input type="checkbox" name="fields" value="Age"> Age</label>
                        <label><input type="checkbox" name="fields" value="Service No"> Service No</label>
                        <label><input type="checkbox" name="fields" value="Other Regiment"> Other Regiment</label>
                        <label><input type="checkbox" name="fields" value="Other Unit"> Other Unit</label>
                        <label><input type="checkbox" name="fields" value="Other Service No."> Other Service No</label>
                        <label><input type="checkbox" name="fields" value="Medals" checked> Medals</label>
                        <label><input type="checkbox" name="fields" value="Enlistment Date"> Enlistment Date</label>
                        <label><input type="checkbox" name="fields" value="Discharge Date"> Discharge Date</label>
                        <label><input type="checkbox" name="fields" value="Death (in service) Date"> Death Date</label>
                        <label><input type="checkbox" name="fields" value="Misc Info Nroh"> Misc Info</label>
                        <label><input type="checkbox" name="fields" value="Cemetery/Memorial" checked> Cemetery</label>
                        <label><input type="checkbox" name="fields" value="Cemetery/Memorial Ref"> Cemetery Ref</label>
                        <label><input type="checkbox" name="fields" value="Cemetery/Memorial Country"> Cemetery Country</label>
                        <label><input type="checkbox" name="fields" value="Additional CWCG Info"> Additional Info</label>
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
                <h2>Honour Records Search</h2>
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
