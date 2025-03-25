<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require 'db_connect.php';

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
    <title>WW1 Database Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Database Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='userSection.html'">Back to Sections</button>
            <button type="button" id="adminPanelToggle">Admin Panel</button>
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminBiographies.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Names on Bradford Biographies</h2>
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
                            <th>Regiment</th>
                            <th>Service No</th>
                            <th>Biography</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Service No']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['Biography']) . "' target='_blank'>View</a></td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody></table>";
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

    <!-- Admin Panel Modal -->
    <div class="overlay" id="adminOverlay"></div>
    <div class="admin-panel" id="adminPanel">
        <button class="close-btn" id="closeAdminPanel">×</button>
        <h2 id="adminPanelTitle">Admin Panel</h2>
        
        <div class="admin-actions">
            <button id="createRecordBtn">Create New Record</button>
            <button id="editRecordBtn">Edit Existing Record</button>
        </div>

        <!-- Create Record Form -->
        <form id="createRecordForm" style="display:none;">
            <input type="hidden" name="action" value="create">
            <h3>Create New Record</h3>
            <input type="text" name="surname" placeholder="Surname" required>
            <input type="text" name="forename" placeholder="Forename" required>
            <input type="text" name="regiment" placeholder="Regiment" required>
            <input type="text" name="service_no" placeholder="Service No" required>
            <input type="text" name="biography_link" placeholder="Biography Link" required>
            <div class="admin-buttons">
                <button type="submit">Create Record</button>
                <button type="button" id="cancelCreateRecord">Cancel</button>
            </div>
        </form>

        <!-- Edit Record Form -->
        <form id="editRecordForm" style="display:none;">
            <input type="hidden" name="action" value="edit">
            <h3>Edit Existing Record</h3>
            <select id="recordSelector" name="record_id" required>
                <option value="">Select a Record to Edit</option>
                <?php 
                foreach ($results as $row) {
                    echo "<option value='" . $row['ID'] . "'>" . 
                         htmlspecialchars($row['Surname'] . ", " . $row['Forename']) . 
                         " - " . htmlspecialchars($row['Regiment']) . 
                         "</option>";
                }
                ?>
            </select>
            <input type="text" name="surname" placeholder="Surname">
            <input type="text" name="forename" placeholder="Forename">
            <input type="text" name="regiment" placeholder="Regiment">
            <input type="text" name="service_no" placeholder="Service No">
            <input type="text" name="biography_link" placeholder="Biography Link">
            <div class="admin-buttons">
                <button type="submit">Update Record</button>
                <button type="button" id="cancelEditRecord">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        // Admin Panel Toggle
        const adminPanelToggle = document.getElementById('adminPanelToggle');
        const adminPanel = document.getElementById('adminPanel');
        const adminOverlay = document.getElementById('adminOverlay');
        const closeAdminPanel = document.getElementById('closeAdminPanel');

        adminPanelToggle.addEventListener('click', () => {
            adminPanel.classList.add('active');
            adminOverlay.classList.add('active');
        });

        closeAdminPanel.addEventListener('click', () => {
            adminPanel.classList.remove('active');
            adminOverlay.classList.remove('active');
        });

        // Create and Edit Record Buttons
        const createRecordBtn = document.getElementById('createRecordBtn');
        const editRecordBtn = document.getElementById('editRecordBtn');
        const createRecordForm = document.getElementById('createRecordForm');
        const editRecordForm = document.getElementById('editRecordForm');
        const cancelCreateRecord = document.getElementById('cancelCreateRecord');
        const cancelEditRecord = document.getElementById('cancelEditRecord');

        createRecordBtn.addEventListener('click', () => {
            createRecordForm.style.display = 'block';
            editRecordForm.style.display = 'none';
        });

        editRecordBtn.addEventListener('click', () => {
            createRecordForm.style.display = 'none';
            editRecordForm.style.display = 'block';
        });

        cancelCreateRecord.addEventListener('click', () => {
            createRecordForm.style.display = 'none';
        });

        cancelEditRecord.addEventListener('click', () => {
            editRecordForm.style.display = 'none';
        });

        // Form Submission Handlers
        createRecordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(createRecordForm);
            
            fetch('process_record.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Record created successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the record.');
            });
        });

        editRecordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(editRecordForm);
            
            fetch('process_record.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Record updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the record.');
            });
        });
    </script>
</body>
</html>