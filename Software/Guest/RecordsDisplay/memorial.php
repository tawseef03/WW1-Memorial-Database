<?php
require_once '../../Global/auth_check.php';
require '../../Global/db_connect.php';

// Get search parameters and current page
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1;

$offset = ($page - 1);

// Build the query with search parameters
$query = "SELECT * FROM memorials WHERE 1=1";
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
$query .= " LIMIT 1 OFFSET ?";
$params[] = $offset;

// Prepare and execute the query
$stmt = $mysqli->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the total number of records for pagination calculation
$total_query = "SELECT COUNT(*) AS total FROM memorials WHERE 1=1";
$total_params = [];
$param_types = "";

if (!empty($surname)) {
    $total_query .= " AND Surname LIKE ?";
    $total_params[] = "%$surname%";
    $param_types .= "s";
}
if (!empty($forename)) {
    $total_query .= " AND Forename LIKE ?";
    $total_params[] = "%$forename%";
    $param_types .= "s";
}
if (!empty($regiment)) {
    $total_query .= " AND Regiment LIKE ?";
    $total_params[] = "%$regiment%";
    $param_types .= "s";
}

$total_stmt = $mysqli->prepare($total_query);
if (!empty($total_params)) {
    $total_stmt->bind_param($param_types, ...$total_params);
}
$total_stmt->execute();
$total_pages = $total_stmt->get_result()->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Database Records</title>
    <link rel="icon" type="image/x-icon" href="../../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="database.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../Resource/Images/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Database Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='../UserSection/userSection.php'">Back to Sections</button>
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'memorial.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Names on Bradford Memorials</h2>
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
                            echo "<div class='col1'>";
                            echo "<p><strong>Memorial ID:</strong> " . htmlspecialchars($row['MemorialID']) . "</p>";
                            echo "<p><strong>Surname:</strong> " . htmlspecialchars($row['Surname']) . "</p>";
                            echo "<p><strong>Forename:</strong> " . htmlspecialchars($row['Forename']) . "</p>";
                            echo "<p><strong>Regiment:</strong> " . htmlspecialchars($row['Regiment']) . "</p>";
                            echo "<p><strong>Unit:</strong> " . htmlspecialchars($row['Unit']) . "</p>";
                            echo "<p><strong>Cemetery/Memorial:</strong> " . htmlspecialchars($row['Cemetery/Memorial']) . "</p>";
                            echo "<p><strong>Cemetery/Grave Ref.:</strong> " . htmlspecialchars($row['Cemetery/Grave Ref.']) . "</p>";
                            echo "<p><strong>Cemetery/Memorial Country:</strong> " . htmlspecialchars($row['Cemetery / Memorial Country']) . "</p>";
                            echo "</div>";
                            echo "<div class='col2'>";
                            echo "<p><strong>Memorial:</strong> " . htmlspecialchars($row['Memorial']) . "</p>";
                            echo "<p><strong>Memorial Location:</strong> " . htmlspecialchars($row['Memorial Location']) . "</p>";
                            echo "<p><strong>Memorial Info:</strong> " . htmlspecialchars($row['Memorial Info']) . "</p>";
                            echo "<p><strong>Postcode:</strong> " . htmlspecialchars($row['Memorial Postcode']) . "</p>";
                            echo "<p><strong>District:</strong> " . htmlspecialchars($row['District']) . "</p>";
                            echo "<p><strong>Photo:</strong> " . ($row['Photo available'] ? 'Yes' : 'No') . "</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
                
                <!-- Pagination buttons -->
                <div class="pagination">
                    <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page - 1; ?>" 
                        class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        Prev
                    </a>

                    <span id="pageInfo">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

                    <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page + 1; ?>" 
                    class="<?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        Next
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // When the search button is clicked, trigger the form submission
        document.getElementById("searchButton").onclick = function() {
            document.getElementById("searchForm").submit();
        };
    </script>
</body>
</html>
