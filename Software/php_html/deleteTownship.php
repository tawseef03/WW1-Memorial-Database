<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\php_html\deleteTownship.php -->
<?php
require 'db_connect.php';

// 获取记录 ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

// 删除记录
$query = "DELETE FROM township WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: township.php?msg=Record deleted successfully");
} else {
    echo "Error deleting record: " . $mysqli->error;
}
?>