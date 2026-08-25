
<?php

$scores = [95, 82, 74, 61, 88, 45];

$array_size = count($scores);

$aGradeCount = 0;
$bGradeCount = 0;
$cGradeCount = 0;
$fGradeCount = 0;


for ($i = 0; $i < $array_size; $i++) {
    // echo $scores[$i] . "<br>";
    if($scores[$i] > 90){
        $aGradeCount = $aGradeCount + 1;
    }elseif($scores[$i] > 80){
        $bGradeCount = $bGradeCount + 1;
    }elseif($scores[$i] > 70){
        $cGradeCount = $cGradeCount + 1;
    }else{
        $fGradeCount = $fGradeCount + 1;
    }
}

echo "<br>";
echo "A Grade: " . $aGradeCount . "<br>";
echo "B Grade: " . $bGradeCount . "<br>";
echo "C Grade: " . $cGradeCount . "<br>";
echo "F Grade: " . $fGradeCount . "<br>";