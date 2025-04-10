<?php

function assertEquals($input, $expect) {
    if ($input == $expect) {
        echo "<p style='background-color: #77ff77;'>PASS</p>"; 
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertEquals expected " . $expect . " but got " . $input;
        return false;
    }
}

function assertNotEquals($input, $expect) {
    if ($input != $expect) {
        echo "<p style='background-color: #77ff77;'>PASS</p>";
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertNotEquals got " . $input . " unexpectedly";
        return false;
    }
}

function assertTrue($input) {
    if ($input == true) {
        echo "<p style='background-color: #77ff77;'>PASS</p>"; 
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertTrue expected true but got " . $input;
        return false;
    }
}

function assertFalse($input) {
    if ($input == false) {
        echo "<p style='background-color: #77ff77;'>PASS</p>"; 
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertFalse expected false but got " . $input;
        return false;
    }
}

function assertNull($input) {
    if (is_null($input)) {
        echo "<p style='background-color: #77ff77;'>PASS</p>"; 
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertNull expected true but got " . is_null($input);
        return false;
    }
}

function assertNotNull($input) {
    if (!is_null($input)) {
        echo "<p style='background-color: #77ff77;'>PASS</p>"; 
        return true;
    } else {
        echo "<p style='background-color: #ff7777;'>FAIL:</p> assertNotNull expected true but got " . is_null($input);
        return false;
    }
}

?>