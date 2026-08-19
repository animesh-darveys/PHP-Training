<?php
// Write a function that accepts an array of student results (name, 3 subject marks)
// and returns a new array with each student's total, average and pass/fail status
// - no built-in aggregate shortcuts.

function processStudentResults($students)
{
    $results = [];

    foreach ($students as $student) {

        $total = 0;

        // Calculate total manually
        foreach ($student["marks"] as $mark) {
            $total = $total + $mark;
        }

        // Calculate average
        $average = $total / 3;

        // Pass/Fail
        if ($average >= 40) {
            $status = "Pass";
        } else {
            $status = "Fail";
        }

        // Add result
        $results[] = [
            "name" => $student["name"],
            "total" => $total,
            "average" => $average,
            "status" => $status
        ];
    }

    return $results;
}


$students = [
    [
        "name" => "Animesh",
        "marks" => [80, 75, 90]
    ],
    [
        "name" => "Rahul",
        "marks" => [45, 50, 40]
    ],
    [
        "name" => "Amit",
        "marks" => [30, 35, 25]
    ]
];


$results = processStudentResults($students);

echo "<pre>";
echo json_encode($results, JSON_PRETTY_PRINT);
echo "</pre>";
