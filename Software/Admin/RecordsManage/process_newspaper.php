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
        $rank = $_POST['rank'] ?? '';
        $address = $_POST['address'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $article_description = $_POST['article_description'] ?? '';
        $newspaper_name = $_POST['newspaper_name'] ?? '';
        $paper_date = $_POST['paper_date'] ?? '';
        $page_col = $_POST['page_col'] ?? '';
        $photo_incl = $_POST['photo_incl'] ?? '';
        
        // Create query - use prepared statement
        $query = "INSERT INTO newspapers (
            Surname, 
            Forename, 
            Rank, 
            Address, 
            Regiment, 
            Unit, 
            `Article Description`, 
            `Newspaper Name`, 
            `Paper Date`, 
            `Page/Col`, 
            `Photo incl.`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("sssssssssss", 
            $surname, 
            $forename, 
            $rank, 
            $address, 
            $regiment, 
            $unit, 
            $article_description, 
            $newspaper_name, 
            $paper_date, 
            $page_col, 
            $photo_incl
        );
        
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error creating record: " . $stmt->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $address = $_POST['address'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $article_description = $_POST['article_description'] ?? '';
        $newspaper_name = $_POST['newspaper_name'] ?? '';
        $paper_date = $_POST['paper_date'] ?? '';
        $page_col = $_POST['page_col'] ?? '';
        $photo_incl = $_POST['photo_incl'] ?? '';
        
        // Update query
        $query = "UPDATE newspapers SET 
        Surname = ?, 
        Forename = ?, 
        Rank = ?, 
        Address = ?, 
        Regiment = ?, 
        Unit = ?, 
        `Article Description` = ?, 
        `Newspaper Name` = ?, 
        `Paper Date` = ?, 
        `Page/Col` = ?, 
        `Photo incl.` = ?
        WHERE NewspaperID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("sssssssssssi", 
        $surname, 
        $forename, 
        $rank, 
        $address, 
        $regiment, 
        $unit, 
        $article_description, 
        $newspaper_name, 
        $paper_date, 
        $page_col, 
        $photo_incl, 
        $record_id
    );
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error updating record: " . $stmt->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Delete query
        $query = "DELETE FROM newspapers WHERE NewspaperID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameter
        $stmt->bind_param("i", $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error deleting record: " . $stmt->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminNewspaper.php");
    exit();
}
?>