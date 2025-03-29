<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\php_html\township.php -->
<?php
// Include the database connection
require 'db_connect.php'; // This will include your database connection from db_connect.php

// Get search parameters and current page
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1; // Get current page, default to 1 if not set
$records_per_page = 1; // Number of records per page (1 in this case)

$offset = ($page - 1) * $records_per_page; // Calculate the offset

// Build the query with search parameters
$query = "SELECT * FROM township WHERE 1=1";
$params = [];

if (!empty($surname)) {
    $query .= " AND Surname LIKE ?";
    $params[] = "%$surname%";
}
if (!empty($forename)) {
    $query .= " AND Forename LIKE ?";
    $params[] = "%$forename%";
}
if (!empty($regiment)) {
    $query .= " AND Regiment LIKE ?";
    $params[] = "%$regiment%";
}

// Apply the limit and offset for pagination
$query .= " LIMIT ? OFFSET ?";
$params[] = $records_per_page; // Limit to 1 record per page
$params[] = $offset; // Offset for the page

// Prepare and execute the query
$stmt = $mysqli->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params); // Bind parameters dynamically
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the total number of records for pagination calculation
$total_query = "SELECT COUNT(*) FROM township WHERE 1=1";
$total_stmt = $mysqli->prepare($total_query);
$total_stmt->execute();
$total_results = $total_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_results / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Database Records</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="../css/database.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Database Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='userSection.html'">Back to Sections</button>
        </div>
    </div>

    <div class="container">
        <div class="search-panel">
            <h3>Search Criteria</h3>
            <form id="searchForm" method="get">
                <div class="form-group">
                    <label for="surname">Surname:</label>
                    <input type="text" id="surname" name="surname" placeholder="Enter surname..." value="<?php echo htmlspecialchars($surname); ?>">
                </div>
                
                <div class="form-group">
                    <label for="forename">Forename:</label>
                    <input type="text" id="forename" name="forename" placeholder="Enter forename..." value="<?php echo htmlspecialchars($forename); ?>">
                </div>
                
                <div class="form-group">
                    <label for="regiment">Regiment:</label>
                    <input type="text" id="regiment" name="regiment" placeholder="Enter regiment..." value="<?php echo htmlspecialchars($regiment); ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="button" id="searchButton">Search</button>
                    <button type="button" id="resetButton" onclick="window.location.href = 'township.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Bradford and Surrounding Townships</h2>
            </div>
            
            <div class="records-container">
                <h3 id="resultsHeading">Records Display</h3>
                
                <div class="display">
                    <?php
                    if (empty($results)) {
                        echo "<p>No records found.</p>";
                    } else {
                        foreach ($results as $row) {
                            echo "<div class='record'>";
                            // 添加编辑按钮
                            echo "<button class='edit-button' onclick=\"location.href='editTownship.php?id=" . htmlspecialchars($row['id']) . "'\">编辑</button>";
                            echo "<p><strong>Surname:</strong> " . htmlspecialchars($row['Surname']) . "</p>";
                            echo "<p><strong>Forename:</strong> " . htmlspecialchars($row['Forename']) . "</p>";
                            echo "<p><strong>Regiment:</strong> " . htmlspecialchars($row['Regiment']) . "</p>";
                            echo "<p><strong>Unit:</strong> " . htmlspecialchars($row['Unit']) . "</p>";
                            echo "<p><strong>Memorial:</strong> " . htmlspecialchars($row['Memorial']) . "</p>";
                            echo "<p><strong>Memorial Info:</strong> " . htmlspecialchars($row['Memorial Info']) . "</p>";
                            echo "<p><strong>Postcode:</strong> " . htmlspecialchars($row['Memorial Postcode']) . "</p>";
                            echo "<p><strong>District:</strong> " . htmlspecialchars($row['District']) . "</p>";
                            echo "<p><strong>Photo:</strong> " . ($row['Photo available'] ? 'Yes' : 'No') . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
                
                <!-- Pagination buttons -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    <?php endif; ?>
                    
                    <span id="pageInfo">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page + 1; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        // When the search button is clicked, trigger the form submission
        document.getElementById("searchButton").onclick = function() {
            document.getElementById("searchForm").submit(); // Submit the form to trigger PHP search
        };
    </script>
</body>
</html>