<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\WW1-Code\complete\BuriedPage\buried_connect.php -->
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
        $sql = "SELECT BuriedID, Surname, Forename, Regiment FROM buried";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("查询失败: " . $conn->error);
        }

        $records = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'records' => $records
        ]);
    } elseif ($action === 'update') {
        // 更新记录
        $id = $_POST['id'];
        $surname = $_POST['surname'];
        $forename = $_POST['forename'];
        $regiment = $_POST['regiment'];

        $sql = "UPDATE buried SET Surname = ?, Forename = ?, Regiment = ? WHERE BuriedID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("准备SQL语句失败: " . $conn->error);
        }

        if (!$stmt->bind_param('sssi', $surname, $forename, $regiment, $id)) {
            throw new Exception("绑定参数失败: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("执行更新失败: " . $stmt->error);
        }

        echo json_encode([
            'success' => true
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