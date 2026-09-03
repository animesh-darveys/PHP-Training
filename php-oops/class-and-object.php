<?php

class MathsOPeration{
    function sum($a, $b){
        echo $a + $b;
        echo '<br>';
    }
    function sub($a, $b){
        echo $a - $b;
        echo '<br>';

    }
        function multi($a, $b){
        echo $a * $b;
        echo '<br>';

    }
        function division($a, $b){
        echo $a / $b;
        echo '<br>';

    }
}

$maths = new MathsOPeration();
$maths->sum(10,20);
$maths->sub(100,20);
$maths->multi(100,20);
$maths->division(100,20);