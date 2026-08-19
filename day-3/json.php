<?php

// 1. PHP Array → JSON (json_encode)
$user = [
    "name" => "Animesh",
    "age" => 30,
    "department" => "IT"
];

$json = json_encode($user);

echo $json;

echo "<br>";

// 2. JSON → PHP Array (json_decode)

$jsonData = '{"name": "Randheer", "age": 30, "department": "IT"}';
$phpArray = json_decode($jsonData, true);

echo $user["name"];
echo "<br>";

echo $user["age"];
echo "<br>";

echo $user["department"];
echo "<br>";




$employees = [
    [
        "name" => "Animesh",
        "department" => "IT"
    ],
    [
        "name" => "Rahul",
        "department" => "HR"
    ]
];

echo json_encode($employees);

echo "<br /> <br />";

echo json_encode($employees, JSON_PRETTY_PRINT);

echo "<br /> <br />";

$order = [
    "order_id" => 101,
    "customer" => [
        "name" => "Animesh",
        "email" => "animesh@example.com"
    ],
    "total" => 5000
];

echo json_encode($order);

echo "<br /> <br />";

$json = '{"name":"Animesh"';

$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Invalid JSON";
}
echo "<br /> <br />";



$json = '{"name":"Animesh"';

try {
    $data = json_decode(
        $json,
        true,
        512,
        JSON_THROW_ON_ERROR
    );
} catch (JsonException $e) {
    echo "Invalid JSON: " . $e->getMessage();
}

echo "<br /> <br />";

$employee = [
    "id" => 101,
    "name" => "Animesh",
    "email" => "animesh@example.com",
    "department" => "IT",
    "salary" => 50000,
    "is_active" => true
];

$employeejson = json_encode($employee, JSON_PRETTY_PRINT);

echo $employeejson;

echo "<br /> <br />";

$json = '{"id":101,"name":"Animesh","department":"IT","salary":50000}';

$decodedEmployee = json_decode($json, true);

echo "Name: " . $decodedEmployee["name"] . "<br>";
echo "Salary: " . $decodedEmployee["salary"] . "<br>";

//  Nested JSON ⭐
$order = [
    "order_id" => 1001,

    "customer" => [
        "name" => "Animesh",
        "email" => "animesh@example.com"
    ],

    "items" => [
        [
            "name" => "Nike Shoes",
            "price" => 5000,
            "quantity" => 2
        ],
        [
            "name" => "T-Shirt",
            "price" => 2000,
            "quantity" => 1
        ]
    ],

    "status" => "paid"
];

$orderJson = json_encode($order, JSON_PRETTY_PRINT);
echo $orderJson;