<?php
// connect to the database
require '../db_connect.php';

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new record
    if ($action == 'create') {
        // Get all the fields from the form
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $address = $_POST['address'] ?? '';
        $electoral_ward = $_POST['electoral_ward'] ?? '';
        $town = $_POST['town'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $battalion = $_POST['battalion'] ?? '';
        $company = $_POST['company'] ?? '';
        $age = $_POST['age'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $other_regiment = $_POST['other_regiment'] ?? '';
        $other_battalion = $_POST['other_battalion'] ?? '';
        $other_service_no = $_POST['other_service_no'] ?? '';
        $medals = $_POST['medals'] ?? '';
        $enlistment_date = $_POST['enlistment_date'] ?? '';
        $discharge_date = $_POST['discharge_date'] ?? '';
        $death_service_date = $_POST['death_service_date'] ?? '';
        $misc_info_nroh = $_POST['misc_info_nroh'] ?? '';
        $cemetery_memorial = $_POST['cemetery_memorial'] ?? '';
        $cemetery_memorial_ref = $_POST['cemetery_memorial_ref'] ?? '';
        $cemetery_memorial_country = $_POST['cemetery_memorial_country'] ?? '';
        $additional_cwcg_info = $_POST['additional_cwcg_info'] ?? '';
        
        // Create query - use prepared statement
        $query = "INSERT INTO township (
            Surname, 
            Forename, 
            Address, 
            `Electoral Ward`, 
            Town, 
            Rank, 
            Regiment, 
            Battalion, 
            Company, 
            Age, 
            `Service No`, 
            `Other Regiment`, 
            `Other Battalion`, 
            `Other Service No.`, 
            Medals, 
            `Enlistment Date`, 
            `Discharge Date`, 
            `Death (in service) Date`, 
            `Misc Info Nroh`, 
            `Cemetery/Memorial`, 
            `Cemetery/Memorial Ref`, 
            `Cemetery/Memorial Country`, 
            `Additional CWCG Info`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("sssssssssssssssssssssss", 
            $surname, 
            $forename, 
            $address, 
            $electoral_ward, 
            $town, 
            $rank, 
            $regiment, 
            $battalion, 
            $company, 
            $age, 
            $service_no, 
            $other_regiment, 
            $other_battalion, 
            $other_service_no, 
            $medals, 
            $enlistment_date, 
            $discharge_date, 
            $death_service_date, 
            $misc_info_nroh, 
            $cemetery_memorial, 
            $cemetery_memorial_ref, 
            $cemetery_memorial_country, 
            $additional_cwcg_info
        );
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship2.php?msg=Record created successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship2.php?error=Error creating record: " . $stmt->error);
            exit();
        }
    }
    
    // Edit an existing record
    else if ($action == 'edit') {
        $record_id = $_POST['record_id'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $forename = $_POST['forename'] ?? '';
        $address = $_POST['address'] ?? '';
        $electoral_ward = $_POST['electoral_ward'] ?? '';
        $town = $_POST['town'] ?? '';
        $rank = $_POST['rank'] ?? '';
        $regiment = $_POST['regiment'] ?? '';
        $battalion = $_POST['battalion'] ?? '';
        $company = $_POST['company'] ?? '';
        $age = $_POST['age'] ?? '';
        $service_no = $_POST['service_no'] ?? '';
        $other_regiment = $_POST['other_regiment'] ?? '';
        $other_battalion = $_POST['other_battalion'] ?? '';
        $other_service_no = $_POST['other_service_no'] ?? '';
        $medals = $_POST['medals'] ?? '';
        $enlistment_date = $_POST['enlistment_date'] ?? '';
        $discharge_date = $_POST['discharge_date'] ?? '';
        $death_service_date = $_POST['death_service_date'] ?? '';
        $misc_info_nroh = $_POST['misc_info_nroh'] ?? '';
        $cemetery_memorial = $_POST['cemetery_memorial'] ?? '';
        $cemetery_memorial_ref = $_POST['cemetery_memorial_ref'] ?? '';
        $cemetery_memorial_country = $_POST['cemetery_memorial_country'] ?? '';
        $additional_cwcg_info = $_POST['additional_cwcg_info'] ?? '';
        
        // Update query
        $query = "UPDATE township SET 
            Surname = ?, 
            Forename = ?, 
            Address = ?, 
            `Electoral Ward` = ?, 
            Town = ?, 
            Rank = ?, 
            Regiment = ?, 
            Battalion = ?, 
            Company = ?, 
            Age = ?, 
            `Service No` = ?, 
            `Other Regiment` = ?, 
            `Other Battalion` = ?, 
            `Other Service No.` = ?, 
            Medals = ?, 
            `Enlistment Date` = ?, 
            `Discharge Date` = ?, 
            `Death (in service) Date` = ?, 
            `Misc Info Nroh` = ?, 
            `Cemetery/Memorial` = ?, 
            `Cemetery/Memorial Ref` = ?, 
            `Cemetery/Memorial Country` = ?, 
            `Additional CWCG Info` = ?
            WHERE HonourID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("sssssssssssssssssssssssi", 
            $surname, 
            $forename, 
            $address, 
            $electoral_ward, 
            $town, 
            $rank, 
            $regiment, 
            $battalion, 
            $company, 
            $age, 
            $service_no, 
            $other_regiment, 
            $other_battalion, 
            $other_service_no, 
            $medals, 
            $enlistment_date, 
            $discharge_date, 
            $death_service_date, 
            $misc_info_nroh, 
            $cemetery_memorial, 
            $cemetery_memorial_ref, 
            $cemetery_memorial_country, 
            $additional_cwcg_info, 
            $record_id
        );
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship2.php?msg=Record updated successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship2.php?error=Error updating record: " . $stmt->error);
            exit();
        }
    }
    
    // Delete a record
    else if ($action == 'delete') {
        $record_id = $_POST['record_id'] ?? '';
        
        // Delete query
        $query = "DELETE FROM township WHERE HonourID = ?";
        
        // Prepare statement
        $stmt = $mysqli->prepare($query);
        
        // Bind parameter
        $stmt->bind_param("i", $record_id);
        
        // Execute query
        if ($stmt->execute()) {
            // Redirect back to the page with success message
            header("Location: AdminTownship2.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: AdminTownship2.php?error=Error deleting record: " . $stmt->error);
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
                    $address = $mysqli->real_escape_string($data[2]);
                    $electoral_ward = $mysqli->real_escape_string($data[3]);
                    $town = $mysqli->real_escape_string($data[4]);
                    $rank = $mysqli->real_escape_string($data[5]);
                    $regiment = $mysqli->real_escape_string($data[6]);
                    $battalion = $mysqli->real_escape_string($data[7]);
                    $company = $mysqli->real_escape_string($data[8]);
                    $age = $mysqli->real_escape_string($data[9]);
                    $service_no = $mysqli->real_escape_string($data[10]);
                    $other_regiment = $mysqli->real_escape_string($data[11]);
                    $other_battalion = $mysqli->real_escape_string($data[12]);
                    $other_service_no = $mysqli->real_escape_string($data[13]);
                    $medals = $mysqli->real_escape_string($data[14]);
                    $enlistment_date = $mysqli->real_escape_string($data[15]);
                    $discharge_date = $mysqli->real_escape_string($data[16]);
                    $death_service_date = $mysqli->real_escape_string($data[17]);
                    $misc_info_nroh = $mysqli->real_escape_string($data[18]);
                    $cemetery_memorial = $mysqli->real_escape_string($data[19]);
                    $cemetery_memorial_ref = $mysqli->real_escape_string($data[20]);
                    $cemetery_memorial_country = $mysqli->real_escape_string($data[21]);
                    $additional_cwcg_info = $mysqli->real_escape_string($data[22]);
    
                    // Insert or update the record
                    $query = "INSERT INTO township (Surname, Forename, Address, `Electoral Ward`, Town, Rank, Regiment, Battalion, Company, Age, `Service No`, `Other Regiment`, `Other Battalion`, `Other Service No.`, Medals, `Enlistment Date`, `Discharge Date`, `Death (in service) Date`, `Misc Info Nroh`, `Cemetery/Memorial`, `Cemetery/Memorial Ref`, `Cemetery/Memorial Country`, `Additional CWCG Info`)
                              VALUES ('$surname', '$forename', '$address', '$electoral_ward', '$town', '$rank', '$regiment', '$battalion', '$company', '$age', '$service_no', '$other_regiment', '$other_battalion', '$other_service_no', '$medals', '$enlistment_date', '$discharge_date', '$death_service_date', '$misc_info_nroh', '$cemetery_memorial', '$cemetery_memorial_ref', '$cemetery_memorial_country', '$additional_cwcg_info')
                              ON DUPLICATE KEY UPDATE
                              Surname = VALUES(Surname),
                              Forename = VALUES(Forename),
                              Address = VALUES(Address),
                              `Electoral Ward` = VALUES(`Electoral Ward`),
                              Town = VALUES(Town),
                              Rank = VALUES(Rank),
                              Regiment = VALUES(Regiment),
                              Battalion = VALUES(Battalion),
                              Company = VALUES(Company),
                              Age = VALUES(Age),
                              `Service No` = VALUES(`Service No`),
                              `Other Regiment` = VALUES(`Other Regiment`),
                              `Other Battalion` = VALUES(`Other Battalion`),
                              `Other Service No.` = VALUES(`Other Service No.`),
                              Medals = VALUES(Medals),
                              `Enlistment Date` = VALUES(`Enlistment Date`),
                              `Discharge Date` = VALUES(`Discharge Date`),
                              `Death (in service) Date` = VALUES(`Death (in service) Date`),
                              `Misc Info Nroh` = VALUES(`Misc Info Nroh`),
                              `Cemetery/Memorial` = VALUES(`Cemetery/Memorial`),
                              `Cemetery/Memorial Ref` = VALUES(`Cemetery/Memorial Ref`),
                              `Cemetery/Memorial Country` = VALUES(`Cemetery/Memorial Country`),
                              `Additional CWCG Info` = VALUES(`Additional CWCG Info`)";
                    $mysqli->query($query);
                }
    
                fclose($handle);
    
                // Redirect back with success message
                header("Location: AdminTownship2.php?msg=CSV file processed successfully");
                exit();
            } else {
                // Redirect back with error message
                header("Location: AdminTownship2.php?error=Error opening CSV file.");
                exit();
            }
        } else {
            // Redirect back with error message
            header("Location: AdminTownship2.php?error=No file uploaded or file upload error.");
            exit();
        }
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminTownship2.php");
    exit();
}
?>