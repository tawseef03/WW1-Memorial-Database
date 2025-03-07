<?php
require_once 'db_config.php';

function getStats() {
    global $dbConfig;
    $stats = ['records' => 0, 'users' => 0, 'error' => null];
    
    try {
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($mysqli->connect_error) {
            throw new Exception("数据库连接失败: " . $mysqli->connect_error);
        }

        // 修改为正确的表名 biographyinfo
        $recordsResult = $mysqli->query("SELECT COUNT(*) as count FROM biographyinfo");
        if (!$recordsResult) {
            throw new Exception("查询biographyinfo表失败: " . $mysqli->error);
        }
        $row = $recordsResult->fetch_assoc();
        $stats['records'] = $row['count'];

        // 获取用户数量
        $usersResult = $mysqli->query("SELECT COUNT(*) as count FROM users");
        if (!$usersResult) {
            throw new Exception("查询users表失败: " . $mysqli->error);
        }
        $row = $usersResult->fetch_assoc();
        $stats['users'] = $row['count'];

    } catch (Exception $e) {
        $stats['error'] = $e->getMessage();
        error_log("统计数据获取失败: " . $e->getMessage());
    } finally {
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }
    return $stats;
}

// 返回JSON数据
header('Content-Type: application/json');
echo json_encode(getStats());
?>
