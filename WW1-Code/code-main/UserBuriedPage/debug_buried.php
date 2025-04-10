<?php
require '../db_config.php';

echo "<h2>数据库连接测试</h2>";

$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

if ($mysqli->connect_error) {
    die("连接失败: " . $mysqli->connect_error);
}
echo "数据库连接成功!<br><br>";

// 测试表是否存在
$table_query = "SHOW TABLES LIKE 'buried'";
$table_result = $mysqli->query($table_query);
if($table_result->num_rows > 0) {
    echo "buried表存在!<br><br>";
} else {
    die("buried表不存在!");
}

// 显示表结构
echo "<h3>表结构:</h3>";
$structure_query = "DESCRIBE buried";
$structure_result = $mysqli->query($structure_query);
if($structure_result) {
    echo "<table border='1'>";
    echo "<tr><th>字段</th><th>类型</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $structure_result->fetch_assoc()) {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table><br><br>";
}

// 显示记录数
$count_query = "SELECT COUNT(*) as count FROM buried";
$count_result = $mysqli->query($count_query);
if($count_result) {
    $count = $count_result->fetch_assoc()['count'];
    echo "表中总记录数: " . $count . "<br><br>";
}

// 显示前5条记录
echo "<h3>示例数据 (前5条记录):</h3>";
$sample_query = "SELECT * FROM buried LIMIT 5";
$sample_result = $mysqli->query($sample_query);
if($sample_result) {
    if($sample_result->num_rows > 0) {
        echo "<table border='1'>";
        // 表头
        $first_row = $sample_result->fetch_assoc();
        echo "<tr>";
        foreach(array_keys($first_row) as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "</tr>";
        // 显示第一行
        echo "<tr>";
        foreach($first_row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
        // 显示其余行
        while($row = $sample_result->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "表中没有数据";
    }
}

// 测试查询语句
echo "<h3>测试查询:</h3>";
$test_query = "SELECT BuriedID, Surname, Forename, Age, `Date of Death`, Rank, `Service No`, 
              Regiment, Unit, Cemetary, `Grave Ref`, Info FROM buried LIMIT 1";
$test_result = $mysqli->query($test_query);
if($test_result === false) {
    echo "查询错误: " . $mysqli->error;
} else {
    echo "查询语句执行成功!<br>";
    if($test_result->num_rows > 0) {
        $row = $test_result->fetch_assoc();
        echo "查询结果示例:<br>";
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
}

$mysqli->close();
?>
