<?php
// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

class AdminTester
{
    private $mysqli;
    private $testsPassed = 0;
    private $testsFailed = 0;
    private $failedTests = [];
    private $testMessages = []; // Store test messages
    
    public function __construct()
    {
        // Connect to the test database
        $this->mysqli = new mysqli('localhost', 'root', '', 'ww1_test');
        
        // Check connection
        if ($this->mysqli->connect_error) {
            die("Database connection failed: " . $this->mysqli->connect_error);
        }
        
        $this->testMessages[] = "Connected to ww1_test database successfully.";
    }
    
    public function runAllTests()
    {
        $this->testMessages[] = "Starting tests...";
        $this->testMessages[] = "-------------------------";
        
        // Run each test with proper setup and teardown
        $this->setupTestTable();
        $this->testCreateRecord();
        $this->cleanupTestTable();
        
        $this->setupTestTable();
        $this->testEditRecord();
        $this->cleanupTestTable();
        
        $this->setupTestTable();
        $this->testDeleteRecord();
        $this->cleanupTestTable();
        
        $this->setupTestTable();
        $this->testInvalidCreateRecord();
        $this->cleanupTestTable();
        
        $this->setupTestTable();
        $this->testInvalidEditRecord();
        $this->cleanupTestTable();
        
        $this->setupTestTable();
        $this->testInvalidDeleteRecord();
        $this->cleanupTestTable();
        
        // Display test results
        $this->testMessages[] = "-------------------------";
        $this->testMessages[] = "Tests Completed: " . ($this->testsPassed + $this->testsFailed);
        $this->testMessages[] = "Tests Passed: " . $this->testsPassed;
        $this->testMessages[] = "Tests Failed: " . $this->testsFailed;
        
        if ($this->testsFailed > 0) {
            $this->testMessages[] = "\nFailed Tests:";
            foreach ($this->failedTests as $test) {
                $this->testMessages[] = "- $test";
            }
        }
        
        // Store results in session
        $_SESSION['test_results'] = $this->testMessages;
        $_SESSION['tests_passed'] = $this->testsPassed;
        $_SESSION['tests_failed'] = $this->testsFailed;
        $_SESSION['failed_tests'] = $this->failedTests;
        
        // Close the database connection
        if ($this->mysqli) {
            $this->mysqli->close();
            $this->testMessages[] = "\nDatabase connection closed.";
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
    
    private function assertEquals($expected, $actual, $message)
    {
        return $this->assert($expected === $actual, "$message (Expected: $expected, Got: $actual)");
    }
    
    private function setupTestTable()
    {
        // Clear existing data
        $this->mysqli->query("TRUNCATE TABLE admintest");
        
        // Insert some test data
        $this->mysqli->query("INSERT INTO admintest 
            (BiographyID, Surname, Forename, Regiment, `Service No`, Biography) 
            VALUES (1, 'Smith', 'John', 'Royal Infantry', 'SI12345', 'http://example.com/smith')");
    }
    
    private function cleanupTestTable()
    {
        // Clean up test data
        $this->mysqli->query("TRUNCATE TABLE admintest");
    }
    
    // Test creating a new record
    public function testCreateRecord()
    {
        $this->testMessages[] = "\nRunning testCreateRecord...";
        
        // Arrange - Setup test data
        $testData = [
            'action' => 'create',
            'surname' => 'Doe',
            'forename' => 'Jane',
            'regiment' => 'Royal Navy',
            'service_no' => 'RN67890',
            'biography_link' => 'http://example.com/doe'
        ];
        
        // Act - Call the function to create a record
        $result = $this->createRecord($testData);
        
        // Assert - Check that the record was created successfully
        $this->assert($result, "Create record should return true");
        
        // Check if the record exists in the database
        $query = "SELECT * FROM admintest WHERE Surname = 'Doe' AND Forename = 'Jane'";
        $result = $this->mysqli->query($query);
        $this->assertEquals(1, $result->num_rows, "Record should be created in database");
    }
    
    // Test editing an existing record
    public function testEditRecord()
    {
        $this->testMessages[] = "\nRunning testEditRecord...";
        
        // Arrange - Setup test data
        $testData = [
            'action' => 'edit',
            'record_id' => 1,
            'surname' => 'Smith',
            'forename' => 'John',
            'regiment' => 'Royal Marines', // Changed from Royal Infantry
            'service_no' => 'SI12345',
            'biography_link' => 'http://example.com/smith'
        ];
        
        // Act - Call the function to edit a record
        $result = $this->editRecord($testData);
        
        // Assert - Check that the record was updated successfully
        $this->assert($result, "Edit record should return true");
        
        // Check if the record was updated in the database
        $query = "SELECT * FROM admintest WHERE BiographyID = 1";
        $result = $this->mysqli->query($query);
        $row = $result->fetch_assoc();
        $this->assertEquals('Royal Marines', $row['Regiment'], "Regiment should be updated in database");
    }
    
    // Test deleting a record
    public function testDeleteRecord()
    {
        $this->testMessages[] = "\nRunning testDeleteRecord...";
        
        // Arrange - Setup test data
        $testData = [
            'action' => 'delete',
            'record_id' => 1
        ];
        
        // Act - Call the function to delete a record
        $result = $this->deleteRecord($testData);
        
        // Assert - Check that the record was deleted successfully
        $this->assert($result, "Delete record should return true");
        
        // Check if the record was deleted from the database
        $query = "SELECT * FROM admintest WHERE BiographyID = 1";
        $result = $this->mysqli->query($query);
        $this->assertEquals(0, $result->num_rows, "Record should be deleted from database");
    }
    
    // Test invalid input for create
    public function testInvalidCreateRecord()
    {
        $this->testMessages[] = "\nRunning testInvalidCreateRecord...";
        
        // Test with missing required field
        $testData = [
            'action' => 'create',
            'surname' => '', // Empty surname - should fail
            'forename' => 'Jane',
            'regiment' => 'Royal Navy',
            'service_no' => 'RN67890',
            'biography_link' => 'http://example.com/doe'
        ];
        
        $result = $this->createRecord($testData);
        $this->assert(!$result, "Creation should fail with empty surname");
    }
    
    // Test for invalid edit (non-existent record)
    public function testInvalidEditRecord()
    {
        $this->testMessages[] = "\nRunning testInvalidEditRecord...";
        
        // Arrange - Setup test data with non-existent ID
        $testData = [
            'action' => 'edit',
            'record_id' => 999, // ID that doesn't exist
            'surname' => 'Smith',
            'forename' => 'John',
            'regiment' => 'Royal Marines',
            'service_no' => 'SI12345',
            'biography_link' => 'http://example.com/smith'
        ];
        
        // Act - Call the function to edit a record
        $result = $this->editRecord($testData);
        
        // Assert - Edit should succeed but affect 0 rows
        $this->assert($result, "Edit with non-existent ID should return true");
        $this->assertEquals(0, $this->mysqli->affected_rows, "Edit with non-existent ID should affect 0 rows");
    }
    
    // Test for invalid delete (non-existent record)
    public function testInvalidDeleteRecord()
    {
        $this->testMessages[] = "\nRunning testInvalidDeleteRecord...";
        
        // Arrange - Setup test data with non-existent ID
        $testData = [
            'action' => 'delete',
            'record_id' => 999 // ID that doesn't exist
        ];
        
        // Act - Call the function to delete a record
        $result = $this->deleteRecord($testData);
        
        // Assert - Delete should succeed but affect 0 rows
        $this->assert($result, "Delete with non-existent ID should return true");
        $this->assertEquals(0, $this->mysqli->affected_rows, "Delete with non-existent ID should affect 0 rows");
    }
    
    // Implement the functions that interact with the database
    // These replicate the functionality in process_biographies.php
    private function createRecord($data)
    {
        // Validate input
        if (empty($data['surname']) || empty($data['forename']) || 
            empty($data['regiment']) || empty($data['service_no']) || 
            empty($data['biography_link'])) {
            return false;
        }
        
        $surname = $data['surname'];
        $forename = $data['forename'];
        $regiment = $data['regiment'];
        $service_no = $data['service_no'];
        $biography_link = $data['biography_link'];
        
        // Create query - Use prepared statements for security
        $query = "INSERT INTO admintest (Surname, Forename, Regiment, `Service No`, Biography) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('sssss', $surname, $forename, $regiment, $service_no, $biography_link);
        
        // Execute query
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    private function editRecord($data)
    {
        // Validate input
        if (empty($data['record_id']) || empty($data['surname']) || empty($data['forename']) || 
            empty($data['regiment']) || empty($data['service_no']) || 
            empty($data['biography_link'])) {
            return false;
        }
        
        $record_id = $data['record_id'];
        $surname = $data['surname'];
        $forename = $data['forename'];
        $regiment = $data['regiment'];
        $service_no = $data['service_no'];
        $biography_link = $data['biography_link'];
        
        // Update query - Use prepared statements for security
        $query = "UPDATE admintest SET 
                 Surname = ?, 
                 Forename = ?, 
                 Regiment = ?, 
                 `Service No` = ?, 
                 Biography = ? 
                 WHERE BiographyID = ?";
        
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('sssssi', $surname, $forename, $regiment, $service_no, $biography_link, $record_id);
        
        // Execute query
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    private function deleteRecord($data)
    {
        // Validate input
        if (empty($data['record_id'])) {
            return false;
        }
        
        $record_id = $data['record_id'];
        
        // Delete query - Use prepared statements for security
        $query = "DELETE FROM admintest WHERE BiographyID = ?";
        
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $record_id);
        
        // Execute query
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}

// Run the tests automatically when the script is executed
$tester = new AdminTester();
$tester->runAllTests();
?>