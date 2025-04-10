<?php
// connect to the database
require 'db_connect.php';

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $biography_link = $_POST['biography_link'] ?? '';
        
        // Create query
        $query = "INSERT INTO biographyinfo (Surname, Forename, Regiment, `Service No`, Biography) 
                 VALUES ('$surname', '$forename', '$regiment', '$service_no', '$biography_link')";
        
        // Execute query
        if ($mysqli->query($query)) {
            // Redirect back to the page with success message
            header("Location: AdminBiographies.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBiographies.php?error=Error creating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $biography_link = $_POST['biography_link'] ?? '';
        
        // Update query
        $query = "UPDATE biographyinfo SET 
                 Surname = '$surname', 
                 Forename = '$forename', 
                 Regiment = '$regiment', 
                 `Service No` = '$service_no', 
                 Biography = '$biography_link' 
                 WHERE BiographyID = $record_id";
        
        // Execute query
        if ($mysqli->query($query)) {
            // Redirect back to the page with success message
            header("Location: AdminBiographies.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBiographies.php?error=Error updating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Delete query
        $query = "DELETE FROM biographyinfo WHERE BiographyID = $record_id";
        
        // Execute query
        if ($mysqli->query($query)) {
            // Redirect back to the page with success message
            header("Location: AdminBiographies.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBiographies.php?error=Error deleting record: " . $mysqli->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminBiographies.php");
    exit();
}
?>