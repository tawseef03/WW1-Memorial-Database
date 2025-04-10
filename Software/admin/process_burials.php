<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\admin\process_burials.php -->
<?php
// Include the database connection
require 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        // Get all the fields from the form
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $dob = $_POST['dob'] ?? null;
        $date_of_death = $_POST['date_of_death'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $battalion = $_POST['battalion'] ?? '';
        $cemetery = $_POST['cemetery'] ?? '';
        $grave_ref = $_POST['grave_ref'] ?? '';
        
        // Create query - use prepared statement
        $query = "INSERT INTO buried (
            Surname, 
            Forename, 
            DoB, 
            `Date of Death`, 
            Rank, 
            `Service No`, 
            Regiment, 
            Battalion, 
            Cemetary, 
            `Grave Ref`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("ssssssssss", 
            $surname, 
            $forename, 
            $dob, 
            $date_of_death, 
            $rank, 
            $service_no, 
            $regiment, 
            $battalion, 
            $cemetery, 
            $grave_ref
        );
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error creating record: " . $stmt->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $dob = $_POST['dob'] ?? null;
        $date_of_death = $_POST['date_of_death'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $battalion = $_POST['battalion'] ?? '';
        $cemetery = $_POST['cemetery'] ?? '';
        $grave_ref = $_POST['grave_ref'] ?? '';
        
        // Update query
        $query = "UPDATE buried SET 
            Surname = ?, 
            Forename = ?, 
            DoB = ?, 
            `Date of Death` = ?, 
            Rank = ?, 
            `Service No` = ?, 
            Regiment = ?, 
            Battalion = ?, 
            Cemetary = ?, 
            `Grave Ref` = ?
            WHERE BuriedID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("ssssssssssi", 
            $surname, 
            $forename, 
            $dob, 
            $date_of_death, 
            $rank, 
            $service_no, 
            $regiment, 
            $battalion, 
            $cemetery, 
            $grave_ref, 
            $record_id
        );
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error updating record: " . $stmt->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Delete query
        $query = "DELETE FROM buried WHERE BuriedID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameter
        $stmt->bind_param("i", $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error deleting record: " . $stmt->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminBurials.php");
    exit();
}
?>