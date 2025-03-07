<?php
require_once '../db_config.php';
header('Content-Type: application/json');

try {
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 获取数据库统计信息
    $stats = [
        'total_records' => 0,
        'last_update' => '',
        'database_size' => '',
    ];

    $result = $conn->query("SELECT COUNT(*) as count FROM newspapers");
    if ($result) {
        $stats['total_records'] = $result->fetch_assoc()['count'];
    }

    // 获取最后更新时间
    $result = $conn->query("SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = '{$dbConfig['dbname']}' AND TABLE_NAME = 'newspapers'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['last_update'] = $row['UPDATE_TIME'] ?? 'Unknown';
    }

    // 获取数据库大小
    $result = $conn->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size FROM information_schema.tables WHERE table_schema = '{$dbConfig['dbname']}'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['database_size'] = $row['size'] . ' MB';
    }

    // 获取搜索参数
    $surname = $_POST['surname'] ?? '';
    $forename = $_POST['forename'] ?? '';
    $newspaper = $_POST['newspaper'] ?? '';
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // 构建搜索条件
    $where_conditions = [];
    $params = [];
    $types = '';

    if (!empty($surname)) {
        $where_conditions[] = "Surname LIKE ?";
        $params[] = "%$surname%";
        $types .= 's';
    }
    if (!empty($forename)) {
        $where_conditions[] = "Forename LIKE ?";
        $params[] = "%$forename%";
        $types .= 's';
    }
    if (!empty($newspaper)) {
        $where_conditions[] = "`Newspaper Name` LIKE ?";
        $params[] = "%$newspaper%";
        $types .= 's';
    }

    // 构建SQL查询
    $sql = "SELECT 
            NewspaperID,
            Surname,
            Forename,
            Rank,
            Address,
            Regiment,
            Unit,
            `Article Description` as ArticleDescription,
            `Newspaper Name` as NewspaperName,
            DATE_FORMAT(`Paper Date`, '%d/%m/%Y') as PaperDate,
            `Page/Col` as PageCol,
            `Photo incl.` as PhotoIncl
            FROM newspapers";
    
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }
    $sql .= " ORDER BY `Paper Date` DESC, Surname, Forename LIMIT ?, ?";
    $types .= 'ii';
    $params[] = $offset;
    $params[] = $limit;

    // 准备和执行查询
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("准备SQL语句失败: " . $conn->error);
    }

    if (!empty($params)) {
        if (!$stmt->bind_param($types, ...$params)) {
            throw new Exception("绑定参数失败: " . $stmt->error);
        }
    }

    if (!$stmt->execute()) {
        throw new Exception("执行查询失败: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result === false) {
        throw new Exception("获取结果失败: " . $stmt->error);
    }

    $records = $result->fetch_all(MYSQLI_ASSOC);

    // 计算总记录数
    $count_sql = "SELECT COUNT(*) as total FROM newspapers";
    if (!empty($where_conditions)) {
        $count_sql .= " WHERE " . implode(" AND ", $where_conditions);
    }
    $count_stmt = $conn->prepare($count_sql);
    if (!empty($params)) {
        array_pop($params); // 移除 LIMIT 参数
        array_pop($params);
        if (!empty($params)) {
            $count_stmt->bind_param(substr($types, 0, -2), ...$params);
        }
    }
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];

    echo json_encode([
        'success' => true,
        'records' => $records,
        'total' => $total,
        'page' => $page,
        'pages' => ceil($total / $limit),
        'database_stats' => $stats,
        'debug' => [
            'sql' => $sql,
            'params' => $params
        ]
    ]);

} catch (Exception $e) {
    error_log("错误: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
} finally {
    if (isset($count_stmt)) $count_stmt->close();
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
