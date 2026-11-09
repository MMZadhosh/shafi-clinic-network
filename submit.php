<?php
// Database connection credentials
$serverName = "localhost";
$dbUsername = "root"; // Replace with your database username
$dbPassword = ""; // Replace with your database password
$dbName = "mydatabase"; // Replace with your database name

// Establish database connection
$databaseConnection = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check database connection
if ($databaseConnection->connect_error) {
	die("Database connection failed: " . $databaseConnection->connect_error);
	exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// Validate and sanitize input
	$name = htmlspecialchars(trim($_POST['name'] ?? ''));
	$nationalCode = htmlspecialchars(trim($_POST['national_code'] ?? ''));
	$insuranceType = htmlspecialchars(trim($_POST['insurance_type'] ?? ''));
	$appointmentNumber = htmlspecialchars(trim($_POST['appointment_number'] ?? ''));
	$registrationDate = htmlspecialchars(trim($_POST['registration_date'] ?? ''));

	// Ensure all required fields are provided
	if (empty($name) || empty($nationalCode) || empty($insuranceType) || empty($appointmentNumber) || empty($registrationDate)) {
		die("All fields are required.");
		exit();
	}

	// Use prepared statements to prevent SQL injection
	$query = $databaseConnection->prepare(
		"INSERT INTO sick (name, national_code, insurance_type, appointment_number, registration_date) 
		VALUES (?, ?, ?, ?, ?)"
	);
	$query->bind_param("sssis", $name, $nationalCode, $insuranceType, $appointmentNumber, $registrationDate);

	// Execute the query and handle the result
	if ($query->execute()) {
		// Redirect to confirmation page
		$redirectUrl = "https://example.com/print_turn.php"; // Replace with your actual URL
		header("Location: $redirectUrl?name=" . urlencode($name) . "&turn=" . urlencode($appointmentNumber) . "&date=" . urlencode($registrationDate));
		exit();
	} else {
		echo "Error saving data: " . $query->error;
	}

	// Close the prepared statement
	$query->close();
}

// Close the database connection
$databaseConnection->close();