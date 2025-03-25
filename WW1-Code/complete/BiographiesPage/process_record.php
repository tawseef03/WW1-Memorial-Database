<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connect.php';

// Set response headers
header('Content-Type: application/json');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Determine the action
    $action = $_POST['action'] ?? '';

    // Validate required fields
    $requiredFields = ['surname', 'forename', 'regiment', 'service_no', 'biography_link'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing required fields: ' . implode(', ', $missingFields)
        ]);
        exit;
    }

    if ($action === 'create') {
        // Prepare insert statement
        $stmt = $mysqli->prepare("INSERT INTO biographyinfo 
            (Surname, Forename, Regiment, `Service No`, Biography) 
            VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            'sssss', 
            $_POST['surname'], 
            $_POST['forename'], 
            $_POST['regiment'], 
            $_POST['service_no'], 
            $_POST['biography_link']
        );
        
        $result = $stmt->execute();
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Record created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create record: ' . $mysqli->error]);
        }
    } elseif ($action === 'edit') {
        // Validate record ID for edit
        if (!isset($_POST['record_id']) || empty($_POST['record_id'])) {
            echo json_encode(['success' => false, 'message' => 'Record ID is required for editing']);
            exit;
        }

        // Prepare update statement
        $stmt = $mysqli->prepare("UPDATE biographyinfo SET 
            Surname = ?, 
            Forename = ?, 
            Regiment = ?, 
            `Service No` = ?, 
            Biography = ? 
            WHERE ID = ?");
        
        $stmt->bind_param(
            'sssssi', 
            $_POST['surname'], 
            $_POST['forename'], 
            $_POST['regiment'], 
            $_POST['service_no'], 
            $_POST['biography_link'], 
            $_POST['record_id']
        );
        
        $result = $stmt->execute();
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record: ' . $mysqli->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

// Close the database connection
$mysqli->close();
?>