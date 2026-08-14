<?php

$name = "Animesh";
$age = 30;
$salary = 20000000;
$developer = true;
$fav_color = ["red","black", "green"];

$price = "100";

$price = (int) $price;

echo "My name is " . $name . "<br>";
echo "My age is " . $age. "<br>";
echo "My salary is ". $salary. "<br>";
echo "I am a developer : ". $developer. "<br>";
echo "My First Favourite color : ". $fav_color[0];

// var_dump($name);

echo gettype($name). "<br>";

echo gettype($price). "<br>";

// define("GREETING", "Welcome to W3Schools.com!");
// echo GREETING;

define("GREETING", "Welcome to W3Schools.com!");
function greet_message(){
echo GREETING;
}
greet_message();
print("! <br />Hello Print fuction with parantheces");
print "! <br />Hello Print fuction without parantheces";
echo "<br />";
$text_1 = "Test Message";
echo "<br />";
$text_2 = "test message 2";
echo ($text_1." ".$text_2);
echo "<br />";
print ($text_1." ".$text_2);

$person = [
    "name" => "Animesh",
    "age" => 30,
    "role" => "Developer"
];

var_dump($person);
