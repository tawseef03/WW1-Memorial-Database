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

// 查询 buried 表中的数据
// Query data from the buried table
$result = $mysqli->query('SELECT * FROM buried');

// 如果查询成功，输出数据
// If query is successful, output the data
if ($result) {
    echo '<html><body><h1>测试成功</h1><table border="1">';
    echo '<tr><th>BuriedID</th><th>Surname</th><th>Forename</th><th>Age</th><th>Date of Death</th><th>Rank</th><th>Service No</th><th>Regiment</th><th>Unit</th><th>Cemetary</th><th>Grave Ref</th><th>Info</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['BuriedID'] . '</td>';
        echo '<td>' . $row['Surname'] . '</td>';
        echo '<td>' . $row['Forename'] . '</td>';
        echo '<td>' . $row['Age'] . '</td>';
        echo '<td>' . $row['Date of Death'] . '</td>';
        echo '<td>' . $row['Rank'] . '</td>';
        echo '<td>' . $row['Service No'] . '</td>';
        echo '<td>' . $row['Regiment'] . '</td>';
        echo '<td>' . $row['Unit'] . '</td>';
        echo '<td>' . $row['Cemetary'] . '</td>';
        echo '<td>' . $row['Grave Ref'] . '</td>';
        echo '<td>' . $row['Info'] . '</td>';
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