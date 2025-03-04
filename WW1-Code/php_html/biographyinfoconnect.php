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

// 查询 biographyinfo 表中的数据
// Query data from the biographyinfo table
$result = $mysqli->query('SELECT * FROM biographyinfo');

// 如果查询成功，输出数据
// If query is successful, output the data
if ($result) {
    echo '<html><body><h1>测试成功</h1><table border="1"><tr><th>BiographyID</th><th>Surname</th><th>Forename</th><th>Regiment</th><th>Service No</th><th>Biography</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['BiographyID'] . '</td><td>' . $row['Surname'] . '</td><td>' . $row['Forename'] . '</td><td>' . $row['Regiment'] . '</td><td>' . $row['Service No'] . '</td><td>' . $row['Biography'] . '</td></tr>';
    }
    echo '</table></body></html>';
} else {
    echo '查询失败: ' . $mysqli->error;
}

// 关闭数据库连接
// Close database connection
$mysqli->close();
?>