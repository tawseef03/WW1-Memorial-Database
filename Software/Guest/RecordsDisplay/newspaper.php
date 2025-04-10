<?php
require_once 'auth_check.php';
require 'db_connect.php';

// Get search parameters and current page
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1;

$records_per_page = 10; // Number of records per page
$offset = ($page - 1) * $records_per_page;

// Build the query with search parameters
$query = "SELECT * FROM newspapers WHERE 1=1";
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
$params[] = $records_per_page;
$params[] = $offset;

// Prepare and execute the query
$stmt = $mysqli->prepare($query);
$stmt->bind_param(str_repeat('s', count($params) - 2) . "ii", ...$params);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the total number of records for pagination calculation
$total_query = "SELECT COUNT(*) AS total FROM newspapers WHERE 1=1";
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
$total_results = $total_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_results / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Newspaper References</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="../css/database.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Newspaper References
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='userSection.php'">Back to Sections</button>
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'newspaper.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Newspaper References</h2>
            </div>
            
            <div class="records-container">
                <h3 id="resultsHeading">Records Display</h3>
                
                <div class="display">
                    <?php
                    if (empty($results)) {
                        echo "<p>No records found.</p>";
                    } else {
                        echo "<table class='records-table'>";
                        echo "<thead><tr>
                            <th>Surname</th>
                            <th>Forename</th>
                            <th>Rank</th>
                            <th>Address</th>
                            <th>Regiment</th>
                            <th>Unit</th>
                            <th>Article Description</th>
                            <th>Newspaper Name</th>
                            <th>Paper Date</th>
                            <th>Page/Col</th>
                            <th>Photo incl.</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";

                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Rank']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Unit']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Article Description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Newspaper Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Paper Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Page/Col']) . "</td>";
                            echo "<td>" . ($row['Photo incl.'] ? 'Yes' : 'No') . "</td>";
                            echo "<td class='action-buttons'>
                                <form action='process_newspaper.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='action' value='edit'>
                                    <input type='hidden' name='record_id' value='" . htmlspecialchars($row['NewspaperID']) . "'>
                                    <button type='submit'>Edit</button>
                                </form>
                                <form action='process_newspaper.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='record_id' value='" . htmlspecialchars($row['NewspaperID']) . "'>
                                    <button type='submit' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</button>
                                </form>
                            </td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
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
