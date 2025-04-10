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
$townshipName = validateInput($_GET['townshipName'] ?? '');
$location = validateInput($_GET['location'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$records_per_page = 10;

try {
    // 构建基础查询
    $baseQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM townships WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($townshipName)) {
        $baseQuery .= " AND TownshipName LIKE ?";
        $params[] = "%$townshipName%";
        $types .= 's';
    }
    if (!empty($location)) {
        $baseQuery .= " AND Location LIKE ?";
        $params[] = "%$location%";
        $types .= 's';
    }

    // 添加分页
    $offset = ($page - 1) * $records_per_page;
    $baseQuery .= " ORDER BY TownshipName LIMIT ? OFFSET ?";
    $params[] = $records_per_page;
    $params[] = $offset;
    $types .= 'ii';

    // 准备并执行查询
    $stmt = $mysqli->prepare($baseQuery);
    if ($stmt === false) {
        throw new Exception("查询准备失败: " . $mysqli->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("查询执行失败: " . $stmt->error);
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

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            try {
                // 验证必填字段
                $required_fields = ['townshipName', 'location'];
                $missing_fields = array_filter($required_fields, function($field) {
                    return empty($_POST[$field]);
                });
                
                if (!empty($missing_fields)) {
                    throw new Exception("请填写所有必填字段");
                }

                $stmt = $mysqli->prepare("INSERT INTO townships (TownshipName, Location, Description) VALUES (?, ?, ?)");
                
                if (!$stmt) {
                    throw new Exception("准备语句失败: " . $mysqli->error);
                }
                
                $stmt->bind_param("sss", 
                    $_POST['townshipName'],
                    $_POST['location'],
                    $_POST['description']
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
            try {
                if (empty($_POST['record_id'])) {
                    throw new Exception("记录ID不能为空");
                }

                $stmt = $mysqli->prepare("UPDATE townships SET TownshipName=?, Location=?, Description=? WHERE TownshipID=?");
                
                if (!$stmt) {
                    throw new Exception("准备语句失败: " . $mysqli->error);
                }
                
                $stmt->bind_param("sssi", 
                    $_POST['townshipName'],
                    $_POST['location'],
                    $_POST['description'],
                    $_POST['record_id']
                );
                
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                
                $_SESSION['success'] = "记录更新成功";
                
            } catch (Exception $e) {
                $_SESSION['error'] = "更新失败: " . $e->getMessage();
            }
            break;
            
        case 'delete':
            try {
                if (empty($_POST['record_id'])) {
                    throw new Exception("记录ID不能为空");
                }

                $stmt = $mysqli->prepare("DELETE FROM townships WHERE TownshipID = ?");
                
                if (!$stmt) {
                    throw new Exception("准备语句失败: " . $mysqli->error);
                }
                
                $stmt->bind_param("i", $_POST['record_id']);
                
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                
                $_SESSION['success'] = "记录删除成功";
                
            } catch (Exception $e) {
                $_SESSION['error'] = "删除失败: " . $e->getMessage();
            }
            break;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Township Management - Admin</title>
    <link rel="icon" type="image/x-icon" href="../rsc/WebLogo.png">
    <link rel="stylesheet" href="AdminTownship.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../rsc/GroupLogo.png" alt="WW1 Group">
        </div>
        <div class="title">Township Management</div>
        <div class="navbuttons">
            <button onclick="window.location.href='../AdminManageDatabasePage/AdminManageDatabase.html'">Back to Database</button>
            <button onclick="window.location.href='../AdminSectionPage/AdminSection.php'">Back to Sections</button>
            <button onclick="window.location.href='../logout.php'">Logout</button>
        </div>
    </div>

    <div class="container">
        <div class="search-panel">
            <h3>Search Townships</h3>
            <form id="searchForm" method="get">
                <div class="form-group">
                    <label for="townshipName">Township Name:</label>
                    <input type="text" id="townshipName" name="townshipName" 
                           value="<?php echo htmlspecialchars($townshipName); ?>"
                           placeholder="Enter township name...">
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" 
                           value="<?php echo htmlspecialchars($location); ?>"
                           placeholder="Enter location...">
                </div>
                
                <!-- Add Field Selector -->
                <div class="field-selector">
                    <h4>Display Fields</h4>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="fields[]" value="TownshipID" checked> ID</label>
                        <label><input type="checkbox" name="fields[]" value="TownshipName" checked> Township Name</label>
                        <label><input type="checkbox" name="fields[]" value="Location" checked> Location</label>
                        <label><input type="checkbox" name="fields[]" value="Description"> Description</label>
                        <label><input type="checkbox" name="fields[]" value="Created"> Created Date</label>
                        <label><input type="checkbox" name="fields[]" value="LastModified"> Last Modified</label>
                    </div>
                    <div class="field-selector-buttons">
                        <button type="button" id="selectAllFields">Select All</button>
                        <button type="button" id="deselectAllFields">Deselect All</button>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="button" id="searchButton">Search</button>
                    <button type="button" id="resetButton" onclick="window.location.href='AdminTownship.php'">Reset</button>
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
                    <button id="createRecordBtn" class="create-record-btn">Create New Township</button>
                </div>
                <div class="display">
                    <?php if (empty($results)): ?>
                        <p class="no-records">No records found.</p>
                    <?php else: ?>
                        <table class="records-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Township Name</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['TownshipID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['TownshipName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Location']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Description'] ?? ''); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" data-id="<?php echo $row['TownshipID']; ?>">Edit</button>
                                            <button class="delete-btn" data-id="<?php echo $row['TownshipID']; ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?>&townshipName=<?php echo urlencode($townshipName); ?>&location=<?php echo urlencode($location); ?>">Previous</a>
                    <?php endif; ?>
                    
                    <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo ($page + 1); ?>&townshipName=<?php echo urlencode($townshipName); ?>&location=<?php echo urlencode($location); ?>">Next</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php include 'modals.php'; ?>

    <script src="AdminTownship.js"></script>
</body>
</html>