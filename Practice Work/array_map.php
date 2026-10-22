<?php
// $a = array(1, 2, 3, 4, 5);
// function squareNumber($n){
//     return ($n*$n);
// }

// $updatedArray = array_map("squareNumber", $a);

// echo "<pre>";
// print_r($updatedArray);
// echo "</pre>";


$a = array(1, 2, 3, 4, 5);
$b = array(6, 7, 8, 9, 10);

function addNumbers($a, $b) {
    return ($a + $b)*($a + $b);
}

$updatedArray = array_map("addNumbers", $a, $b);

echo "<pre>";
print_r($updatedArray);
echo "</pre>";


// const APP_NAME = "Student Management System";
// const APP_VERSION = "1.0";
// const COMPANY_NAME = "ABC Technologiess";
// $COMPANY_NAME = "Updated";

// echo APP_NAME;
// echo "<br>";
// echo APP_VERSION;
// echo "<br>";
// echo COMPANY_NAME;
// echo "<br>";
// echo $COMPANY_NAME; // new variable created.


define("COMPANY_NAME", "ABC Technologies");
echo COMPANY_NAME;