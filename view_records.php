<?php
// Database configuration (use environment variables for security)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';
$dbName = getenv('DB_NAME') ?: 'mydatabase';

// Establish database connection
$databaseConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($databaseConnection->connect_error) {
	die("Database connection failed: " . $databaseConnection->connect_error);
}

// Retrieve and sanitize input
$startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
$startTime = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
$endDate = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
$endTime = isset($_POST['end_time']) ? trim($_POST['end_time']) : '';

if ($startDate && $startTime && $endDate && $endTime) {
	// Combine and format date and time
	$startDateTime = (new DateTime("$startDate $startTime"))->format('Y-m-d H:i:s');
	$endDateTime = (new DateTime("$endDate $endTime"))->format('Y-m-d H:i:s');

	// Prepare and execute SQL queries securely
	$nurseQuery = $databaseConnection->prepare(
		"SELECT COUNT(*) AS total_records, SUM(total_price) AS total_price_sum 
		 FROM nurse_records 
		 WHERE timestamp BETWEEN ? AND ?"
	);
	$nurseQuery->bind_param('ss', $startDateTime, $endDateTime);
	$nurseQuery->execute();
	$nurseResult = $nurseQuery->get_result()->fetch_assoc();
	$totalNurseRecords = $nurseResult['total_records'];
	$totalNurseSum = $nurseResult['total_price_sum'];

	$deletedQuery = $databaseConnection->prepare(
		"SELECT 
			SUM(CASE WHEN insurance_type = 'نامشخص' THEN 1 ELSE 0 END) AS unknown_insurance_count,
			SUM(CASE WHEN insurance_type != 'نامشخص' THEN 1 ELSE 0 END) AS known_insurance_count
		 FROM deleted_records 
		 WHERE deleted_at BETWEEN ? AND ?"
	);
	$deletedQuery->bind_param('ss', $startDateTime, $endDateTime);
	$deletedQuery->execute();
	$deletedResult = $deletedQuery->get_result()->fetch_assoc();
	$unknownInsuranceCount = $deletedResult['unknown_insurance_count'];
	$knownInsuranceCount = $deletedResult['known_insurance_count'];
} else {
	echo "Please provide valid start and end dates/times.";
}

// Close the database connection
$databaseConnection->close();