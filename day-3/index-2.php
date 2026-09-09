<?php
function message(){
    echo "Hello Darveys";
}

message();
echo "<br />";
// function with return value
function sum($num1,$num2) {
    return  $num1 + $num2;
}


// function with default value
function add($num1=5,$num2=5) {
    return  $num1 + $num2;
}

echo sum(50,20)."<br />";

function multiplecation(int $salary, int $percentage = 10): float{
    return $salary * ($percentage / 100);
}

$result = multiplecation(50000);

echo $result;
echo "<br />";
echo var_dump($result);  

?>
