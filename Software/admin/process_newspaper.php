<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\admin\process_newspaper.php -->
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
        $rank = $_POST['rank'] ?? '';
        $address = $_POST['address'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $article_description = $_POST['article_description'] ?? '';
        $newspaper_name = $_POST['newspaper_name'] ?? '';
        $paper_date = $_POST['paper_date'] ?? '';
        $page_col = $_POST['page_col'] ?? '';
        $photo_incl = $_POST['photo_incl'] ?? '0';
        
        // Prepare and execute the query with prepared statements
        $query = "INSERT INTO newspapers (Surname, Forename, Rank, Address, Regiment, Unit, `Article Description`, 
                  `Newspaper Name`, `Paper Date`, `Page/Col`, `Photo incl.`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssi', $surname, $forename, $rank, $address, $regiment, $unit, 
                          $article_description, $newspaper_name, $paper_date, $page_col, $photo_incl);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error creating record: " . $mysqli->error);
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
        $photo_incl = $_POST['photo_incl'] ?? '0';
        
        // Prepare and execute the query with prepared statements
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
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssii', $surname, $forename, $rank, $address, $regiment, $unit, 
                          $article_description, $newspaper_name, $paper_date, $page_col, $photo_incl, $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error updating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "DELETE FROM newspapers WHERE NewspaperID = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminNewspaper.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminNewspaper.php?error=Error deleting record: " . $mysqli->error);
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminNewspaper.php");
    exit();
}
?>