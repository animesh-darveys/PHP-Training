<?php
$students = [
    [
        "name" => "Rahul",
        "marks" => [
            "maths" => 78,
            "english" => 85,
            "science" => 72,
            "physics" => 99,
            "Computer" => 75
        ]
    ],
    [
        "name" => "Aman",
        "marks" => [
            "maths" => 39,
            "english" => 74,
            "science" => 80,
            "physics" => 99,
            "Computer" => 75
        ]
    ],
    [
        "name" => "Priya",
        "marks" => [
            "maths" => 92,
            "english" => 88,
            "science" => 95,
            "physics" => 99,
            "Computer" => 75
        ]
    ],
    [
        "name" => "Neha",
        "marks" => [
            "maths" => 55,
            "english" => 84,
            "science" => 88,
            "physics" => 99,
            "Computer" => 75
        ]
    ]
];

// match, array, and foreach in details.


$students = calculateStudentData($students);

function calculateStudentData($students = array())
{
    foreach ($students as $index => $student) {
        $total_marks = 0;
        $students_marks = $student["marks"];
        $subjects = count($students_marks);
        $status = "Pass";

        foreach ($students_marks as $mark) {
            $total_marks = $total_marks + $mark;
        }

        $percentage = ($total_marks / ($subjects * 100)) * 100;
        //on the basis of the percenctage mark pass or fail id percentage > 40 then it should be pass
        $status = $percentage >= 40 ? "Pass" : "Fail";
        $students[$index]["total_marks"] = $total_marks;
        $students[$index]["percentage"] = $percentage;
        $students[$index]["status"] = $status;
    }

    $n = count($students);
    // Two Pointer 
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($students[$j]["percentage"] > $students[$i]["percentage"]) {
                $temp = $students[$i];
                $students[$i] = $students[$j];
                $students[$j] = $temp;
            }
        }
    }

    return $students;
}

echo "<h2>Student Results</h2>";

echo "<table border='1' cellpadding='8' cellspacing='0'>";

$headingRenderStautus = false;
foreach ($students as $student) {


    if (!$headingRenderStautus) {
        echo "<tr>";
        foreach ($student as $key => $value) {
            $headingRenderStautus = true;
            //heading parts need to run only once
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    echo "<th>" . htmlspecialchars(ucfirst($subKey)) . "</th>";
                }
            } else {
                echo "<th>" . htmlspecialchars(ucfirst(str_replace("_", " ", $key))) . "</th>";
            }
        }
        echo "<tr>";
    }

    echo "<tr>";
    foreach ($student as $key => $value) {
        if (is_array($value)) {

            foreach ($value as $subKey => $subValue) {
                echo "<td>" . htmlspecialchars($subValue) . "</td>";
            }
        } else {

            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
    }

    echo "</tr>";
}

echo "</table>";

if (!empty($students)) {
    $json = json_encode($students, JSON_PRETTY_PRINT);
    echo "<pre>";
    print_r($json);
    echo "</pre>";
} else {
    echo "NO RECORD FOUND";
}
