<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $mysqli = new mysqli($host, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        throw new Exception('Connection failed: ' . $mysqli->connect_error);
    }

    // Get total records count
    $records_query = "SELECT COUNT(*) as total FROM records";
    $records_result = $mysqli->query($records_query);
    $records_count = $records_result->fetch_assoc()['total'];

    // Get total users count
    $users_query = "SELECT COUNT(*) as total FROM users";
    $users_result = $mysqli->query($users_query);
    $users_count = $users_result->fetch_assoc()['total'];

    echo json_encode([
        'records' => $records_count,
        'users' => $users_count
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>
