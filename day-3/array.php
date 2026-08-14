<?php

$arr = [
    "animesh",
    "randhir",
    "abhishek",
    "pradum",
    "rishab",
    "abhinav",
    "rishi",
    "puneet"
];

for ($i = 0; $i < count($arr); $i++) {
    // echo $arr[$i] . $i . "<br>";
    // echo "{$arr[$i]} {$i}<br>";
    // echo strtoupper($arr[$i]) . " {$i}<br>";
    echo ucwords($arr[$i]) . " {$i}<br>";
}


$employee = [
    "name" => "Animesh",
    "age" => 30,
    "department" => "IT",
    "salary" => 50000
];
echo "<br />";
echo $employee["name"] . "<br />";
echo $employee["age"] . "<br />";
echo $employee["department"] . "<br />";
echo $employee["salary"] . "<br />";


// multi dimentional array
$employees = [
    [
        "name" => "Animesh",
        "department" => "IT",
        "salary" => 50000
    ],
    [
        "name" => "Rahul",
        "department" => "HR",
        "salary" => 40000
    ],
    [
        "name" => "Amit",
        "department" => "IT",
        "salary" => 60000
    ]
];
echo "<br />";
echo "<br />";
foreach ($employees as $employee) {
    echo $employee["name"] . "</br />";
    echo $employee["department"] . "</br />";
    echo $employee["salary"] . "</br />";
    echo "<br />";

}