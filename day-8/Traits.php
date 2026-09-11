<?php
// Define a trait
trait Authentication {
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
}

class Staff {
    use Authentication;
}

class client {
    use Authentication;
}

class customer {
    use Authentication;
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