<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\admin\process_township.php -->
<?php
// Include the database connection
require 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $memorial = $_POST['memorial'] ?? '';
        $memorial_info = $_POST['memorial_info'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $district = $_POST['district'] ?? '';
        $photo = $_POST['photo'] ?? '0';
        
        // Prepare and execute the query with prepared statements
        $query = "INSERT INTO township (Surname, Forename, Regiment, Unit, Memorial, `Memorial Info`, Postcode, District, `Photo available`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssi', $surname, $forename, $regiment, $unit, $memorial, $memorial_info, $postcode, $district, $photo);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship.php?error=Error creating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $memorial = $_POST['memorial'] ?? '';
        $memorial_info = $_POST['memorial_info'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $district = $_POST['district'] ?? '';
        $photo = $_POST['photo'] ?? '0';
        
        // Prepare and execute the query with prepared statements
        $query = "UPDATE township SET 
                 Surname = ?, 
                 Forename = ?, 
                 Regiment = ?, 
                 Unit = ?, 
                 Memorial = ?, 
                 `Memorial Info` = ?, 
                 Postcode = ?, 
                 District = ?, 
                 `Photo available` = ? 
                 WHERE id = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssii', $surname, $forename, $regiment, $unit, $memorial, $memorial_info, $postcode, $district, $photo, $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship.php?error=Error updating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "DELETE FROM township WHERE id = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship.php?error=Error deleting record: " . $mysqli->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminTownship.php");
    exit();
}
?>