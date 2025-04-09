<?php
session_start();
// Include the database connection
require '../db_config.php';

// Create database connection
$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get search parameters and current page
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

$offset = ($page - 1) * $records_per_page;

// Build the query with search parameters
$query = "SELECT * FROM biographyinfo WHERE 1=1";
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

$query .= " LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;

// 执行查询
$stmt = $mysqli->prepare($query);
$bind_types = str_repeat('s', count($params));
$stmt->bind_param($bind_types, ...$params);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 获取总记录数用于分页
$total_query = "SELECT COUNT(*) FROM biographyinfo WHERE 1=1";
$total_params = [];

if (!empty($surname)) {
    $total_query .= " AND Surname LIKE ?";
    $total_params[] = "%$surname%";
}
if (!empty($forename)) {
    $total_query .= " AND Forename LIKE ?";
    $total_params[] = "%$forename%";
}
if (!empty($regiment)) {
    $total_query .= " AND Regiment LIKE ?";
    $total_params[] = "%$regiment%";
}

$total_stmt = $mysqli->prepare($total_query);
if (!empty($total_params)) {
    $total_bind_types = str_repeat('s', count($total_params));
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
    <title>WW1 Biographies Records</title>
    <link rel="icon" type="image/x-icon" href="../../rsc/WebLogo.png">
    <link rel="stylesheet" href="UserBiographies.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Biographies Records
        </div>
        <div class="navbuttons">
            <button onclick="window.location.href='<?php echo (isset($_SESSION['user_type']) && intval($_SESSION['user_type']) === 1) ? '../AdminSectionPage/AdminSection.php' : '../UserSectionPage/userSection.php'; ?>'">Back to Sections</button>
            <button type="button">About</button>
            <button type="button">Contact</button>
        </div>
    </div>

    <div class="container">
        <div class="search-panel">
            <h3>Search Records</h3>
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
                    <button type="submit">Search</button>
                    <button type="button" onclick="window.location.href='biographies.php'">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="results-container">
            <h2>Biography Records</h2>
            <?php if (empty($results)): ?>
                <p class="no-results">No records found.</p>
            <?php else: ?>
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Surname</th>
                            <th>Forename</th>
                            <th>Regiment</th>
                            <th>Service No</th>
                            <th>Biography</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['BiographyID']); ?></td>
                                <td><?php echo htmlspecialchars($row['Surname']); ?></td>
                                <td><?php echo htmlspecialchars($row['Forename']); ?></td>
                                <td><?php echo htmlspecialchars($row['Regiment']); ?></td>
                                <td><?php echo htmlspecialchars($row['Service No']); ?></td>
                                <td data-full-text="<?php echo htmlspecialchars($row['Biography']); ?>">
                                    <?php echo htmlspecialchars($row['Biography']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
                    <?php endif; ?>
                    
                    <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&forename=<?php echo urlencode($forename); ?>&regiment=<?php echo urlencode($regiment); ?>&page=<?php echo $page + 1; ?>" class="page-link">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
