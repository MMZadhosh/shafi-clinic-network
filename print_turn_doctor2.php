<?php
// print_turn_doctor2.php
include 'jalali_calendar.php';

// Sanitize and retrieve query parameters
$nationalCode = isset($_GET['national_code']) ? htmlspecialchars($_GET['national_code']) : 'نامشخص';
$insuranceType = isset($_GET['insurance_type']) ? htmlspecialchars($_GET['insurance_type']) : 'نامشخص';
$turnNumber = isset($_GET['turn']) ? htmlspecialchars($_GET['turn']) : 'نامشخص';
$gregorianDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : date('Y-m-d');

// Convert Gregorian date to Jalali date
list($gregorianYear, $gregorianMonth, $gregorianDay) = explode('-', $gregorianDate);
$jalaliDateArray = gregorian_to_jalali($gregorianYear, $gregorianMonth, $gregorianDay);

// Jalali month names
$jalaliMonthNames = [
    1 => "فروردین", 2 => "اردیبهشت", 3 => "خرداد",
    4 => "تیر", 5 => "مرداد", 6 => "شهریور",
    7 => "مهر", 8 => "آبان", 9 => "آذر",
    10 => "دی", 11 => "بهمن", 12 => "اسفند"
];

// Format Jalali date
$jalaliDay = $jalaliDateArray[2];
$jalaliMonthName = $jalaliMonthNames[$jalaliDateArray[1]] ?? '';
$formattedJalaliDate = $jalaliDay . ' ' . $jalaliMonthName;
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>چاپ نوبت - دکتر دوم</title>
</head>
<body>
    <!-- Content goes here -->
</body>
</html>