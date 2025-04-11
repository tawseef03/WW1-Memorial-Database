<?php
require_once '../../Global/admin_auth_check.php';
require '../db_connect.php';

// Get search parameters and current page
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

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

// Dynamically create the bind_param string
$bind_types = str_repeat('s', count($params) - 2) . "ii";
$stmt->bind_param($bind_types, ...$params);

$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the total number of records for pagination calculation
$total_query = "SELECT COUNT(*) FROM buried WHERE 1=1";
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
    <title>WW1 Newspaper Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="../../Resource/Images/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Newspaper Records
        </div>
        <div class="navbuttons">
            <button type="button" onclick="location.href='../AdminManageDatabase.php'">Back</button>
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminBurials.php';">Reset</button>
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
                    <button id="uploadCsvBtn" class="upload-csv-btn">Upload CSV</button>
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
                            <th>Rank</th>
                            <th>Paper Date</th>
                            <th>Regiment</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Rank']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Paper Date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td class='action-buttons'>
                                <button class='edit-btn' data-id='" . $row['NewspaperID'] . "' 
                                data-surname='" . htmlspecialchars($row['Surname']) . "' 
                                data-forename='" . htmlspecialchars($row['Forename']) . "' 
                                data-rank='" . htmlspecialchars($row['Rank']) . "' 
                                data-address='" . htmlspecialchars($row['Address']) . "' 
                                data-regiment='" . htmlspecialchars($row['Regiment']) . "' 
                                data-unit='" . htmlspecialchars($row['Unit']) . "' 
                                data-article_description='" . htmlspecialchars($row['Article Description']) . "' 
                                data-newspaper_name='" . htmlspecialchars($row['Newspaper Name']) . "' 
                                data-paper_date='" . htmlspecialchars($row['Paper Date']) . "' 
                                data-page_col='" . htmlspecialchars($row['Page/Col']) . "' 
                                data-photo_incl='" . htmlspecialchars($row['Photo incl.']) . "'>Edit</button>
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

<!-- CSV Upload Modal -->
<div class="modal" id="uploadCsvModal">
    <div class="modal-content">
        <button class="close-btn" id="closeCsvModal">×</button>
        <h2>Upload CSV File</h2>
        <form id="uploadCsvForm" action="process_newspaper.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_csv">
            <div class="form-group">
                <label for="csv_file">Select CSV File:</label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-btn">Upload</button>
            </div>
        </form>
    </div>
</div>

 <!-- Create Record Modal -->
 <div class="modal" id="createRecordModal">
    <div class="modal-content">
        <button class="close-btn" id="closeCreateModal">×</button>
        <h2>Create New Newspaper Record</h2>
        <form id="createRecordForm" action="process_newspaper.php" method="POST">
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
                <label for="create_rank">Rank:</label>
                <input type="text" id="create_rank" name="rank">
            </div>
            <div class="form-group">
                <label for="create_address">Address:</label>
                <input type="text" id="create_address" name="address">
            </div>
            <div class="form-group">
                <label for="create_regiment">Regiment:</label>
                <input type="text" id="create_regiment" name="regiment">
            </div>
            <div class="form-group">
                <label for="create_unit">Unit:</label>
                <input type="text" id="create_unit" name="unit">
            </div>
            <div class="form-group">
                <label for="create_article_description">Article Description:</label>
                <input type="text" id="create_article_description" name="article_description">
            </div>
            <div class="form-group">
                <label for="create_newspaper_name">Newspaper Name:</label>
                <input type="text" id="create_newspaper_name" name="newspaper_name">
            </div>
            <div class="form-group">
                <label for="create_paper_date">Paper Date:</label>
                <input type="date" id="create_paper_date" name="paper_date">
            </div>
            <div class="form-group">
                <label for="create_page_col">Page/Col:</label>
                <input type="text" id="create_page_col" name="page_col">
            </div>
            <div class="form-group">
                <label for="create_photo_incl">Photo incl.:</label>
                <input type="text" id="create_photo_incl" name="photo_incl">
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
        <h2>Edit Newspaper Record</h2>
        <form id="editRecordForm" action="process_newspaper.php" method="POST">
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
                <label for="edit_rank">Rank:</label>
                <input type="text" id="edit_rank" name="rank">
            </div>
            <div class="form-group">
                <label for="edit_address">Address:</label>
                <input type="text" id="edit_address" name="address">
            </div>
            <div class="form-group">
                <label for="edit_regiment">Regiment:</label>
                <input type="text" id="edit_regiment" name="regiment">
            </div>
            <div class="form-group">
                <label for="edit_unit">Unit:</label>
                <input type="text" id="edit_unit" name="unit">
            </div>
            <div class="form-group">
                <label for="edit_article_description">Article Description:</label>
                <input type="text" id="edit_article_description" name="article_description">
            </div>
            <div class="form-group">
                <label for="edit_newspaper_name">Newspaper Name:</label>
                <input type="text" id="edit_newspaper_name" name="newspaper_name">
            </div>
            <div class="form-group">
                <label for="edit_paper_date">Paper Date:</label>
                <input type="date" id="edit_paper_date" name="paper_date">
            </div>
            <div class="form-group">
                <label for="edit_page_col">Page/Col:</label>
                <input type="text" id="edit_page_col" name="page_col">
            </div>
            <div class="form-group">
                <label for="edit_photo_incl">Photo incl.:</label>
                <input type="text" id="edit_photo_incl" name="photo_incl">
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
        <form id="deleteRecordForm" action="process_newspaper.php" method="POST">
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
        const createRecordModal = document.getElementById('createRecordModal');
        const closeCreateModal = document.getElementById('closeCreateModal');
        const editRecordModal = document.getElementById('editRecordModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const createRecordBtn = document.getElementById('createRecordBtn');
        
        // Open create record modal
        createRecordBtn.addEventListener('click', () => {
            createRecordModal.style.display = 'block';
        });
        
        // Close create record modal
        closeCreateModal.addEventListener('click', () => {
            createRecordModal.style.display = 'none';
        });
        
        // Close edit record modal
        closeEditModal.addEventListener('click', () => {
            editRecordModal.style.display = 'none';
        });
        
        // Close delete confirmation modal
        closeDeleteModal.addEventListener('click', () => {
            deleteConfirmModal.style.display = 'none';
        });
        
        // Cancel delete
        cancelDelete.addEventListener('click', () => {
            deleteConfirmModal.style.display = 'none';
        });
        
        // Edit button click handlers
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Fill the edit form with the data attributes
                document.getElementById('edit_record_id').value = this.getAttribute('data-id');
                document.getElementById('edit_surname').value = this.getAttribute('data-surname');
                document.getElementById('edit_forename').value = this.getAttribute('data-forename');
                document.getElementById('edit_rank').value = this.getAttribute('data-rank');
                document.getElementById('edit_address').value = this.getAttribute('data-address');
                document.getElementById('edit_regiment').value = this.getAttribute('data-regiment');
                document.getElementById('edit_unit').value = this.getAttribute('data-unit');
                document.getElementById('edit_article_description').value = this.getAttribute('data-article_description');
                document.getElementById('edit_newspaper_name').value = this.getAttribute('data-newspaper_name');
                document.getElementById('edit_paper_date').value = this.getAttribute('data-paper_date');
                document.getElementById('edit_page_col').value = this.getAttribute('data-page_col');
                document.getElementById('edit_photo_incl').value = this.getAttribute('data-photo_incl');
                
                // Show the edit modal
                editRecordModal.style.display = 'block';
            });
        });
        
        // Delete button click handlers
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('delete_record_id').value = this.getAttribute('data-id');
                deleteConfirmModal.style.display = 'block';
            });
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === createRecordModal) {
                createRecordModal.style.display = 'none';
            }
            if (event.target === editRecordModal) {
                editRecordModal.style.display = 'none';
            }
            if (event.target === deleteConfirmModal) {
                deleteConfirmModal.style.display = 'none';
            }
        });
        
        // Handle search button click
        document.getElementById('searchButton').addEventListener('click', function() {
            document.getElementById('searchForm').submit();
        });

        const uploadCsvBtn = document.getElementById('uploadCsvBtn');
        const uploadCsvModal = document.getElementById('uploadCsvModal');
        const closeCsvModal = document.getElementById('closeCsvModal');

        // Open CSV upload modal
        uploadCsvBtn.addEventListener('click', () => {
            uploadCsvModal.style.display = 'block';
        });

        // Close CSV upload modal
        closeCsvModal.addEventListener('click', () => {
            uploadCsvModal.style.display = 'none';
        });

        // Close modal when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === uploadCsvModal) {
                uploadCsvModal.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>