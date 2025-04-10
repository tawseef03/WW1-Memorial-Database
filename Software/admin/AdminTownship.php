<?php
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
$total_query = "SELECT COUNT(*) FROM township WHERE 1=1";
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
    <title>WW1 Township Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Township Records
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminTownship.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Names in Township Records</h2>
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
                            <th>Surname</th>
                            <th>Forename</th>
                            <th>Regiment</th>
                            <th>Unit</th>
                            <th>Memorial</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['HonourID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Electoral Ward']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Town']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Rank']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Battalion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Company']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Age']) . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['Service No']) . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['Other Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Other Battalion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Other Service No.']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Medals']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Enlistment Date']) . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['Discharge Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Death(in service Date)']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Cemetery/Memorial']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Cemetery/Memorial Ref']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Cemetery/Memorial Country']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Additional CWCG Info']) . "</td>";
                            
                            
                            echo "<td class='action-buttons'>
                                <button class='edit-btn' data-id='" . $row['TownshipID'] . "' 
                                data-surname='" . htmlspecialchars($row['Surname']) . "' 
                                data-forename='" . htmlspecialchars($row['Forename']) . "' 
                                data-regiment='" . htmlspecialchars($row['Regiment']) . "' 
                                data-unit='" . htmlspecialchars($row['Unit']) . "' 
                                data-memorial='" . htmlspecialchars($row['Memorial']) . "'>Edit</button>
                                <button class='delete-btn' data-id='" . $row['TownshipID'] . "'>Delete</button>
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


    <!-- Create Record Modal -->
    <div class="modal" id="createRecordModal">
        <div class="modal-content">
            <button class="close-btn" id="closeCreateModal">×</button>
            <h2>Create New Record</h2>
            <form id="createRecordForm" action="process_township.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="create_surname">Surname:</label>
                    <input type="text" id="create_surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="create_forename">Forename:</label>
                    <input type="text" id="create_forename" name="forename" required>
                </div>
                <div class="form-group">
                    <label for="create_regiment">Regiment:</label>
                    <input type="text" id="create_regiment" name="regiment" required>
                </div>
                <div class="form-group">
                    <label for="create_unit">Unit:</label>
                    <input type="text" id="create_unit" name="unit" required>
                </div>
                <div class="form-group">
                    <label for="create_memorial">Memorial:</label>
                    <input type="text" id="create_memorial" name="memorial" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Create Record</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Record Modal -->
    <div class="modal" id="editRecordModal">
        <div class="modal-content">
            <button class="close-btn" id="closeEditModal">×</button>
            <h2>Edit Record</h2>
            <form id="editRecordForm" action="process_township.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_record_id" name="record_id">
                <div class="form-group">
                    <label for="edit_surname">Surname:</label>
                    <input type="text" id="edit_surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="edit_forename">Forename:</label>
                    <input type="text" id="edit_forename" name="forename" required>
                </div>
                <div class="form-group">
                    <label for="edit_regiment">Regiment:</label>
                    <input type="text" id="edit_regiment" name="regiment" required>
                </div>
                <div class="form-group">
                    <label for="edit_unit">Unit:</label>
                    <input type="text" id="edit_unit" name="unit" required>
                </div>
                <div class="form-group">
                    <label for="edit_memorial">Memorial:</label>
                    <input type="text" id="edit_memorial" name="memorial" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Update Record</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteConfirmModal">
        <div class="modal-content">
            <button class="close-btn" id="closeDeleteModal">×</button>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this record? This action cannot be undone.</p>
            <form id="deleteRecordForm" action="process_township.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="delete_record_id" name="record_id">
                <div class="form-buttons">
                    <button type="button" id="cancelDelete" class="cancel-btn">Cancel</button>
                    <button type="submit" class="delete-confirm-btn">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const overlay = document.getElementById('adminOverlay');
        const createRecordBtn = document.getElementById('createRecordBtn');
        const createRecordModal = document.getElementById('createRecordModal');
        const closeCreateModal = document.getElementById('closeCreateModal');
        const editRecordModal = document.getElementById('editRecordModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDelete = document.getElementById('cancelDelete');
        
        // Open create record modal
        createRecordBtn.addEventListener('click', () => {
            createRecordModal.style.display = 'block';
            overlay.classList.add('active');
        });
        
        // Close create record modal
        closeCreateModal.addEventListener('click', () => {
            createRecordModal.style.display = 'none';
            overlay.classList.remove('active');
        });
        
        // Close edit record modal
        closeEditModal.addEventListener('click', () => {
            editRecordModal.style.display = 'none';
            overlay.classList.remove('active');
        });
        
        // Close delete confirmation modal
        closeDeleteModal.addEventListener('click', () => {
            deleteConfirmModal.style.display = 'none';
            overlay.classList.remove('active');
        });
        
        // Cancel delete
        cancelDelete.addEventListener('click', () => {
            deleteConfirmModal.style.display = 'none';
            overlay.classList.remove('active');
        });
        
        // Edit button click handlers
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Fill the edit form with the data attributes
                document.getElementById('edit_record_id').value = this.getAttribute('data-id');
                document.getElementById('edit_surname').value = this.getAttribute('data-surname');
                document.getElementById('edit_forename').value = this.getAttribute('data-forename');
                document.getElementById('edit_regiment').value = this.getAttribute('data-regiment');
                document.getElementById('edit_unit').value = this.getAttribute('data-unit');
                document.getElementById('edit_memorial').value = this.getAttribute('data-memorial');
                
                // Show the edit modal
                editRecordModal.style.display = 'block';
                overlay.classList.add('active');
            });
        });
        
        // Delete button click handlers
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('delete_record_id').value = this.getAttribute('data-id');
                deleteConfirmModal.style.display = 'block';
                overlay.classList.add('active');
            });
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === overlay) {
                createRecordModal.style.display = 'none';
                editRecordModal.style.display = 'none';
                deleteConfirmModal.style.display = 'none';
                overlay.classList.remove('active');
            }
        });
        
        // Handle search button click
        document.getElementById('searchButton').addEventListener('click', function() {
            document.getElementById('searchForm').submit();
        });
    });
    </script>
</body>
</html>

