<?php
// Database configuration (use environment variables for security)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';
$dbName = getenv('DB_NAME') ?: 'mydatabase';

// Establish database connection
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check database connection
if ($conn->connect_error) {
	die("Database connection failed: " . $conn->connect_error);
}

// Get today's date
$currentDate = date('Y-m-d');

// SQL query to reset or insert the daily turn counter
$query = "INSERT INTO daily_turn_counter (`turn_date`, `last_turn_number`)
		  VALUES (?, 0)
		  ON DUPLICATE KEY UPDATE last_turn_number = 0";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
if ($stmt) {
	$stmt->bind_param('s', $currentDate);
	if ($stmt->execute()) {
		echo "Daily turn counter for today ($currentDate) has been successfully reset or created.\n";
	} else {
		echo "Error resetting/creating daily turn counter: " . $stmt->error . "\n";
	}
	$stmt->close();
} else {
	echo "Error preparing the SQL statement: " . $conn->error . "\n";
}

// Close the database connection
$conn->close();
?>