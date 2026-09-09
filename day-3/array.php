<?php

// $arr = [
//     "animesh",
//     "randhir",
//     "abhishek",
//     "pradum",
//     "rishab",
//     "abhinav",
//     "rishi",
//     "puneet"
// ];

// for ($i = 0; $i < count($arr); $i++) {
//     // echo $arr[$i] . $i . "<br>";
//     // echo "{$arr[$i]} {$i}<br>";
//     // echo strtoupper($arr[$i]) . " {$i}<br>";
//     echo ucwords($arr[$i]) . " {$i}<br>";
// }


// $employee = [
//     "name" => "Animesh",
//     "age" => 30,
//     "department" => "IT",
//     "salary" => 50000
// ];
// echo "<br />";
// echo $employee["name"] . "<br />";
// echo $employee["age"] . "<br />";
// echo $employee["department"] . "<br />";
// echo $employee["salary"] . "<br />";


// // multi dimentional array
// $employees = [
//     [
//         "name" => "Animesh",
//         "department" => "IT",
//         "salary" => 50000
//     ],
//     [
//         "name" => "Rahul",
//         "department" => "HR",
//         "salary" => 40000
//     ],
//     [
//         "name" => "Amit",
//         "department" => "IT",
//         "salary" => 60000
//     ]
// ];
// echo "<br />";
// echo "<br />";
// foreach ($employees as $employee) {
//     echo $employee["name"] . "</br />";
//     echo $employee["department"] . "</br />";
//     echo $employee["salary"] . "</br />";
//     echo "<br />";

// }

// // 1. count()
// $names = ["Animesh", "Rahul", "Amit","Rishab", "Abhishek"];

// echo "Array length: " . count($names);
// echo "<br />";


// // 2. in_array()

// $names = ["Animesh", "Rahul", "Amit"];

// if (in_array("Rahul", $names)) {
//     echo "Rahul found";
// }

// echo "<br />";
// if (in_array("Abhishek", $names)) {
//     echo "Abhishek found";
// } else {
//     echo "Abhishek not found";
// }


// echo "<br />";

// // 3. array_search()

// $names = ["Animesh", "Rahul", "Amit"];

// $index = array_search("Rahul", $names);

// echo $index;

// echo "<br />";


// // 4. array_merge()
// $first = ["PHP", "Laravel"];
// $second = ["MySQL", "Redis"];

// $result = array_merge($first, $second);

// print_r($result);
// echo "<br />";
// echo $result[0] . "<br />";
// echo $result[1] . "<br />";
// echo $result[2] . "<br />";
// echo $result[3] . "<br />";

// echo "<br />";

// // 5. array_keys()

// $user = [
//     "name" => "Animesh",
//     "email" => "test@example.com",
//     "age" => 30
// ];

// $keys = array_keys($user);

// print_r($keys);


// echo "<br />";

// // 6. array_values()

// $user = [
//     "name" => "Animesh",
//     "email" => "test@example.com",
//     "age" => 30
// ];

// $values = array_values($user);

// print_r($values);  


// $json = '{"name":"Rahul","marks":90}';

// $student = json_decode($json);
// echo $student->name;

// $student = json_decode($json, true);
// echo $student["name"];
// echo "<br>";

// $fruits = ["a" => "Apple", "b" => "Banana"];

// // Method 1: array_key_exists()
// if (array_key_exists("a", $fruits)) {
//     echo "Key 'a' exists\n";
// }
// echo "<br>";
// // Method 2: isset() — faster, but returns false if value is null
// if (isset($fruits["b"])) {
//     echo "Key 'b' exists\n";
// }
// echo "<br>";
// // Difference example:
// $data = ["x" => null];
// var_dump(array_key_exists("x", $data)); // true  (key exists, even though value is null)
// echo "<br>";
// var_dump(isset($data["x"]));            // false (isset treats null as "not set")

$students = ["Amisha","Katreena","Karina"];

array_push($students, "Ram","Shyam","Ajay");
$students[] = "Animesh";
$students[] = "Shivam";
$students[] = "Randheer";

$students= array_merge(["abhishek"], $students);

array_unshift($students, "Kallu");
array_push($students, "Lallu");

$count = count($students);

echo "Student array length is {$count}";
echo "<br/>";
// print_r($students);
echo "<br/>";

var_dump(empty($students));
echo "<br/>";

if(empty($students)){
    echo "Student Array is Empty";
}else{
    echo "Student Array is Not Empty";
}

echo "<br/>";

var_dump(in_array("Animesh", $students));

echo "<br/>";

if (in_array("Animesh", $students)) {
    echo "Animesh list mein hai!";
} else {
    echo "Animesh list mein nahi hai.";
}
echo "<br/>";

if (!in_array("Ramesh", $students)) {
    echo "Ramesh list mein NAHI hai.";
}


$numbers = [1, 2, 3, 4, 5, 6];

$evenNumbers = array_filter($numbers, function ($num) {
    return $num % 2 == 1;
});
echo "<br/>";
print_r($evenNumbers);



$products = [
    ["name" => "T-Shirt",  "category" => "clothing", "price" => 499,  "in_stock" => true],
    ["name" => "Shoes",    "category" => "footwear", "price" => 1999, "in_stock" => false],
    ["name" => "Jeans",    "category" => "clothing", "price" => 1299, "in_stock" => true],
    ["name" => "Watch",    "category" => "accessory","price" => 2499, "in_stock" => true],
    ["name" => "Sandals",  "category" => "footwear", "price" => 799,  "in_stock" => true],
];

$clothing = array_filter($products,function($product){
    return $product["category"]== "clothing";
});

// print_r($clothing);

foreach($clothing as $key=> $value){
echo "<br/>";
echo "<br/>";
    if(is_array($value)){
        foreach($value as $subKey=>$subvalue){
            echo htmlspecialchars(ucFirst($subKey))." : ".htmlspecialchars(ucFirst($subvalue));
            echo "<br>";
        }
    }
}


$orderStatus = 3;

$statusText = match($orderStatus){
    1=>"Order Placed",
    2=>"Order Shiped",
    3=>"Out For Delivery",
    4=>"Delivered",
    5=>"Canceled",
    default=>"Unknown Status",
};
echo "<br/>";
echo "<br/>";
echo $statusText;


$marks = 78;

$grade = match(true) {
    $marks >= 90 => "A+",
    $marks >= 75 => "A",
    $marks >= 60 => "B",
    $marks >= 40 => "C",
    default => "Fail",
};
echo "<br/>";
echo "<br/>";
echo "Grade: " . $grade;