<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Access database
$hostname = "localhost";
$username = "root";
$dbname = "ww1_db";
<<<<<<< Updated upstream
$password = ""; 
=======
$password = ""; // Typical default for XAMPP, adjust if different
>>>>>>> Stashed changes

// Create connection
$mysqli = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if($mysqli->connect_errno){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

<<<<<<< Updated upstream
=======
// Optional: Add a print statement to verify connection
// echo "Database connection successful!";

>>>>>>> Stashed changes
return $mysqli;
?>