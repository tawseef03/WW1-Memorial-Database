<!DOCTYPE html><html><head></head><body><h1>Login testing</h1>
<?php

require_once '../../php_html/db_connect.php';
require_once 'test_functions.php';
function getSql($userin) {
    try {
        $mysqli = require '../../php_html/db_connect.php';
        $sql = "SELECT * FROM users WHERE Username = ?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("SQL preparation failed: " . $mysqli->error);
        }
        $stmt->bind_param("s", $userin);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    } catch (Exception $e) {
        $error_message = "System Error: " . $e->getMessage();
        $debug_info .= "Error Info: " . $e->getMessage();
    } finally {
        if (isset($stmt) && $stmt !== false) {
            $stmt->close();
        }
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }
}

function testGuest() {
    echo "<h3>Testing 'guest' details</h3>";
    $user = getSql("guest");
    echo "<ul><li>Fetch user from database<br>";
    assertNotNull($user);
    echo "<br><li>Check correct username has been fetched<br>";
    assertEquals($user["Username"],"guest");
    echo "<br><li>Check correct password has been fetched using MD5 hash<br>";
    assertEquals($user["Password"],md5("123"));
    echo "</ul>End test";
}

function testAdmin() {
    echo "<h3>Testing 'admin' details</h3>";
    $user = getSql("admin");
    echo "<ul><li>Fetch user from database<br>";
    assertNotNull($user);
    echo "<br><li>Check correct username has been fetched<br>";
    assertEquals($user["Username"],"admin");
    echo "<br><li>Check correct password has been fetched using MD5 hash<br>";
    assertEquals($user["Password"],md5("admin123"));
    echo "</ul>End test";
}

testGuest();
testAdmin();

?>
</body></html>