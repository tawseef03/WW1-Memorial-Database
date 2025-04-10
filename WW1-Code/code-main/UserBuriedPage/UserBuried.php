<?php
session_start();
require '../db_config.php';

$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$surname = $_GET['surname'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$cemetery = $_GET['cemetery'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

$offset = ($page - 1) * $records_per_page;

$query = "SELECT BuriedID, Surname, Forename, Age, `Date of Death`, Rank, `Service No`, 
          Regiment, Unit, Cemetary, `Grave Ref`, Info FROM buried WHERE 1=1";
$params = [];

if (!empty($surname)) {
    $query .= " AND Surname LIKE ?";
    $params[] = "%$surname%";
}
if (!empty($regiment)) {
    $query .= " AND Regiment LIKE ?";
    $params[] = "%$regiment%";
}
if (!empty($cemetery)) {
    $query .= " AND Cemetary LIKE ?";  
    $params[] = "%$cemetery%";
}

$query .= " LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total_query = "SELECT COUNT(*) FROM buried WHERE 1=1";
$total_params = [];

if (!empty($surname)) {
    $total_query .= " AND Surname LIKE ?";
    $total_params[] = "%$surname%";
}
if (!empty($regiment)) {
    $total_query .= " AND Regiment LIKE ?";
    $total_params[] = "%$regiment%";
}
if (!empty($cemetery)) {
    $total_query .= " AND Cemetary LIKE ?"; // Note: field name is "Cemetary" in the SQL definition
    $total_params[] = "%$cemetery%";
}

$total_stmt = $mysqli->prepare($total_query);
if (!$total_stmt) {
    die("Prepare failed: " . $mysqli->error);
}
if (!empty($total_params)) {
    $total_stmt->bind_param(str_repeat('s', count($total_params)), ...$total_params);
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
    <title>WW1 Buried Records</title>
    <link rel="icon" type="image/x-icon" href="../../rsc/WebLogo.png">
    <link rel="stylesheet" href="UserBuried.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Buried Records
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
                    <label for="regiment">Regiment:</label>
                    <input type="text" id="regiment" name="regiment" placeholder="Enter regiment..." value="<?php echo htmlspecialchars($regiment); ?>">
                </div>

                <div class="form-group">
                    <label for="cemetery">Cemetery:</label>
                    <input type="text" id="cemetery" name="cemetery" placeholder="Enter cemetery..." value="<?php echo htmlspecialchars($cemetery); ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="submit">Search</button>
                    <button type="button" onclick="window.location.href='UserBuried.php'">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="results-container">
            <h2>Buried Records</h2>
            <?php if (empty($results)): ?>
                <p class="no-results">No records found.</p>
            <?php else: ?>
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Surname</th>
                            <th>Forename</th>
                            <th>Age</th>
                            <th>Date of Death</th>
                            <th>Rank</th>
                            <th>Service No</th>
                            <th>Regiment</th>
                            <th>Unit</th>
                            <th>Cemetery</th>
                            <th>Grave Ref</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['BuriedID']); ?></td>
                                <td><?php echo htmlspecialchars($row['Surname']); ?></td>
                                <td><?php echo htmlspecialchars($row['Forename']); ?></td>
                                <td><?php echo htmlspecialchars($row['Age']); ?></td>
                                <td><?php echo htmlspecialchars($row['Date of Death']); ?></td>
                                <td><?php echo htmlspecialchars($row['Rank']); ?></td>
                                <td><?php echo htmlspecialchars($row['Service No']); ?></td>
                                <td><?php echo htmlspecialchars($row['Regiment']); ?></td>
                                <td><?php echo htmlspecialchars($row['Unit']); ?></td>
                                <td><?php echo htmlspecialchars($row['Cemetary']); ?></td>
                                <td><?php echo htmlspecialchars($row['Grave Ref']); ?></td>
                                <td><?php echo htmlspecialchars($row['Info']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&regiment=<?php echo urlencode($regiment); ?>&cemetery=<?php echo urlencode($cemetery); ?>&page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
                    <?php endif; ?>
                    
                    <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?surname=<?php echo urlencode($surname); ?>&regiment=<?php echo urlencode($regiment); ?>&cemetery=<?php echo urlencode($cemetery); ?>&page=<?php echo $page + 1; ?>" class="page-link">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
