<?php
// Define a trait
trait Authentication {
    abstract public function greeting();
    public function login($name) {
        echo "Logged in successfully. Hurray! {$name} ";
    }

    public function logout($name) {
        echo "Logged Out";
    }
}

// Use the trait in a class
class Admin {
    use Authentication;
    public function greeting(){
        echo "Good Morning";
    }
}

class Staff {
    use Authentication;
    public function greeting(){
        echo "Good Morning";
    }
}

class client {
    use Authentication;
    public function greeting(){
        echo "Good Morning";
    }
}

class customer {
    use Authentication;
    public function greeting(){
        echo "Good Morning";
    }
}

echo "<br />";
$obj = new Admin();
$obj->login("Amit");

echo "<br />";
$obj2 = new Staff();
$obj2->login("Animesh");

echo "<br />";
$obj2 = new Staff();
$obj2->login("Randheer");

echo "<br />";
$obj2 = new Staff();
$obj2->login("Abhishek");
?>