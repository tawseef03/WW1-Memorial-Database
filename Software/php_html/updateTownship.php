<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\php_html\updateTownship.php -->
<?php
require 'db_connect.php';

// 获取表单数据
$id = $_POST['id'];
$surname = $_POST['surname'];
$forename = $_POST['forename'];
$regiment = $_POST['regiment'];
$unit = $_POST['unit'];
$memorial = $_POST['memorial'];

// 更新记录
$query = "UPDATE township SET Surname = ?, Forename = ?, Regiment = ?, Unit = ?, Memorial = ? WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('sssssi', $surname, $forename, $regiment, $unit, $memorial, $id);

if ($stmt->execute()) {
    header("Location: township.php?msg=Record updated successfully");
} else {
    echo "Error updating record: " . $mysqli->error;
}
?>