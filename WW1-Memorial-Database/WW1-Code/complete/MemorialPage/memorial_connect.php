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

    // 修改所有查询中的表名为 memorials
    $result = $conn->query("SELECT COUNT(*) as count FROM memorials");
    if ($result) {
        $stats['total_records'] = $result->fetch_assoc()['count'];
    }

    // 获取最后更新时间
    $result = $conn->query("SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = '{$dbConfig['dbname']}' AND TABLE_NAME = 'memorials'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['last_update'] = $row['UPDATE_TIME'] ?? '未知';
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
    $regiment = $_POST['regiment'] ?? '';
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 10;  // 修改每页显示数量为10条
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
    if (!empty($regiment)) {
        $where_conditions[] = "Regiment LIKE ?";
        $params[] = "%$regiment%";
        $types .= 's';
    }

    // 修改 SQL 查询以包含所有需要的字段
    $sql = "SELECT MemorialID, Surname, Forename, Regiment, Unit, `Cemetery/Memorial`, 
            `Cemetery/Grave Ref.`, `Cemetery / Memorial Country`, Memorial, 
            `Memorial Location`, `Memorial Info`, `Memorial Postcode`, 
            District, `Photo available` FROM memorials";
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }
    $sql .= " ORDER BY Surname, Forename LIMIT ?, ?";  // 添加排序
    $types .= 'ii';
    $params[] = $offset;
    $params[] = $limit;

    // 添加调试信息
    error_log("执行的SQL查询: " . $sql);
    error_log("参数: " . print_r($params, true));

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

    // 修改计数查询
    $count_sql = "SELECT COUNT(*) as total FROM memorials";
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

    // 在返回数据中添加统计信息
    echo json_encode([
        'success' => true,
        'records' => array_map(function($record) {
            return [
                'Surname' => $record['Surname'],
                'Forename' => $record['Forename'],
                'Regiment' => $record['Regiment'],
                'Unit' => $record['Unit'],
                'Cemetery' => $record['Cemetery/Memorial'],
                'GraveRef' => $record['Cemetery/Grave Ref.'],
                'Country' => $record['Cemetery / Memorial Country'],
                'Memorial' => $record['Memorial'],
                'Location' => $record['Memorial Location'],
                'Info' => $record['Memorial Info'],
                'Postcode' => $record['Memorial Postcode'],
                'District' => $record['District'],
                'Photo' => $record['Photo available']
            ];
        }, $records),
        'total' => $total,
        'page' => $page,
        'pages' => ceil($total / $limit),
        'database_stats' => $stats,
        'sql_debug' => $sql  // 添加调试信息
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
    // 修复重复关闭语句的问题
    if (isset($count_stmt)) {
        $count_stmt->close();
    }
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
