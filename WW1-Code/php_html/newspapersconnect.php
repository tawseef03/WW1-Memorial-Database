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

// 查询 newspapers 表中的数据
// Query data from the newspapers table
$result = $mysqli->query('SELECT * FROM newspapers');

// 如果查询成功，输出数据
// If query is successful, output the data
if ($result) {
    echo '<html><body><h1>Newspapers 数据</h1><table border="1">';
    echo '<tr><th>NewspaperID</th><th>Surname</th><th>Forename</th><th>Rank</th><th>Address</th><th>Regiment</th><th>Unit</th><th>Article Description</th><th>Newspaper Name</th><th>Paper Date</th><th>Page/Col</th><th>Photo incl.</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['NewspaperID'] . '</td>';
        echo '<td>' . $row['Surname'] . '</td>';
        echo '<td>' . $row['Forename'] . '</td>';
        echo '<td>' . $row['Rank'] . '</td>';
        echo '<td>' . $row['Address'] . '</td>';
        echo '<td>' . $row['Regiment'] . '</td>';
        echo '<td>' . $row['Unit'] . '</td>';
        echo '<td>' . $row['Article Description'] . '</td>';
        echo '<td>' . $row['Newspaper Name'] . '</td>';
        echo '<td>' . $row['Paper Date'] . '</td>';
        echo '<td>' . $row['Page/Col'] . '</td>';
        echo '<td>' . $row['Photo incl.'] . '</td>';
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