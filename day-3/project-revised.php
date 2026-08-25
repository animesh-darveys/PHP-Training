<?php
// Standalone Project: Student Result Calculator. 
// Process an array of multiple students' results, 
// compute rank order, and output the results as both an HTML table and a JSON payload.

$students = [
    [
        "name" => "Rahul",
        "maths" => 78,
        "english" => 85,
        "science" => 72
    ],
    [
        "name" => "Aman",
        "maths" => 39,
        "english" => 74,
        "science" => 80
    ],
    [
        "name" => "Priya",
        "maths" => 92,
        "english" => 88,
        "science" => 95
    ],
    [
        "name" => "Neha",
        "maths" => 55,
        "english" => 68,
        "science" => 60
    ]
];

foreach($students as &$student){

    $total_marks = 0;
    $name = $student["name"];
    $maths_marks = $student["maths"];
    $english_marks = $student["english"];
    $science_marks = $student["science"];

    $total_marks = $maths_marks + $english_marks + $science_marks;

    $avg = number_format($total_marks/3, 2);

    $status = match (true) {
        $maths_marks < 40 => "Fail",
        $english_marks < 40 => "Fail",
        $science_marks < 40 => "Fail",
        default => "Pass"
    };

    // echo "Total marks of ".$name." is ".$total_marks." and avarage is ".$avg."<br>";

    $student["total_marks"] = $total_marks;
    $student["avg"] = $avg;
    $student["status"] = $status;
}

unset($student); 

usort($students, function ($studentA, $studentB) {
    return $studentB["total_marks"] <=> $studentA["total_marks"];
});


foreach ($students as $index => $student) {
    $students[$index]["rank"] = $index + 1;
}

echo "<h2>Student Results</h2>";

echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Maths</th>
        <th>English</th>
        <th>Science</th>
        <th>Total</th>
        <th>Average</th>
        <th>Status</th>
      </tr>";

foreach ($students as $student) {

    echo "<tr>
            <td>{$student['rank']}</td>
            <td>" . htmlspecialchars($student['name']) . "</td>
            <td>{$student['maths']}</td>
            <td>{$student['english']}</td>
            <td>{$student['science']}</td>
            <td>{$student['total_marks']}</td>
            <td>{$student['avg']}</td>
            <td>{$student['status']}</td>
          </tr>";
}

echo "</table>";

// echo "<pre>";
// print_r($students);
// echo "</pre>";

$json = json_encode($students, JSON_PRETTY_PRINT);

echo "<pre>";
print_r($json);
echo "</pre>";