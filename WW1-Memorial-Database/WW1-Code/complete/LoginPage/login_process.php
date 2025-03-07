<?php
session_start();
header('Content-Type: application/json');

// 修改引用路径，使用相对路径
require_once '../db_config.php';

try {
    // 获取和验证输入
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        throw new Exception('Username and password are required');
    }

    // 连接数据库
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }

    // 查询用户
    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // 登录成功
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        echo json_encode([
            'success' => true,
            'role' => $user['role'],
            'message' => 'Login successful'
        ]);
    } else {
        // 登录失败
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }

    $stmt->close();
    $mysqli->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
