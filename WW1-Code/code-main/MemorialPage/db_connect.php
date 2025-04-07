<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Access database
$hostname = "localhost";
$username = "root";
$dbname = "ww1_db";
$password = ""; // Typical default for XAMPP, adjust if different

// Create connection
$mysqli = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if($mysqli->connect_errno){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

// Optional: Add a print statement to verify connection
// echo "Database connection successful!";

return $mysqli;
?>