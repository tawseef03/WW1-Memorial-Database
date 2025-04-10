<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Access database
$hostname = "localhost";
$username = "root";
$dbname = "ww1_db";
$password = ""; 

// Create connection
$mysqli = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if($mysqli->connect_errno){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

return $mysqli;
?>