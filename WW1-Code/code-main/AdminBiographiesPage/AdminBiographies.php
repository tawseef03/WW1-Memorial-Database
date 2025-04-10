<?php
session_start();
require_once '../db_config.php';

// 验证管理员登录状态
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 1) {
    header('Location: ../LoginPage/login.php');
    exit;
}

// 创建数据库连接
try {
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    
    if ($mysqli->connect_error) {
        throw new Exception('数据库连接失败: ' . $mysqli->connect_error);
    }
} catch (Exception $e) {
    $_SESSION['error'] = "数据库错误: " . $e->getMessage();
    header('Location: ../AdminSectionPage/AdminSection.php');
    exit;
}

// 表单验证函数
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 获取并验证搜索参数
$surname = validateInput($_GET['surname'] ?? '');
$forename = validateInput($_GET['forename'] ?? '');
$regiment = validateInput($_GET['regiment'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$records_per_page = 10;

try {
    // 构建基础查询
    $baseQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM biographyinfo WHERE 1=1";
    $params = [];
    $types = '';

    // 动态添加搜索条件
    if (!empty($surname)) {
        $baseQuery .= " AND Surname LIKE ?";
        $params[] = "%$surname%";
        $types .= 's';
    }
    if (!empty($forename)) {
        $baseQuery .= " AND Forename LIKE ?";
        $params[] = "%$forename%";
        $types .= 's';
    }
    if (!empty($regiment)) {
        $baseQuery .= " AND Regiment LIKE ?";
        $params[] = "%$regiment%";
        $types .= 's';
    }

    // 添加分页
    $offset = ($page - 1) * $records_per_page;
    $baseQuery .= " ORDER BY Surname, Forename LIMIT ? OFFSET ?";
    $params[] = $records_per_page;
    $params[] = $offset;
    $types .= 'ii';

    // 准备并执行查询
    $stmt = $mysqli->prepare($baseQuery);
    if ($stmt === false) {
        throw new Exception("Query preparation failed: " . $mysqli->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // 获取总记录数
    $totalResults = $mysqli->query("SELECT FOUND_ROWS()")->fetch_row()[0];
    $total_pages = ceil($totalResults / $records_per_page);

} catch (Exception $e) {
    $_SESSION['error'] = "数据库错误: " . $e->getMessage();
    $results = [];
    $total_pages = 0;
}

// 修改表单处理部分
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            try {
                // 验证必填字段
                $required_fields = ['surname', 'forename', 'regiment', 'service_no', 'biography_link'];
                $missing_fields = array_filter($required_fields, function($field) {
                    return empty($_POST[$field]);
                });
                
                if (!empty($missing_fields)) {
                    throw new Exception("请填写所有必填字段");
                }

                // 准备数据插入
                $stmt = $mysqli->prepare("INSERT INTO biographyinfo (Surname, Forename, Regiment, `Service No`, Biography) VALUES (?, ?, ?, ?, ?)");
                
                if (!$stmt) {
                    throw new Exception("准备语句失败: " . $mysqli->error);
                }
                
                $stmt->bind_param("sssss", 
                    $_POST['surname'],
                    $_POST['forename'],
                    $_POST['regiment'],
                    $_POST['service_no'],
                    $_POST['biography_link']
                );
                
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                
                $_SESSION['success'] = "记录创建成功";
                
            } catch (Exception $e) {
                $_SESSION['error'] = "创建失败: " . $e->getMessage();
            }
            break;
            
        case 'edit':
            // ...existing code...
            break;
            
        case 'delete':
            // ...existing code...
            break;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 在脚本结束时关闭数据库连接
if (isset($mysqli)) {
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WW1 Database Records - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminDatabase3.css">
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
        <button type="button" onclick="window.location.href='../AdminManageDatabasePage/AdminManageDatabase.html'">Back to Database</button>
        <button type="button" onclick="window.location.href='../AdminSectionPage/AdminSection.php'">Back to Sections</button>
        <button type="button" onclick="window.location.href='../logout.php'">Logout</button>
    </div>
</div>

<div class="container">
    <div class="search-panel">
        <h3>Search Criteria</h3>
        <form id="searchForm" method="get" action="AdminBiographies.php">
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
                            <th>ID</th>
                            <th>Surname</th>
                            <th>Forename</th>
                            <th>Regiment</th>
                            <th>Service No</th>
                            <th>Biography</th>
                            <th>Actions</th>
                        </tr></thead><tbody>";
                        foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['BiographyID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Forename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Regiment']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Service No']) . "</td>";
                            echo "<td>";
                            if ($row['Biography']) {
                                echo "<a href='" . htmlspecialchars($row['Biography']) . "' 
                                    target='_blank' 
                                    class='view-link'
                                    title='" . htmlspecialchars($row['Biography']) . "'>
                                    View
                                    <span class='tooltip'>" . htmlspecialchars($row['Biography']) . "</span>
                                    </a>";
                            } else {
                                echo "N/A";
                            }
                            echo "</td>";
                            echo "<td>";
                            echo "<div class='action-buttons-container'>";
                            echo "<button class='edit-btn' data-id='" . $row['BiographyID'] . "' 
                                data-surname='" . htmlspecialchars($row['Surname']) . "' 
                                data-forename='" . htmlspecialchars($row['Forename']) . "' 
                                data-regiment='" . htmlspecialchars($row['Regiment']) . "' 
                                data-service='" . htmlspecialchars($row['Service No']) . "' 
                                data-biography='" . htmlspecialchars($row['Biography']) . "'>Edit</button>";
                            echo "<button class='delete-btn' data-id='" . $row['BiographyID'] . "'>Delete</button>";
                            echo "</div>";
                            echo "</td>";
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

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Create Record Modal -->
    <div class="modal" id="createRecordModal">
        <div class="modal-content">
            <button class="close-btn" id="closeCreateModal">×</button>
            <h2>Create New Record</h2>
            <form id="createRecordForm" action="AdminBiographies.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="create_surname">Surname:*</label>
                    <input type="text" id="create_surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="create_forename">Forename:*</label>
                    <input type="text" id="create_forename" name="forename" required>
                </div>
                <div class="form-group">
                    <label for="create_regiment">Regiment:*</label>
                    <input type="text" id="create_regiment" name="regiment" required>
                </div>
                <div class="form-group">
                    <label for="create_service_no">Service No:*</label>
                    <input type="text" id="create_service_no" name="service_no" required>
                </div>
                <div class="form-group">
                    <label for="create_biography_link">Biography Link:*</label>
                    <input type="url" id="create_biography_link" name="biography_link" required>
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
            <form id="editRecordForm" action="AdminBiographies.php" method="POST">
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
                    <label for="edit_service_no">Service No:</label>
                    <input type="text" id="edit_service_no" name="service_no" required>
                </div>
                <div class="form-group">
                    <label for="edit_biography_link">Biography Link:</label>
                    <input type="text" id="edit_biography_link" name="biography_link" required>
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
            <form id="deleteRecordForm" action="AdminBiographies.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="delete_record_id" name="record_id">
                <div class="form-buttons">
                    <button type="button" id="cancelDelete" class="cancel-btn">取消</button>
                    <button type="submit" class="submit-btn">删除</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 修改删除确认框按钮样式 -->
    <style>
        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .cancel-btn,
        .submit-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            min-width: 100px;
        }
        
        .cancel-btn {
            background-color: #6c757d;
            color: white;
        }
        
        .submit-btn {
            background-color: #dc3545;
            color: white;
        }
        
        .action-buttons button {
            padding: 5px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>

    <style>
        /* 更新按钮样式 */
        .action-buttons-container {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            min-width: 120px; /* 确保容器最小宽度 */
        }

        .edit-btn,
        .delete-btn {
            padding: 4px 8px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            min-width: 50px; /* 设置最小宽度 */
            white-space: nowrap; /* 防止文字换行 */
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>

    <style>
        /* 表格样式调整 */
        .records-table td {
            padding: 8px;
            vertical-align: middle;
        }

        .records-table th:last-child,
        .records-table td:last-child {
            min-width: 130px; /* 确保操作列有足够宽度 */
            text-align: center;
        }
    </style>

    <style>
        /* 新增表单样式 */
        .form-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .tab-btn {
            padding: 8px 16px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            position: relative;
        }

        .tab-btn.active {
            color: #007bff;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
    </style>

    <style>
        /* 添加到现有样式中 */
        .view-link {
            position: relative;
            color: #007bff;
            text-decoration: none;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .view-link .tooltip {
            visibility: hidden;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .view-link:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        /* 添加一个小箭头 */
        .view-link .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
        }
    </style>

    <script>
    // 添加到现有的 DOMContentLoaded 事件处理程序中
    document.addEventListener('DOMContentLoaded', function() {
        // ...existing code...

        // 表单分步逻辑
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');
        const prevBtn = document.getElementById('prevStep');
        const nextBtn = document.getElementById('nextStep');
        const submitBtn = document.getElementById('submitCreate');
        let currentTab = 0;

        function showTab(index) {
            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));
            tabs[index].classList.add('active');
            contents[index].classList.add('active');
            
            prevBtn.style.display = index === 0 ? 'none' : 'block';
            nextBtn.style.display = index === tabs.length - 1 ? 'none' : 'block';
            submitBtn.style.display = index === tabs.length - 1 ? 'block' : 'none';
        }

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                currentTab = index;
                showTab(currentTab);
            });
        });

        prevBtn.addEventListener('click', () => {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentTab < tabs.length - 1) {
                if (validateTab(currentTab)) {
                    currentTab++;
                    showTab(currentTab);
                }
            }
        });

        function validateTab(tabIndex) {
            const currentContent = contents[tabIndex];
            const requiredFields = currentContent.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('invalid');
                } else {
                    field.classList.remove('invalid');
                }
            });

            return isValid;
        }

        // 初始化显示
        showTab(currentTab);
    });
    </script>

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

        // Admin panel toggle
        const adminPanelToggle = document.getElementById('adminPanelToggle');

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
                document.getElementById('edit_service_no').value = this.getAttribute('data-service');
                document.getElementById('edit_biography_link').value = this.getAttribute('data-biography');

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