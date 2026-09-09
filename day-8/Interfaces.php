<!-- agar interface ko hum jaha bhi implement keyword ka use karte hai to interface ke andar jitne bhi function declare kiye hai unki body us class me likhna jaruri hai otherwise error aayegi -->
<!-- Class Classname contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (Employee::work) -->

<?php
interface Employee{
    public function work();
    public function salary();
}

class Developer implements Employee{
    public function work() {
        echo "Developer is writing code";
    }
    public function salary() {
        echo "Developer salary";
    }
}

class Designer implements Employee{
    public function work() {
        echo "Designer is writing code";
    }
    public function salary() {
        echo "Designer salary";
    }
}

class Tester implements Employee{
    public function work() {
        echo "Tester is writing code";
    }
    public function salary() {
        echo "Tester salary";
    }
}

$developer = new Developer();
$developer->work();
echo "<br />";
$developer->salary();
echo "<br />";
$designer = new Designer();
$designer->work();
echo "<br />";
$designer->salary();
echo "<br />";
$tester = new Tester();
$tester->work();
echo "<br />";
$tester->salary();
?>