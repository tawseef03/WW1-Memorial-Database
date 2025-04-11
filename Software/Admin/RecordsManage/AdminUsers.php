<?php
// connect to the database
require '../../Global/db_connect.php';

// Get used for search
$username = $_GET['username'] ?? '';
$page = $_GET['page'] ?? 1;
$records_per_page = 5;

$offset = ($page - 1) * $records_per_page;

// Query for search
$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($username)) {
    $query .= " AND Username LIKE ?";
    $params[] = "%$username%";
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
$total_query = "SELECT COUNT(*) FROM users WHERE 1=1";
$total_params = [];

if (!empty($username)) {
    $total_query .= " AND Username LIKE ?";
    $total_params[] = "%$username%";
}

$total_stmt = $mysqli->prepare($total_query);

// Create the bind_param string for total query
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
    <title>WW1 Database Users - Admin</title>
    <link rel="icon" type="image/x-icon" href="../../Resource/Images/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase.css">
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="../../Resource/Images/GroupLogo.png" alt="WW1 Group">
    </div>
    <div class="title">
        WW1 Database Users
    </div>
    <div class="navbuttons">
        <button type="button" onclick="location.href='../AdminPage.php'">Back to Panel</button>
    </div>
</div>

<div class="container">
    <div class="search-panel">
        <h3>Search Users</h3>
        <form id="searchForm" method="get">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter username..." value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-buttons">
                <button type="submit" id="searchButton">Search</button>
                <button type="button" id="resetButton" onclick="window.location.href = 'AdminUsers.php';">Reset</button>
            </div>
        </form>
    </div>
    
    <div class="content-panel">
        <div class="records-container">
            <div class="records-header">
                <h3>Users</h3>
                <button id="createRecordBtn" class="create-record-btn">Create New User</button>
            </div>
            
            <div class="display">
                <?php
                if (empty($results)) {
                    echo "<p>No users found.</p>";
                } else {
                    echo "<table class='records-table'>";
                    echo "<thead><tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr></thead><tbody>";
                    
                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Password']) . "</td>";
                        echo "<td>" . ($row['User Type'] == 1 ? 'Admin' : 'Guest') . "</td>";
                        echo "<td class='action-buttons'>
                            <button class='edit-btn' 
                                data-id='" . $row['UserID'] . "' 
                                data-username='" . htmlspecialchars($row['Username']) . "' 
                                data-usertype='" . $row['User Type'] . "'>Edit</button>
                            <button class='delete-btn' data-id='" . $row['UserID'] . "'>Delete</button>
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
                    <a href="?username=<?php echo urlencode($username); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                
                <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?username=<?php echo urlencode($username); ?>&page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Record Modal -->
<div class="modal" id="createRecordModal">
    <div class="modal-content">
        <button class="close-btn" id="closeCreateModal">×</button>
        <h2>Create New User</h2>
        <form id="createRecordForm" action="process_users.php" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="create_username">Username:</label>
                <input type="text" id="create_username" name="username" required>
            </div>
            <div class="form-group">
                <label for="create_password">Password:</label>
                <input type="password" id="create_password" name="password" required>
            </div>
            <div class="form-group">
                <label for="create_usertype">User Type:</label>
                <select id="create_usertype" name="usertype" required>
                    <option value="1">Admin</option>
                    <option value="2">Guest</option>
                </select>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-btn">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Record Modal -->
<div class="modal" id="editRecordModal">
    <div class="modal-content">
        <button class="close-btn" id="closeEditModal">×</button>
        <h2>Edit User</h2>
        <form id="editRecordForm" action="process_users.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_record_id" name="user_id">
            <div class="form-group">
                <label for="edit_username">Username:</label>
                <input type="text" id="edit_username" name="username" required>
            </div>
            <div class="form-group">
                <label for="edit_password">Password:</label>
                <input type="password" id="edit_password" name="password" required>
            </div>
            <div class="form-group">
                <label for="edit_usertype">User Type:</label>
                <select id="edit_usertype" name="usertype" required>
                    <option value="1">Admin</option>
                    <option value="2">Guest</option>
                </select>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-btn">Update User</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmModal">
    <div class="modal-content">
        <button class="close-btn" id="closeDeleteModal">×</button>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        <form id="deleteRecordForm" action="process_users.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="delete_record_id" name="user_id">
            <div class="form-buttons">
                <button type="button" id="cancelDelete" class="cancel-btn">Cancel</button>
                <button type="submit" class="delete-confirm-btn">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    });
    
    // Close create record modal
    closeCreateModal.addEventListener('click', () => {
        createRecordModal.style.display = 'none';
    });
    
    // Edit button click handlers
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_record_id').value = this.getAttribute('data-id');
            document.getElementById('edit_username').value = this.getAttribute('data-username');
            document.getElementById('edit_usertype').value = this.getAttribute('data-usertype'); // Set UserType
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

    // Close modals
    closeEditModal.addEventListener('click', () => {
        editRecordModal.style.display = 'none';
    });
    closeDeleteModal.addEventListener('click', () => {
        deleteConfirmModal.style.display = 'none';
    });

    // Search button
    document.getElementById('searchButton').addEventListener('click', function() {
        document.getElementById('searchForm').submit();
    });
});
</script>
</body>
</html>