<?php

$studentName = "Animesh";

$mathsMarks = 80;
$englishMarks = 75;
$scienceMarks = 90;

$feesPaid = 15000;
$totalFees = 20000;

// Calculate total marks
$totalMarks = $mathsMarks + $englishMarks + $scienceMarks;

// Calculate average marks
$averageMarks = $totalMarks / 3;

// Calculate remaining fees
$remainingBalance = $totalFees - $feesPaid;

// Display result
echo "Student Name: " . $studentName . "<br>";
echo "Total Marks: " . $totalMarks . "<br>";
echo "Average Marks: " . $averageMarks . "<br>";
echo "Remaining Balance: " . $remainingBalance . "<br>";