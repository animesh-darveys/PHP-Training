<?php
date_default_timezone_set("asia/kolkata");

echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("l"). "<br>";
echo date('l, F j, Y'). "<br>";

echo date("jS-M-Y H:i:sa"). "<br>";

// mktime(hour, minute, second, month, day, year);

echo "<br>";
$timestamp = mktime(00, 00, 0, 02, 01, 2025);
echo $timestamp;
echo "<br>";

echo date("Y-M-d", $timestamp);


// echo date("Y-m-d");

// echo "<br />";

// echo date("d-m-Y H:i:s");

// echo "<br />";


// $date = strtotime("+7 days");

// echo  "Estimated Date: " . date("Y-m-d", $date);


// echo "<br />";

// $date = new DateTime("2026-08-18");

// echo $date->format("d-m-Y");  

// echo "<br />";

// $date = new DateTimeImmutable("2026-08-18");

// $newDate = $date->modify("+7 days");

// echo $newDate->format("Y-m-d");


// echo "<br />";

// $date = new DateTimeImmutable(); 

// $newDate = $date->modify("+7 days");

// echo "Today: " . $date->format("Y-m-d") . "\n";
// echo "<br />";

// echo "After 7 Days: " . $newDate->format("Y-m-d");

// echo "<br />";
// echo "<br />";

// $date = new DateTime();
// echo "Debug";
// echo "Y-M-j " . $date->format("Y-M-jS");

// echo "<br />";
// echo "<br />";

// echo "D-M-Y" . $date->format("d-m-Y");

// echo "<br />";
// echo "<br />";

// $date = new DateTime("2026-08-18");

// $date->modify("+5 days");

// echo "Modified Date: " . $date->format("Y-m-d");

// echo "<br />";
// echo "<br />";

// $today = new DateTime();
// $expiry = new DateTime("2026-08-17");

// if ($today < $expiry) {
//     echo "Still valid";
// }
// else {
//     echo "Expired";
// }