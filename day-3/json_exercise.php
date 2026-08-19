<?php

$order = [
    "order_id" => 1001,
    "status" => "paid",
    "is_delivered" => true,
    "tracking_number" => null,

    "customer" => [
        "id" => 501,
        "name" => "Animesh",
        "email" => "animesh@example.com"
    ],

    "items" => [
        [
            "product_id" => 101,
            "name" => "Nike Shoes",
            "price" => 5000,
            "quantity" => 2
        ],
        [
            "product_id" => 102,
            "name" => "T-Shirt",
            "price" => 2000,
            "quantity" => 1
        ]
    ],

    "total" => 12000
];


// 1. PHP Array → JSON

try {

    $json = json_encode(
        $order,
        JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
    );

    echo "<pre>";
    echo $json;
    echo "</pre>";

} catch (JsonException $e) {

    echo "JSON Error: " . $e->getMessage();

}


// 2. JSON → PHP Associative Array

try {

    $data = json_decode(
        $json,
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    echo "<br>";

    // 3. Access normal data
    echo "Order ID: " . $data["order_id"] . "<br>";
    echo "Status: " . $data["status"] . "<br>";
    echo "Total: " . $data["total"] . "<br>";

    // 4. Access nested data
    echo "Customer: " . $data["customer"]["name"] . "<br>";
    echo "Email: " . $data["customer"]["email"] . "<br>";

    // 5. Access nested array
    echo "First Product: " . $data["items"][0]["name"] . "<br>";
    echo "First Product Price: " . $data["items"][0]["price"] . "<br>";

} catch (JsonException $e) {

    echo "JSON Decode Error: " . $e->getMessage();

}





$company = [
    "name" => "Darveys",

    "department" => [
        "name" => "IT",

        "employee" => [
            "name" => "Animesh",

            "address" => [
                "country" => [
                    "name" => "India",

                    "city" => [
                        "name" => "Delhi"
                    ]
                ]
            ]
        ]
    ]
];

$companyJson = json_encode($company,JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);

echo "<pre>";
echo $companyJson;
echo "</pre>";