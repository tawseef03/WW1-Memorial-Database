
<?php
session_start();


public function runAllTests()
{
    $output = [];
    $output[] = "Starting tests...";
    $output[] = "-------------------------";
    
    // Run the tests
    
    $output[] = "-------------------------";
    $output[] = "Tests Completed: " . ($this->testsPassed + $this->testsFailed);
    $output[] = "Tests Passed: " . $this->testsPassed;
    $output[] = "Tests Failed: " . $this->testsFailed;
    
    if ($this->testsFailed > 0) {
        $output[] = "\nFailed Tests:";
        foreach ($this->failedTests as $test) {
            $output[] = "- $test";
        }
    }
    
    // Store results in session
    $_SESSION['test_results'] = $output;
    $_SESSION['tests_passed'] = $this->testsPassed;
    $_SESSION['tests_failed'] = $this->testsFailed;
    $_SESSION['failed_tests'] = $this->failedTests;
    
    // Close the database connection
    if ($this->mysqli) {
        $this->mysqli->close();
    }
    
    // Redirect to results page
    header("Location: AdminTestResults.php");
    exit;
}


private function assert($condition, $message)
{
    if ($condition) {
        $this->testsPassed++;
        
        $this->testMessages[] = "✓ PASS: $message";
        return true;
    } else {
        $this->testsFailed++;
        $this->failedTests[] = $message;
        $this->testMessages[] = "✗ FAIL: $message";
        return false;
    }
}