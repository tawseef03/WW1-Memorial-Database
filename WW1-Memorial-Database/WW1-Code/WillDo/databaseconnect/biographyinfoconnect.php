<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_config.php';

$mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $mysqli->connect_error]));
}

$result = $mysqli->query('SELECT * FROM biographyinfo');

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['BiographyID'],
            'surname' => $row['Surname'],
            'forename' => $row['Forename'],
            'regiment' => $row['Regiment'],
            'serviceNumber' => $row['Service No'],
            'biography' => $row['Biography']
        ];
    }
    echo json_encode(['success' => true, 'records' => $data]);
} else {
    echo json_encode(['error' => 'Query failed: ' . $mysqli->error]);
}

$mysqli->close();
?>