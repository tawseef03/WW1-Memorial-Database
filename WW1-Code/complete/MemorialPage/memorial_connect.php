<?php
require_once '../db_config.php';

header('Content-Type: application/json');

try {
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'fetch') {
        // 获取所有记录
        $sql = "SELECT MemorialID, Surname, Forename, Regiment FROM memorials";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("查询失败: " . $conn->error);
        }

        $records = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'records' => $records
        ]);
    } else {
        throw new Exception("无效的操作");
    }
} catch (Exception $e) {
    error_log("错误: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
