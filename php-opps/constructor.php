<?php

class GreetingFunction {
    function __construct($name){
        echo 'Greetings of the day ' .$name . '<br>';
    }
}

$obj = new GreetingFunction("Animesh");
$obj = new GreetingFunction("Randheer");
$obj = new GreetingFunction("Deepak");

?>
