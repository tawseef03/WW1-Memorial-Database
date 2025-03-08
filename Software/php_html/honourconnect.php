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

// 查询 honour 表中的数据
// Query data from the honour table
$result = $mysqli->query('SELECT * FROM honour');

// 如果查询成功，输出数据
// If query is successful, output the data
if ($result) {
    echo '<html><body><h1>测试成功</h1><table border="1">';
    echo '<tr>';
    echo '<th>HonourID</th>';
    echo '<th>Surname</th>';
    echo '<th>Forename</th>';
    echo '<th>Address</th>';
    echo '<th>Electoral Ward</th>';
    echo '<th>Town</th>';
    echo '<th>Rank</th>';
    echo '<th>Regiment</th>';
    echo '<th>Unit</th>';
    echo '<th>Company</th>';
    echo '<th>Age</th>';
    echo '<th>Service No</th>';
    echo '<th>Other Regiment</th>';
    echo '<th>Other Unit</th>';
    echo '<th>Other Service No.</th>';
    echo '<th>Medals</th>';
    echo '<th>Enlistment Date</th>';
    echo '<th>Discharge Date</th>';
    echo '<th>Death (in service) Date</th>';
    echo '<th>Misc Info Nroh</th>';
    echo '<th>Cemetery/Memorial</th>';
    echo '<th>Cemetery/Memorial Ref</th>';
    echo '<th>Cemetery/Memorial Country</th>';
    echo '<th>Additional CWCG Info</th>';
    echo '</tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['HonourID'] . '</td>';
        echo '<td>' . $row['Surname'] . '</td>';
        echo '<td>' . $row['Forename'] . '</td>';
        echo '<td>' . $row['Address'] . '</td>';
        echo '<td>' . $row['Electoral Ward'] . '</td>';
        echo '<td>' . $row['Town'] . '</td>';
        echo '<td>' . $row['Rank'] . '</td>';
        echo '<td>' . $row['Regiment'] . '</td>';
        echo '<td>' . $row['Unit'] . '</td>';
        echo '<td>' . $row['Company'] . '</td>';
        echo '<td>' . $row['Age'] . '</td>';
        echo '<td>' . $row['Service No'] . '</td>';
        echo '<td>' . $row['Other Regiment'] . '</td>';
        echo '<td>' . $row['Other Unit'] . '</td>';
        echo '<td>' . $row['Other Service No.'] . '</td>';
        echo '<td>' . $row['Medals'] . '</td>';
        echo '<td>' . $row['Enlistment Date'] . '</td>';
        echo '<td>' . $row['Discharge Date'] . '</td>';
        echo '<td>' . $row['Death (in service) Date'] . '</td>';
        echo '<td>' . $row['Misc Info Nroh'] . '</td>';
        echo '<td>' . $row['Cemetery/Memorial'] . '</td>';
        echo '<td>' . $row['Cemetery/Memorial Ref'] . '</td>';
        echo '<td>' . $row['Cemetery/Memorial Country'] . '</td>';
        echo '<td>' . $row['Additional CWCG Info'] . '</td>';
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