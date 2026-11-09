<?php
// Include Jalali calendar utility
include 'jalali_calendar.php';

// Database connection settings (use environment variables for security)
$serverName = getenv('DB_SERVER') ?: 'localhost';
$dbUsername = getenv('DB_USERNAME') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';
$dbName = getenv('DB_NAME') ?: 'mydatabase';

// Establish database connection
$connection = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

// Get today's Gregorian date for database queries
$todayGregorian = date('Y-m-d');

// Get today's Jalali date for display
$todayJalali = get_today_jalali_date();

// Start transaction to fetch and increment turn number
$connection->begin_transaction();

try {
    // Fetch the last turn number for today's date
    $querySelectForUpdate = "SELECT last_turn_number FROM daily_turn_counter WHERE turn_date = ? FOR UPDATE";
    $stmtSelect = $connection->prepare($querySelectForUpdate);
    $stmtSelect->bind_param('s', $todayGregorian);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    $nextTurnNumber = 1;

    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $nextTurnNumber = $row['last_turn_number'] + 1;

        // Update the turn number in the database
        $queryUpdateCounter = "UPDATE daily_turn_counter SET last_turn_number = ? WHERE turn_date = ?";
        $stmtUpdate = $connection->prepare($queryUpdateCounter);
        $stmtUpdate->bind_param('is', $nextTurnNumber, $todayGregorian);
        $stmtUpdate->execute();
    } else {
        // Insert a new record if no entry exists for today's date
        $queryInsertCounter = "INSERT INTO daily_turn_counter (turn_date, last_turn_number) VALUES (?, ?)";
        $stmtInsert = $connection->prepare($queryInsertCounter);
        $stmtInsert->bind_param('si', $todayGregorian, $nextTurnNumber);
        $stmtInsert->execute();
    }

    // Commit the transaction
    $connection->commit();
} catch (Exception $e) {
    // Rollback transaction in case of error
    $connection->rollback();
    die("Error processing turn number: " . $e->getMessage());
} finally {
    // Close prepared statements and connection
    if (isset($stmtSelect)) $stmtSelect->close();
    if (isset($stmtUpdate)) $stmtUpdate->close();
    if (isset($stmtInsert)) $stmtInsert->close();
    $connection->close();
}
?>