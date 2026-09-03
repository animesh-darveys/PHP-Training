<?php

class school{
    function management(){
        echo 'Management Function';
    }
    private function finance(){
        echo 'Finance Function';
    }

    function studentFee(){
        $this-> finance();
    }

    protected function studentResult(){
        echo 'Student Paper Function';
    }
}
$school = new school();
$school->management();
echo "<br>";
// $school->finance();
echo "<br>";
$school->studentFee();

class student extends school {
    function student_details(){
        $this->management();
        echo "<br>";
        $this->studentResult();
    }
}
$student = new student();
$student->management();
echo "<br>";
$student->student_details();

