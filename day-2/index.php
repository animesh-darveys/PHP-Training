<?php

// $age = 18;

// if($age >= 18){
//     echo "You are eligible to apply for a driving licence.";
// } else{
//     echo "You are not eligible to apply for a driving licence yet.";
// }

// if ($age >= 18) {
//     echo "Congrats! You are eligible to apply for a driving licence.";
// } elseif ($age >= 15) {
//     echo "You are not yet eligible to apply. You can apply for a driving licence after you turn 18.";
// } else {
//     echo "You are not eligible to apply for a driving licence yet.";
// }

// $fav_programming = "Java";

// switch ($fav_programming) {
//     case "PHP":
//         echo "Your are a PHP/LARAVEL developer!";
//         break;
//     case "Shopify":
//         echo "Your are a shopify developer!";
//         break;
//     case "Java":
//         echo "Your are a Java/Springboot developer!";
//         break;
//     case "HTML":
//         echo "Your are not a developer!";
//         break;
//     default:
//         echo "Your Tech Stack is neither PHP, JAVA, nor SHOPIFY!";
// }



// $text = match ($fav_programming) {
//     "PHP" => "Your are a PHP/LARAVEL developer!",
//     "Shopify" => "Your are a shopify developer!",
//     "Java" => "Your are a Java/Springboot developer!",
//     "HTML" => "Your are Web Designer!",
//     default => "Your Tech Stack is neither PHP, JAVA, nor SHOPIFY!",
// };

// echo "<br />" . $text;

// PHP's match uses strict comparison (===), and it is also case-sensitive for strings.
// $fav_programming = "CSS";

// $text = match($fav_programming) {
//   "Java", "PHP", "Shopify", "Python" => "You are a backend Developer",
//   "HTML", "CSS","JAVASCRIPT" => "You are a Frontend Developer",
//   default => "You are not a developer",
// };

// echo $text;

// normalize the input first
// $fav_programming = strtolower("JaVa");

// $text = match($fav_programming) {
//     "java", "php", "shopify", "python" => "You are a backend Developer",
//     "html", "css", "javascript" => "You are a Frontend Developer",
//     default => "You are not a developer",
// };

// echo $text;

$employees = [
    "Animesh",
    "Rahul",
    "Amit",
    "Priya",
    "Neha"
];

$totalEmployees = count($employees);

// echo "Total Employees: " . (string) $totalEmployees;

// echo "<br />";

for ($i = 0; $i < $totalEmployees; $i++) {
    echo "Employee " . ($i + 1) . ": " . $employees[$i] . "<br>";
}

echo "<br />";

for ($i = 0; $i < $totalEmployees; $i++) {
    // if ($i == 3) break;
    echo "Employee " . ($i + 1) . ": " . $employees[$i] . "<br>";
}

for ($i = 0; $i < $totalEmployees; $i++) {
    if ($i == 3) continue;
    echo "Employee " . ($i + 1) . ": " . $employees[$i] . "<br>";
}

echo "<br />";

foreach ($employees as $employee) { 
    echo "Employee: " . $employee . "<br>";
}


echo "<br />";
echo"<b>While Loop Implementaion :</b>";
echo "<br />";
$i = 0;

while ($i < $totalEmployees) {
    if ($i == 2) {
        $i++;
        continue;
    }
    echo "Employee: " . $employees[$i] . "<br>";

    $i++;
}