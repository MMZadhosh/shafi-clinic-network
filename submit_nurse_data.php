<?php
// Database configuration (use environment variables for security)
$servername = getenv('DB_SERVER') ?: 'localhost';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'mydatabase';

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Database connection failed: " . $conn->connect_error);
}

// Sanitize and validate POST inputs
$injectionType = filter_input(INPUT_POST, 'injection_type1', FILTER_SANITIZE_STRING);
$injectionCount = filter_input(INPUT_POST, 'injection_count1', FILTER_VALIDATE_INT);
$serumType = filter_input(INPUT_POST, 'serum_type', FILTER_SANITIZE_STRING);
$serumCount = filter_input(INPUT_POST, 'serum_count', FILTER_VALIDATE_INT);
$earringType = filter_input(INPUT_POST, 'earring_type', FILTER_SANITIZE_STRING);
$bloodPressure = isset($_POST['blood_pressure']) ? 1 : 0;
$bloodSugar = isset($_POST['blood_sugar']) ? 1 : 0;
$ekg = isset($_POST['ekg']) ? 1 : 0;
$stitchesRemoval = filter_input(INPUT_POST, 'stitches_removal', FILTER_SANITIZE_STRING);
$smallDressing = isset($_POST['small_dressing']) ? 1 : 0;
$largeDressing = isset($_POST['large_dressing']) ? 1 : 0;
$earCleaningOne = isset($_POST['ear_cleaning_one']) ? 1 : 0;
$earCleaningTwo = isset($_POST['ear_cleaning_two']) ? 1 : 0;
$oxygenTherapy = filter_input(INPUT_POST, 'oxygen_therapy', FILTER_SANITIZE_STRING);
$totalPrice = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
$timestamp = filter_input(INPUT_POST, 'timestamp', FILTER_SANITIZE_STRING);

// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO nurse_records (
	injection_type1, injection_count1, serum_type, serum_count, earring_type, 
	blood_pressure, blood_sugar, ekg, stitches_removal, small_dressing, 
	large_dressing, ear_cleaning_one, ear_cleaning_two, oxygen_therapy, 
	total_price, timestamp
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
	$stmt->bind_param(
		"sisssiiisiiiisds",
		$injectionType,
		$injectionCount,
		$serumType,
		$serumCount,
		$earringType,
		$bloodPressure,
		$bloodSugar,
		$ekg,
		$stitchesRemoval,
		$smallDressing,
		$largeDressing,
		$earCleaningOne,
		$earCleaningTwo,
		$oxygenTherapy,
		$totalPrice,
		$timestamp
	);

	if ($stmt->execute()) {
		echo "Record inserted successfully";
	} else {
		echo "Error inserting record: " . $stmt->error;
	}

	$stmt->close();
} else {
	echo "Error preparing statement: " . $conn->error;
}

// Close database connection
$conn->close();
?>