<?php
// 包含数据库连接文件
// Contains the database connection file
require_once __DIR__ . '/db_config.php';

// 创建数据库连接
// Create database connection
$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

// 检查连接是否成功
// Check if connection is successful
if ($mysqli->connect_error) {
    die('连接失败: ' . $mysqli->connect_error);
}

// 查询 users 表中的数据
// Query data from the users table
$result = $mysqli->query('SELECT * FROM users');

// 如果查询成功，输出测试成功
// If query is successful, output "测试成功"
if ($result) {
    echo '<html><body><h1>测试成功</h1><table border="1">';
    echo '<tr><th>UserID</th><th>Username</th><th>Password</th><th>User Type</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['UserID'] . '</td>';
        echo '<td>' . $row['Username'] . '</td>';
        echo '<td>' . $row['Password'] . '</td>';
        echo '<td>' . $row['User Type'] . '</td>';
        echo '</tr>';
    }
    echo '</table></body></html>';
} else {
    echo '查询失败: ' . $mysqli->error;
}

// 关闭数据库连接
// Close database connection
$mysqli->close();
?>