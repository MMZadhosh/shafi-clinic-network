<?php
// Include Jalali calendar conversion utility
include 'jalali_calendar.php';

// Database configuration
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "mydatabase";

// Establish database connection
$databaseConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check database connection
if ($databaseConnection->connect_error) {
    die("Database connection failed: " . $databaseConnection->connect_error);
}

// Get today's Gregorian date for database queries
$todayGregorianDate = date('Y-m-d');

// Get today's Jalali date for display
$todayJalaliDate = get_today_jalali_date();

// Start transaction for fetching and updating daily turn number
$databaseConnection->begin_transaction();

try {
    // Lock the record to prevent concurrent access
    $queryLockRecord = "SELECT last_turn_number FROM daily_turn_counter WHERE turn_date = ? FOR UPDATE";
    $stmtLockRecord = $databaseConnection->prepare($queryLockRecord);
    $stmtLockRecord->bind_param("s", $todayGregorianDate);
    $stmtLockRecord->execute();
    $resultLockRecord = $stmtLockRecord->get_result();

    $nextTurnNumber = 1;
    if ($resultLockRecord->num_rows > 0) {
        $row = $resultLockRecord->fetch_assoc();
        $nextTurnNumber = $row['last_turn_number'] + 1;

        // Update the turn number in the database
        $queryUpdateTurn = "UPDATE daily_turn_counter SET last_turn_number = ? WHERE turn_date = ?";
        $stmtUpdateTurn = $databaseConnection->prepare($queryUpdateTurn);
        $stmtUpdateTurn->bind_param("is", $nextTurnNumber, $todayGregorianDate);
        $stmtUpdateTurn->execute();
    } else {
        // Insert a new record if no entry exists for today
        $queryInsertTurn = "INSERT INTO daily_turn_counter (turn_date, last_turn_number) VALUES (?, ?)";
        $stmtInsertTurn = $databaseConnection->prepare($queryInsertTurn);
        $stmtInsertTurn->bind_param("si", $todayGregorianDate, $nextTurnNumber);
        $stmtInsertTurn->execute();
    }

    // Commit the transaction
    $databaseConnection->commit();
} catch (Exception $e) {
    // Rollback transaction in case of an error
    $databaseConnection->rollback();
    die("Error processing turn number: " . $e->getMessage());
} finally {
    // Close prepared statements and database connection
    $stmtLockRecord->close();
    if (isset($stmtUpdateTurn)) {
        $stmtUpdateTurn->close();
    }
    if (isset($stmtInsertTurn)) {
        $stmtInsertTurn->close();
    }
    $databaseConnection->close();
}
?>