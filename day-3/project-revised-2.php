<?php
$students = [
    [
        "name" => "Rahul",
        "marks"=>[
            "maths" => 78,
            "english" => 85,
            "science" => 72,
            "physics" => 99,
            "Computer" => 75
        ]        
    ],
    [
        "name" => "Aman",
        "marks"=>[
            "maths" => 39,
            "english" => 74,
            "science" => 80,
            "physics" => 99,
            "Computer" => 75
        ]
    ],
    [
        "name" => "Priya",
        "marks"=>[
            "maths" => 92,
            "english" => 88,
            "science" => 95,
            "physics" => 99,
            "Computer" => 75
        ]
    ],
    [
        "name" => "Neha",
        "marks"=>[
            "maths" => 55,
            "english" => 84,
            "science" => 88,
            "physics" => 99,
            "Computer" => 75
        ]
    ]
];

// match, array, and foreach in details.

foreach ($students as $index => $student) {
    $total_marks = 0;
    $students_marks = $student["marks"];
    $subjects = count($students_marks);
    $status = "Pass";

    foreach($students_marks as $mark){

        $total_marks = $total_marks + $mark;

        if($mark<40){
            $status = "Fail";
        }

    }

    $avg = number_format($total_marks / $subjects , 2);

    $students[$index]["total_marks"] = $total_marks;
    $students[$index]["avg"] = $avg;
    $students[$index]["status"] = $status;

}

$n = count($students);

for ($i = 0; $i < $n; $i++) {

    for ($j = 0; $j < $n - 1; $j++) {

        if ($students[$j]["total_marks"] <  $students[$j + 1]["total_marks"]) {

            $temp = $students[$j];
            $students[$j] = $students[$j + 1];
            $students[$j + 1] = $temp;
        }
    }
}

foreach ($students as $index => $student) {
    $students[$index]["rank"] = $index + 1;
}

echo "<h2>Student Results</h2>";

echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<tr>";

foreach ($students[0] as $key => $value) {

    if (is_array($value)) {

        foreach ($value as $subKey => $subValue) {
            echo "<th>" . htmlspecialchars(ucfirst($subKey)) . "</th>";
        }

    } else {

        echo "<th>" . htmlspecialchars(ucfirst(str_replace("_"," ", $key))) . "</th>";

    }
}
echo "</tr>";

foreach ($students as $student) {

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

// $json = json_encode($students, JSON_PRETTY_PRINT);

// echo "<pre>";
// print_r($json);
// echo "</pre>";