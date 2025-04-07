<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\admin\process_burials.php -->
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
        $age = $_POST['age'] ?? '';
        $medals = $_POST['medals'] ?? '';
        $date_of_death = $_POST['date_of_death'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $service_number = $_POST['service_number'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $cemetery = $_POST['cemetery'] ?? '';
        $grave_reference = $_POST['grave_reference'] ?? '';
        $information = $_POST['information'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "INSERT INTO burials (Surname, Forename, Age, Medals, `Date of Death`, Rank, `Service Number`, 
                  Regiment, Unit, Cemetery, `Grave Reference`, Information) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssissssissss', $surname, $forename, $age, $medals, $date_of_death, $rank, $service_number, 
                          $regiment, $unit, $cemetery, $grave_reference, $information);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error creating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $age = $_POST['age'] ?? '';
        $medals = $_POST['medals'] ?? '';
        $date_of_death = $_POST['date_of_death'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $service_number = $_POST['service_number'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $cemetery = $_POST['cemetery'] ?? '';
        $grave_reference = $_POST['grave_reference'] ?? '';
        $information = $_POST['information'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "UPDATE burials SET 
                 Surname = ?, 
                 Forename = ?, 
                 Age = ?, 
                 Medals = ?, 
                 `Date of Death` = ?, 
                 Rank = ?, 
                 `Service Number` = ?, 
                 Regiment = ?, 
                 Unit = ?, 
                 Cemetery = ?, 
                 `Grave Reference` = ?, 
                 Information = ? 
                 WHERE BurialID = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssissssissssi', $surname, $forename, $age, $medals, $date_of_death, $rank, $service_number, 
                          $regiment, $unit, $cemetery, $grave_reference, $information, $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error updating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "DELETE FROM burials WHERE BurialID = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminBurials.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminBurials.php?error=Error deleting record: " . $mysqli->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminBurials.php");
    exit();
}
?>