<?php
// connect to the database
require 'db_connect.php';

// Get used for search
$surname = $_GET['surname'] ?? '';
$forename = $_GET['forename'] ?? '';
$regiment = $_GET['regiment'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 10;

$offset = ($page - 1) * $records_per_page;

//query for search
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

// create the bind_param string for total query
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
    <title>WW1 Townships Database - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">
            WW1 Townships Records
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
                    <button type="button" id="resetButton" onclick="window.location.href = 'AdminTownships.php';">Reset</button>
                </div>
            </form>
        </div>
        
        <div class="content-panel">
            <div class="database-title">
                <h2>Township Records</h2>
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
                            <th>Address</th>
                            <th>Town</th>
                            <th>Regiment</th>
                            <th>Service No</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Town']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Service No']) . "</td>";
                            echo "<td class='action-buttons'>
                                <button class='edit-btn' data-id='" . $row['HonourID'] . "' 
                                data-surname='" . htmlspecialchars($row['Surname']) . "' 
                                data-forename='" . htmlspecialchars($row['Forename']) . "' 
                                data-address='" . htmlspecialchars($row['Address']) . "' 
                                data-electoral_ward='" . htmlspecialchars($row['Electoral Ward']) . "' 
                                data-town='" . htmlspecialchars($row['Town']) . "' 
                                data-rank='" . htmlspecialchars($row['Rank']) . "' 
                                data-regiment='" . htmlspecialchars($row['Regiment']) . "' 
                                data-battalion='" . htmlspecialchars($row['Battalion']) . "' 
                                data-company='" . htmlspecialchars($row['Company']) . "' 
                                data-age='" . htmlspecialchars($row['Age']) . "' 
                                data-service_no='" . htmlspecialchars($row['Service No']) . "' 
                                data-other_regiment='" . htmlspecialchars($row['Other Regiment']) . "' 
                                data-other_battalion='" . htmlspecialchars($row['Other Battalion']) . "' 
                                data-other_service_no='" . htmlspecialchars($row['Other Service No.']) . "' 
                                data-medals='" . htmlspecialchars($row['Medals']) . "' 
                                data-enlistment_date='" . htmlspecialchars($row['Enlistment Date']) . "' 
                                data-discharge_date='" . htmlspecialchars($row['Discharge Date']) . "' 
                                data-death_service_date='" . htmlspecialchars($row['Death (in service) Date']) . "' 
                                data-misc_info_nroh='" . htmlspecialchars($row['Misc Info Nroh']) . "' 
                                data-cemetery_memorial='" . htmlspecialchars($row['Cemetery/Memorial']) . "' 
                                data-cemetery_memorial_ref='" . htmlspecialchars($row['Cemetery/Memorial Ref']) . "' 
                                data-cemetery_memorial_country='" . htmlspecialchars($row['Cemetery/Memorial Country']) . "' 
                                data-additional_cwcg_info='" . htmlspecialchars($row['Additional CWCG Info']) . "'>Edit</button>
                                <button class='delete-btn' data-id='" . $row['HonourID'] . "'>Delete</button>
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
            <h2>Create New Township Record</h2>
            <form id="createRecordForm" action="process_townships.php" method="POST">
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
                    <label for="create_address">Address:</label>
                    <input type="text" id="create_address" name="address">
                </div>
                <div class="form-group">
                    <label for="create_electoral_ward">Electoral Ward:</label>
                    <input type="text" id="create_electoral_ward" name="electoral_ward">
                </div>
                <div class="form-group">
                    <label for="create_town">Town:</label>
                    <input type="text" id="create_town" name="town">
                </div>
                <div class="form-group">
                    <label for="create_rank">Rank:</label>
                    <input type="text" id="create_rank" name="rank">
                </div>
                <div class="form-group">
                    <label for="create_regiment">Regiment:</label>
                    <input type="text" id="create_regiment" name="regiment">
                </div>
                <div class="form-group">
                    <label for="create_battalion">Battalion:</label>
                    <input type="text" id="create_battalion" name="battalion">
                </div>
                <div class="form-group">
                    <label for="create_company">Company:</label>
                    <input type="text" id="create_company" name="company">
                </div>
                <div class="form-group">
                    <label for="create_age">Age:</label>
                    <input type="text" id="create_age" name="age">
                </div>
                <div class="form-group">
                    <label for="create_service_no">Service No:</label>
                    <input type="text" id="create_service_no" name="service_no">
                </div>
                <div class="form-group">
                    <label for="create_other_regiment">Other Regiment:</label>
                    <input type="text" id="create_other_regiment" name="other_regiment">
                </div>
                <div class="form-group">
                    <label for="create_other_battalion">Other Battalion:</label>
                    <input type="text" id="create_other_battalion" name="other_battalion">
                </div>
                <div class="form-group">
                    <label for="create_other_service_no">Other Service No:</label>
                    <input type="text" id="create_other_service_no" name="other_service_no">
                </div>
                <div class="form-group">
                    <label for="create_medals">Medals:</label>
                    <input type="text" id="create_medals" name="medals">
                </div>
                <div class="form-group">
                    <label for="create_enlistment_date">Enlistment Date:</label>
                    <input type="text" id="create_enlistment_date" name="enlistment_date">
                </div>
                <div class="form-group">
                    <label for="create_discharge_date">Discharge Date:</label>
                    <input type="text" id="create_discharge_date" name="discharge_date">
                </div>
                <div class="form-group">
                    <label for="create_death_service_date">Death (in service date):</label>
                    <input type="text" id="create_death_service_date" name="death_service_date">
                </div>
                <div class="form-group">
                    <label for="create_misc_info_nroh">Misc Info Nroh:</label>
                    <input type="text" id="create_misc_info_nroh" name="misc_info_nroh">
                </div>
                <div class="form-group">
                    <label for="create_cemetery_memorial">Cemetery/Memorial:</label>
                    <input type="text" id="create_cemetery_memorial" name="cemetery_memorial">
                </div>
                <div class="form-group">
                    <label for="create_cemetery_memorial_ref">Cemetery/Memorial Ref:</label>
                    <input type="text" id="create_cemetery_memorial_ref" name="cemetery_memorial_ref">
                </div>
                <div class="form-group">
                    <label for="create_cemetery_memorial_country">Cemetery/Memorial Country:</label>
                    <input type="text" id="create_cemetery_memorial_country" name="cemetery_memorial_country">
                </div>
                <div class="form-group">
                    <label for="create_additional_cwcg_info">Additional CWCG Info:</label>
                    <input type="text" id="create_additional_cwcg_info" name="additional_cwcg_info">
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
            <h2>Edit Township Record</h2>
            <form id="editRecordForm" action="process_townships.php" method="POST">
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
                    <label for="edit_address">Address:</label>
                    <input type="text" id="edit_address" name="address">
                </div>
                <div class="form-group">
                    <label for="edit_electoral_ward">Electoral Ward:</label>
                    <input type="text" id="edit_electoral_ward" name="electoral_ward">
                </div>
                <div class="form-group">
                    <label for="edit_town">Town:</label>
                    <input type="text" id="edit_town" name="town">
                </div>
                <div class="form-group">
                    <label for="edit_rank">Rank:</label>
                    <input type="text" id="edit_rank" name="rank">
                </div>
                <div class="form-group">
                    <label for="edit_regiment">Regiment:</label>
                    <input type="text" id="edit_regiment" name="regiment">
                </div>
                <div class="form-group">
                    <label for="edit_battalion">Battalion:</label>
                    <input type="text" id="edit_battalion" name="battalion">
                </div>
                <div class="form-group">
                    <label for="edit_company">Company:</label>
                    <input type="text" id="edit_company" name="company">
                </div>
                <div class="form-group">
                    <label for="edit_age">Age:</label>
                    <input type="text" id="edit_age" name="age">
                </div>
                <div class="form-group">
                    <label for="edit_service_no">Service No:</label>
                    <input type="text" id="edit_service_no" name="service_no">
                </div>
                <div class="form-group">
                    <label for="edit_other_regiment">Other Regiment:</label>
                    <input type="text" id="edit_other_regiment" name="other_regiment">
                </div>
                <div class="form-group">
                    <label for="edit_other_battalion">Other Battalion:</label>
                    <input type="text" id="edit_other_battalion" name="other_battalion">
                </div>
                <div class="form-group">
                    <label for="edit_other_service_no">Other Service No:</label>
                    <input type="text" id="edit_other_service_no" name="other_service_no">
                </div>
                <div class="form-group">
                    <label for="edit_medals">Medals:</label>
                    <input type="text" id="edit_medals" name="medals">
                </div>
                <div class="form-group">
                    <label for="edit_enlistment_date">Enlistment Date:</label>
                    <input type="text" id="edit_enlistment_date" name="enlistment_date">
                </div>
                <div class="form-group">
                    <label for="edit_discharge_date">Discharge Date:</label>
                    <input type="text" id="edit_discharge_date" name="discharge_date">
                </div>
                <div class="form-group">
                    <label for="edit_death_service_date">Death (in service date):</label>
                    <input type="text" id="edit_death_service_date" name="death_service_date">
                </div>
                <div class="form-group">
                    <label for="edit_misc_info_nroh">Misc Info Nroh:</label>
                    <input type="text" id="edit_misc_info_nroh" name="misc_info_nroh">
                </div>
                <div class="form-group">
                    <label for="edit_cemetery_memorial">Cemetery/Memorial:</label>
                    <input type="text" id="edit_cemetery_memorial" name="cemetery_memorial">
                </div>
                <div class="form-group">
                    <label for="edit_cemetery_memorial_ref">Cemetery/Memorial Ref:</label>
                    <input type="text" id="edit_cemetery_memorial_ref" name="cemetery_memorial_ref">
                </div>
                <div class="form-group">
                    <label for="edit_cemetery_memorial_country">Cemetery/Memorial Country:</label>
                    <input type="text" id="edit_cemetery_memorial_country" name="cemetery_memorial_country">
                </div>
                <div class="form-group">
                    <label for="edit_additional_cwcg_info">Additional CWCG Info:</label>
                    <input type="text" id="edit_additional_cwcg_info" name="additional_cwcg_info">
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
            <form id="deleteRecordForm" action="process_townships.php" method="POST">
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
                document.getElementById('edit_address').value = this.getAttribute('data-address');
                document.getElementById('edit_electoral_ward').value = this.getAttribute('data-electoral_ward');
                document.getElementById('edit_town').value = this.getAttribute('data-town');
                document.getElementById('edit_rank').value = this.getAttribute('data-rank');
                document.getElementById('edit_regiment').value = this.getAttribute('data-regiment');
                document.getElementById('edit_battalion').value = this.getAttribute('data-battalion');
                document.getElementById('edit_company').value = this.getAttribute('data-company');
                document.getElementById('edit_age').value = this.getAttribute('data-age');
                document.getElementById('edit_service_no').value = this.getAttribute('data-service_no');
                document.getElementById('edit_other_regiment').value = this.getAttribute('data-other_regiment');
                document.getElementById('edit_other_battalion').value = this.getAttribute('data-other_battalion');
                document.getElementById('edit_other_service_no').value = this.getAttribute('data-other_service_no');
                document.getElementById('edit_medals').value = this.getAttribute('data-medals');
                document.getElementById('edit_enlistment_date').value = this.getAttribute('data-enlistment_date');
                document.getElementById('edit_discharge_date').value = this.getAttribute('data-discharge_date');
                document.getElementById('edit_death_service_date').value = this.getAttribute('data-death_service_date');
                document.getElementById('edit_misc_info_nroh').value = this.getAttribute('data-misc_info_nroh');
                document.getElementById('edit_cemetery_memorial').value = this.getAttribute('data-cemetery_memorial');
                document.getElementById('edit_cemetery_memorial_ref').value = this.getAttribute('data-cemetery_memorial_ref');
                document.getElementById('edit_cemetery_memorial_country').value = this.getAttribute('data-cemetery_memorial_country');
                document.getElementById('edit_additional_cwcg_info').value = this.getAttribute('data-additional_cwcg_info');
                
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
    });
</script>
</body>
</html>