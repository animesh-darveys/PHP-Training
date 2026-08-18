<?php
// 1. date() — Date/time format karna

echo date("Y-m-d");

echo "<br />";

echo date("d-m-Y H:i:s");

echo "<br />";


// 2. strtotime() — String ko date/time mein convert karna

$date = strtotime("+7 days");

echo  "Estimated Date: " . date("Y-m-d", $date);


echo "<br />";

// 3. DateTime — Date ko object ke form mein handle karna

$date = new DateTime("2026-08-18");

echo $date->format("d-m-Y");

echo "<br />";

// 4. DateTimeImmutable — Date ko safely modify karna

$date = new DateTimeImmutable("2026-08-18");

$newDate = $date->modify("+7 days");

echo $newDate->format("Y-m-d");


echo "<br />";

// Current (Aaj ki) date dynamically lene ke liye
$date = new DateTimeImmutable(); 

// 7 din aage ki date calculate karna
$newDate = $date->modify("+7 days");

echo "Today: " . $date->format("Y-m-d") . "\n";
echo "<br />";

echo "After 7 Days: " . $newDate->format("Y-m-d");

echo "<br />";
echo "<br />";

// 5. format() — Date ko desired format mein convert karna

$date = new DateTime();

echo "Y-M-D" . $date->format("Y-m-d");

echo "<br />";
echo "<br />";

echo "D-M-Y" . $date->format("d-m-Y");

echo "<br />";
echo "<br />";


// 6. modify() — Date mein change karna

$date = new DateTime("2026-08-18");

$date->modify("+5 days");

echo "Modified Date: " . $date->format("Y-m-d");

echo "<br />";
echo "<br />";

// 7. Date Comparison

$today = new DateTime();
$expiry = new DateTime("2026-08-17");

if ($today < $expiry) {
    echo "Still valid";
}
else {
    echo "Expired";
}

// 🧠 Quick Revision
// date()             → date/time format
// strtotime()        → string → timestamp
// DateTime           → date object
// DateTimeImmutable  → immutable date object
// format()           → desired date format
// modify()           → date add/subtract
// comparison         → dates compare