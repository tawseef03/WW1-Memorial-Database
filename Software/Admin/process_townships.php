<?php
// connect to the database
require 'db_connect.php';

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
    }
} else {
    // If not a POST request, redirect to the main page
    header("Location: AdminTownship2.php");
    exit();
}
?>