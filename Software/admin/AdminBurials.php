<?php
// Include the database connection
require 'db_connect.php';

// Get search parameters and current page
$cemetery = $_GET['cemetery'] ?? '';
$location = $_GET['location'] ?? '';
$grave_number = $_GET['grave_number'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

$offset = ($page - 1) * $records_per_page;

// Build the query with search parameters
$query = "SELECT * FROM burialinfo WHERE 1=1";
$params = [];

if (!empty($cemetery)) {
    $query .= " AND Cemetery LIKE ?";
    $params[] = "%$cemetery%";
}
if (!empty($location)) {
    $query .= " AND Location LIKE ?";
    $params[] = "%$location%";
}
if (!empty($grave_number)) {
    $query .= " AND GraveNumber LIKE ?";
    $params[] = "%$grave_number%";
}

// Apply the limit and offset for pagination
$query .= " LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;

// Prepare and execute the query
$stmt = $mysqli->prepare($query);

// Dynamically create the bind_param string
$bind_types = str_repeat('s', count($params));
$stmt->bind_param($bind_types, ...$params);

$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the total number of records for pagination calculation
$total_query = "SELECT COUNT(*) FROM burialinfo WHERE 1=1";
$total_params = [];

if (!empty($cemetery)) {
    $total_query .= " AND Cemetery LIKE ?";
    $total_params[] = "%$cemetery%";
}
if (!empty($location)) {
    $total_query .= " AND Location LIKE ?";
    $total_params[] = "%$location%";
}
if (!empty($grave_number)) {
    $total_query .= " AND GraveNumber LIKE ?";
    $total_params[] = "%$grave_number%";
}

$total_stmt = $mysqli->prepare($total_query);

// Dynamically create the bind_param string for total query
$total_bind_types = str_repeat('s', count($total_params));
if (!empty($total_params)) {
    $total_stmt->bind_param($total_bind_types, ...$total_params);
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
    <title>WW1 Burial Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Burial Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='AdminSection2.html'">Back to Sections</button>
            <button type="button" onclick="location.href='AdminManageDatabase.html'">Admin Panel</button>
        </div>
    </div>

    <div class="container">
        <div class="search-panel">
            <h3>Search Criteria</h3>
            <form id="searchForm" method="get">
                <div class="form-group">
                    <label for="cemetery">Cemetery:</label>
                    <input type="text" id="cemetery" name="cemetery" placeholder="Enter cemetery..." value="<?php echo htmlspecialchars($cemetery); ?>">
                </div>
                
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" placeholder="Enter location..." value="<?php echo htmlspecialchars($location); ?>">
                </div>
                
                <div class="form-group">
                    <label for="grave_number">Grave Number:</label>
                    <input type="text" id="grave_number" name="grave_number" placeholder="Enter grave number..." value="<?php echo htmlspecialchars($grave_number); ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="button" id="searchButton">Search</button>
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminBurials.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Burial Records</h2>
            </div>
            
            <div class="records-container">
                <div class="records-header">
                    <h3 id="resultsHeading">Records Display</h3>
                    <button id="createRecordBtn" class="create-record-btn">Create New Record</button>
                </div>
                
                <div class="display">
                    <?php
                    if (empty($results)) {
                        echo "<p>No records found.</p>";
                    } else {
                        echo "<table class='records-table'>";
                        echo "<thead><tr>
                            <th>Cemetery</th>
                            <th>Location</th>
                            <th>Grave Number</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Cemetery']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Location']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['GraveNumber']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['Details']) . "' target='_blank'>View</a></td>";
                            echo "<td class='action-buttons'>
                                <button class='edit-btn' data-id='" . $row['BurialID'] . "' 
                                data-cemetery='" . htmlspecialchars($row['Cemetery']) . "' 
                                data-location='" . htmlspecialchars($row['Location']) . "' 
                                data-grave-number='" . htmlspecialchars($row['GraveNumber']) . "' 
                                data-details='" . htmlspecialchars($row['Details']) . "'>Edit</button>
                                <button class='delete-btn' data-id='" . $row['BurialID'] . "'>Delete</button>
                            </td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody></table>";
                    }
                    ?>
                </div>
                
                <!-- Pagination buttons -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?cemetery=<?php echo urlencode($cemetery); ?>&location=<?php echo urlencode($location); ?>&grave_number=<?php echo urlencode($grave_number); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    <?php endif; ?>
                    
                    <span id="pageInfo">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?cemetery=<?php echo urlencode($cemetery); ?>&location=<?php echo urlencode($location); ?>&grave_number=<?php echo urlencode($grave_number); ?>&page=<?php echo $page + 1; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert success">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Create, Edit, Delete Modals (similar to AdminBiographies.php) -->
    <!-- Add JavaScript for modal handling -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Similar modal handling as in AdminBiographies.php
    });
    </script>
</body>
</html>