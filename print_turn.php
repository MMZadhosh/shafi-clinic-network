<?php
// print_turn.php
include 'jalali_calendar.php'; // Include Jalali calendar conversion file

// Retrieve and sanitize input parameters
$patientName = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') : 'نامشخص';
$turnNumber = isset($_GET['turn']) ? htmlspecialchars($_GET['turn'], ENT_QUOTES, 'UTF-8') : 'نامشخص';
$gregorianDate = isset($_GET['date']) ? htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8') : date('Y-m-d');

// Convert Gregorian date to Jalali date
list($gregorianYear, $gregorianMonth, $gregorianDay) = explode('-', $gregorianDate);
$jalaliDateArray = gregorian_to_jalali($gregorianYear, $gregorianMonth, $gregorianDay);

// Extract Jalali day and month for display
$jalaliDay = $jalaliDateArray[2];
$jalaliMonth = $jalaliDateArray[1];

// Map Jalali month numbers to Persian month names
$jalaliMonthNames = [
    1 => "فروردین", 2 => "اردیبهشت", 3 => "خرداد", 4 => "تیر",
    5 => "مرداد", 6 => "شهریور", 7 => "مهر", 8 => "آبان",
    9 => "آذر", 10 => "دی", 11 => "بهمن", 12 => "اسفند"
];
$jalaliMonthName = $jalaliMonthNames[$jalaliMonth] ?? '';

// Format the Jalali date for display
$formattedJalaliDate = $jalaliDay . ' ' . $jalaliMonthName;
?>