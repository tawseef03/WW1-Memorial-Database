<?php
session_start();

// Check if test results exist
if (!isset($_SESSION['test_results'])) {
    echo "No test results found. Please run the tests first.";
    exit;
}

// Get results from session
$results = $_SESSION['test_results'];
$testsPassed = $_SESSION['tests_passed'];
$testsFailed = $_SESSION['tests_failed'];
$failedTests = isset($_SESSION['failed_tests']) ? $_SESSION['failed_tests'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Test Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .failure {
            color: red;
            font-weight: bold;
        }
        .results {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-line;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .summary {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.2em;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            text-align: center;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .run-again {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .run-again:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Admin Test Results</h1>
    
    <div class="results">
        <?php 
        foreach ($results as $line) {
            if (strpos($line, "PASS") !== false) {
                echo '<div class="success">' . htmlspecialchars($line) . '</div>';
            } else if (strpos($line, "FAIL") !== false) {
                echo '<div class="failure">' . htmlspecialchars($line) . '</div>';
            } else {
                echo htmlspecialchars($line) . '<br>';
            }
        }
        ?>
    </div>
    
    <div class="summary">
        <div class="<?php echo ($testsFailed == 0) ? 'success' : 'failure'; ?>">
            Tests Passed: <?php echo $testsPassed; ?> / <?php echo $testsPassed + $testsFailed; ?>
        </div>
    </div>
    
    <a href="AdminTest3.php" class="run-again">Run Tests Again</a>
</body>
</html>