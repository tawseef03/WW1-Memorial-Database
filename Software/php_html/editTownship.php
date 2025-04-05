<!-- filepath: c:\Users\28341\Desktop\ww1code\WW1-Memorial-Database\Software\php_html\editTownship.php -->
<?php
require 'db_connect.php';

// 获取记录 ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

// 查询记录详细信息
$query = "SELECT * FROM township WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    die("Record not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Township Record</title>
    <link rel="stylesheet" href="../css/database.css">
</head>
<body>
    <div class="container">
        <h2>Edit Record</h2>
        <form action="updateTownship.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>">
            <div class="form-group">
                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($record['Surname']); ?>">
            </div>
            <div class="form-group">
                <label for="forename">Forename:</label>
                <input type="text" id="forename" name="forename" value="<?php echo htmlspecialchars($record['Forename']); ?>">
            </div>
            <div class="form-group">
                <label for="regiment">Regiment:</label>
                <input type="text" id="regiment" name="regiment" value="<?php echo htmlspecialchars($record['Regiment']); ?>">
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($record['Unit']); ?>">
            </div>
            <div class="form-group">
                <label for="memorial">Memorial:</label>
                <input type="text" id="memorial" name="memorial" value="<?php echo htmlspecialchars($record['Memorial']); ?>">
            </div>
            <div class="form-buttons">
                <button type="submit">Save</button>
                <button type="button" onclick="window.location.href='township.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>