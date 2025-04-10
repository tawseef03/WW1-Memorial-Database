<?php
// Include the database connection
require 'db_connect.php';

// Get search parameters and current page
$title = $_GET['title'] ?? '';
$date = $_GET['date'] ?? '';
$publisher = $_GET['publisher'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

$offset = ($page - 1) * $records_per_page;

// Build the query with search parameters
$query = "SELECT * FROM newspaperinfo WHERE 1=1";
$params = [];

if (!empty($title)) {
    $query .= " AND Title LIKE ?";
    $params[] = "%$title%";
}
if (!empty($date)) {
    $query .= " AND Date LIKE ?";
    $params[] = "%$date%";
}
if (!empty($publisher)) {
    $query .= " AND Publisher LIKE ?";
    $params[] = "%$publisher%";
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
$total_query = "SELECT COUNT(*) FROM newspaperinfo WHERE 1=1";
$total_params = [];

if (!empty($title)) {
    $total_query .= " AND Title LIKE ?";
    $total_params[] = "%$title%";
}
if (!empty($date)) {
    $total_query .= " AND Date LIKE ?";
    $total_params[] = "%$date%";
}
if (!empty($publisher)) {
    $total_query .= " AND Publisher LIKE ?";
    $total_params[] = "%$publisher%";
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
    <title>WW1 Newspaper Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Newspaper Records
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
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Enter title..." value="<?php echo htmlspecialchars($title); ?>">
                </div>
                
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="text" id="date" name="date" placeholder="Enter date..." value="<?php echo htmlspecialchars($date); ?>">
                </div>
                
                <div class="form-group">
                    <label for="publisher">Publisher:</label>
                    <input type="text" id="publisher" name="publisher" placeholder="Enter publisher..." value="<?php echo htmlspecialchars($publisher); ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="button" id="searchButton">Search</button>
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminNewspaper.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Newspaper Records</h2>
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
                            <th>Title</th>
                            <th>Date</th>
                            <th>Publisher</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Publisher']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['Link']) . "' target='_blank'>View</a></td>";
                            echo "<td class='action-buttons'>
                                <button class='edit-btn' data-id='" . $row['NewspaperID'] . "' 
                                data-title='" . htmlspecialchars($row['Title']) . "' 
                                data-date='" . htmlspecialchars($row['Date']) . "' 
                                data-publisher='" . htmlspecialchars($row['Publisher']) . "' 
                                data-link='" . htmlspecialchars($row['Link']) . "'>Edit</button>
                                <button class='delete-btn' data-id='" . $row['NewspaperID'] . "'>Delete</button>
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
                        <a href="?title=<?php echo urlencode($title); ?>&date=<?php echo urlencode($date); ?>&publisher=<?php echo urlencode($publisher); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    <?php endif; ?>
                    
                    <span id="pageInfo">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?title=<?php echo urlencode($title); ?>&date=<?php echo urlencode($date); ?>&publisher=<?php echo urlencode($publisher); ?>&page=<?php echo $page + 1; ?>">Next</a>
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