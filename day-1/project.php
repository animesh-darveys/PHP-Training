<?php

// Student data
$studentName = "Animesh Gupta";
$rollNo = "DAR-01";

$mathsMarks = 85;
$englishMarks = 78;
$scienceMarks = 92;

// Calculate total and average
$totalMarks = $mathsMarks + $englishMarks + $scienceMarks;
$averageMarks = $totalMarks / 3;

// Escape dynamic values before displaying them in HTML
$safeStudentName = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8');
$safeRollNo = htmlspecialchars($rollNo, ENT_QUOTES, 'UTF-8');
$safeTotalMarks = htmlspecialchars((string) $totalMarks, ENT_QUOTES, 'UTF-8');
$safeAverageMarks = htmlspecialchars((string) round($averageMarks, 2), ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
</head>
<body>

    <h1>Student Profile</h1>

    <div>
        <p>
            <strong>Name:</strong>
            <?= $safeStudentName ?>
        </p>

        <p>
            <strong>Roll No:</strong>
            <?= $safeRollNo ?>
        </p>

        <p>
            <strong>Total Marks:</strong>
            <?= $safeTotalMarks ?>
        </p>

        <p>
            <strong>Average:</strong>
            <?= $safeAverageMarks ?>
        </p>
    </div>

</body>
</html>