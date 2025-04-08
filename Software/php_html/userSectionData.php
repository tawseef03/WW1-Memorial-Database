<?php
require_once 'auth_check.php';
require 'db_connect.php';

function getCount($mysqli, $table) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $mysqli->query($sql);
    $count = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
    $result->free();
    return $count;
}

$data = [
    "counts" => [
        "township" => getCount($mysqli, "township"),
        "memorial" => getCount($mysqli, "memorials"),
        "buried" => getCount($mysqli, "buried"),
        "newspaper" => getCount($mysqli, "newspapers"),
        "biography" => getCount($mysqli, "biographyinfo")
    ],
    "aboutPaths" => []
];

$query = "SELECT filePath FROM about ORDER BY aboutID ASC";
$result = $mysqli->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data["aboutPaths"][] = $row['filePath'];
    }
    $result->free();
}

header('Content-Type: application/json');
echo json_encode($data);