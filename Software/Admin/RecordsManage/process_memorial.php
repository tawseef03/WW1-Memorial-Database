<?php
// Include the database connection
require '../db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $unit = $_POST['unit'] ?? '';
        $cemetery = $_POST['cemetery'] ?? '';
        $cemetery_ref = $_POST['cemetery_ref'] ?? '';
        $cemetery_country = $_POST['cemetery_country'] ?? '';
        $memorial = $_POST['memorial'] ?? '';
        $memorial_location = $_POST['memorial_location'] ?? '';
        $memorial_info = $_POST['memorial_info'] ?? '';
        $memorial_postcode = $_POST['memorial_postcode'] ?? '';
        $district = $_POST['district'] ?? '';
        $photo = $_POST['photo'] ?? '0';
        
        // Prepare and execute the query with prepared statements
        $query = "INSERT INTO memorials (Surname, Forename, Regiment, Unit, `Cemetery/Memorial`, `Cemetery/Grave Ref.`, 
                  `Cemetery / Memorial Country`, Memorial, `Memorial Location`, `Memorial Info`, 
                  `Memorial Postcode`, District, `Photo available`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssssi', $surname, $forename, $regiment, $unit, $cemetery, $cemetery_ref, 
                          $cemetery_country, $memorial, $memorial_location, $memorial_info, 
                          $memorial_postcode, $district, $photo);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminMemorial.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminMemorial.php?error=Error creating record: " . $mysqli->error);
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
        $cemetery = $_POST['cemetery'] ?? '';
        $cemetery_ref = $_POST['cemetery_ref'] ?? '';
        $cemetery_country = $_POST['cemetery_country'] ?? '';
        $memorial = $_POST['memorial'] ?? '';
        $memorial_location = $_POST['memorial_location'] ?? '';
        $memorial_info = $_POST['memorial_info'] ?? '';
        $memorial_postcode = $_POST['memorial_postcode'] ?? '';
        $district = $_POST['district'] ?? '';
        $photo = $_POST['photo'] ?? '0';
        
        // Prepare and execute the query with prepared statements
        $query = "UPDATE memorials SET 
                 Surname = ?, 
                 Forename = ?, 
                 Regiment = ?, 
                 Unit = ?, 
                 `Cemetery/Memorial` = ?, 
                 `Cemetery/Grave Ref.` = ?, 
                 `Cemetery / Memorial Country` = ?, 
                 Memorial = ?, 
                 `Memorial Location` = ?, 
                 `Memorial Info` = ?, 
                 `Memorial Postcode` = ?, 
                 District = ?, 
                 `Photo available` = ? 
                 WHERE MemorialID = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssssii', $surname, $forename, $regiment, $unit, $cemetery, $cemetery_ref, 
                          $cemetery_country, $memorial, $memorial_location, $memorial_info, 
                          $memorial_postcode, $district, $photo, $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminMemorial.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminMemorial.php?error=Error updating record: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Prepare and execute the query with prepared statements
        $query = "DELETE FROM memorials WHERE MemorialID = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminMemorial.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminMemorial.php?error=Error deleting record: " . $mysqli->error);
            exit();
        }
    } else if ($action == 'upload_csv') {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    
            // Open the CSV file
            if (($handle = fopen($fileTmpPath, 'r')) !== false) {
                // Skip the header row
                fgetcsv($handle);
    
                // Process each row
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $surname = $mysqli->real_escape_string($data[0]);
                    $forename = $mysqli->real_escape_string($data[1]);
                    $regiment = $mysqli->real_escape_string($data[2]);
                    $memorial = $mysqli->real_escape_string($data[3]);
                    $district = $mysqli->real_escape_string($data[4]);
    
                    // Insert or update the record
                    $query = "INSERT INTO memorials (Surname, Forename, Regiment, Memorial, District)
                              VALUES ('$surname', '$forename', '$regiment', '$memorial', '$district')
                              ON DUPLICATE KEY UPDATE
                              Surname = VALUES(Surname),
                              Forename = VALUES(Forename),
                              Regiment = VALUES(Regiment),
                              Memorial = VALUES(Memorial),
                              District = VALUES(District)";
                    $mysqli->query($query);
                }
    
                fclose($handle);
    
                // Redirect back with success message
                header("Location: AdminMemorial.php?msg=CSV file processed successfully");
                exit();
            } else {
                // Redirect back with error message
                header("Location: AdminMemorial.php?error=Error opening CSV file.");
                exit();
            }
        } else {
            // Redirect back with error message
            header("Location: AdminMemorial.php?error=No file uploaded or file upload error.");
            exit();
        }
    }
}
 else {
    // If not a POST request, redirect to the main page
    header("Location: AdminMemorial.php");
    exit();
}
?>