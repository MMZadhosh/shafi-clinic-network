<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// Sanitize and validate input
	$userName = htmlspecialchars($_POST['user_name']);
	$appointmentTime = htmlspecialchars($_POST['appointment_time']);
	$currentDate = date("Y/m/d");

	// Generate the receipt HTML
	echo "<!DOCTYPE html>";
	echo "<html lang='fa'>";
	echo "<head><meta charset='UTF-8'><title>Receipt</title></head>";
	echo "<body>";
	echo "<div style='text-align: right;'>";
	echo "<p style='float: right;'>نام: $userName</p>";
	echo "<p style='float: left;'>تاریخ: $currentDate</p>";
	echo "<div style='clear: both;'></div>";
	echo "<p>گواهی میشود مبلغ 60000 تومان بابت ویزیت دریافت شد</p>";
	echo "<br><br><br><br><br>";
	echo "</div>";
	echo "<script>window.print();</script>";
	echo "</body>";
	echo "</html>";
}
?>