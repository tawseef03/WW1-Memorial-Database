<?php
// connect to the database
require 'db_connect.php';

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // Create a new user
    if ($action == 'create') {
        $username = $_POST['username'] ?? '';
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (Username, Password) VALUES ('$username', '$password')";
        
        if ($mysqli->query($query)) {
            header("Location: AdminUsers.php?msg=User created successfully");
            exit();
        } else {
            header("Location: AdminUsers.php?error=Error creating user: " . $mysqli->error);
            exit();
        }
    }
    
    // Edit an existing user
    else if ($action == 'edit') {
        $user_id = $_POST['user_id'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        
        $query = "UPDATE users SET Username = '$username', Password = '$password' WHERE UserID = $user_id";
        
        if ($mysqli->query($query)) {
            header("Location: AdminUsers.php?msg=User updated successfully");
            exit();
        } else {
            header("Location: AdminUsers.php?error=Error updating user: " . $mysqli->error);
            exit();
        }
    }
    
    // Delete a user
    else if ($action == 'delete') {
        $user_id = $_POST['user_id'] ?? '';
        
        $query = "DELETE FROM users WHERE UserID = $user_id";
        
        if ($mysqli->query($query)) {
            header("Location: AdminUsers.php?msg=User deleted successfully");
            exit();
        } else {
            header("Location: AdminUsers.php?error=Error deleting user: " . $mysqli->error);
            exit();
        }
    }
} else {
    header("Location: AdminUsers.php");
    exit();
}
?>