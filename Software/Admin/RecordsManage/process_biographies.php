<?php
// connect to the database
require '../db_connect.php';

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $service_no = $_POST['service_no'] ?? '';

        // Handle file upload
        if (isset($_FILES['biography_file']) && $_FILES['biography_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Files/biographies/';
            $fileName = basename($_FILES['biography_file']['name']);
            $filePath = $uploadDir . $fileName;

            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['biography_file']['tmp_name'], $filePath)) {
                // Save the relative file path to the database
                $relativePath = '../../Files/biographies/' . $fileName;

                // Create query
                $query = "INSERT INTO biographyinfo (Surname, Forename, Regiment, `Service No`, Biography) 
                        VALUES ('$surname', '$forename', '$regiment', '$service_no', '$relativePath')";

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
            } else {
                // Redirect back with error message if file upload fails
                header("Location: AdminBiographies.php?error=Error uploading file.");
                exit();
            }
        } else {
            // Redirect back with error message if no file is uploaded
            header("Location: AdminBiographies.php?error=No file uploaded or file upload error.");
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