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

// 1. count()
$names = ["Animesh", "Rahul", "Amit","Rishab", "Abhishek"];

echo "Array length: " . count($names);
echo "<br />";


// 2. in_array()

$names = ["Animesh", "Rahul", "Amit"];

if (in_array("Rahul", $names)) {
    echo "Rahul found";
}

echo "<br />";
if (in_array("Abhishek", $names)) {
    echo "Abhishek found";
} else {
    echo "Abhishek not found";
}


echo "<br />";

// 3. array_search()

$names = ["Animesh", "Rahul", "Amit"];

$index = array_search("Rahul", $names);

echo $index;

echo "<br />";


// 4. array_merge()
$first = ["PHP", "Laravel"];
$second = ["MySQL", "Redis"];

$result = array_merge($first, $second);

print_r($result);
echo "<br />";
echo $result[0] . "<br />";
echo $result[1] . "<br />";
echo $result[2] . "<br />";
echo $result[3] . "<br />";

echo "<br />";

// 5. array_keys()

$user = [
    "name" => "Animesh",
    "email" => "test@example.com",
    "age" => 30
];

$keys = array_keys($user);

print_r($keys);


echo "<br />";

// 6. array_values()

$user = [
    "name" => "Animesh",
    "email" => "test@example.com",
    "age" => 30
];

$values = array_values($user);

print_r($values);  