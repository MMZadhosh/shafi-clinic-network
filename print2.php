<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// Sanitize and validate input data
	$customerName = htmlspecialchars(trim($_POST['customer_name']));
	$serviceTime = htmlspecialchars(trim($_POST['service_time'])); // Time received as a string
	$paymentAmount = htmlspecialchars(trim($_POST['payment_amount']));
	$currentDate = date("Y/m/d");

	// Output the receipt in Persian (RTL layout)
	echo "<!DOCTYPE html>";
	echo "<html lang='fa'>";
	echo "<head><meta charset='UTF-8'><title>رسید پرداخت</title></head>";
	echo "<body>";
	echo "<div style='text-align: right; direction: rtl;'>";
	echo "<p style='float: right;'>نام: $customerName</p>";
	echo "<p style='float: left;'>تاریخ: $currentDate</p>";
	echo "<div style='clear: both;'></div>";
	echo "<p>گواهی می‌شود مبلغ $paymentAmount تومان بابت وصل سرم و تزریق آمپول دریافت شد.</p>";
	echo "<br><br><br><br><br>";
	echo "</div>";
	echo "<script>window.print();</script>";
	echo "</body>";
	echo "</html>";
}
?>