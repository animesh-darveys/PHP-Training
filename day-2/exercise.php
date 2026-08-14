<?php

// ========================================
// Student Exam Scores
// ========================================

$animeshScore = 81;
$princeScore = 82;
$randhirScore = 65;
$anuragScore = 40;


// ========================================
// Grade Counters
// ========================================

$aGradeCount = 0;
$bGradeCount = 0;
$cGradeCount = 0;
$fGradeCount = 0;


// ========================================
// Student 1 - Animesh
// ========================================

$animeshGrade = match (true) {
    $animeshScore >= 90 => "A",
    $animeshScore >= 75 => "B",
    $animeshScore >= 60 => "C",
    default => "F"
};

echo "Animesh - Score: $animeshScore - Grade: $animeshGrade<br>";

if ($animeshGrade === "A") {
    $aGradeCount++;
} elseif ($animeshGrade === "B") {
    $bGradeCount++;
} elseif ($animeshGrade === "C") {
    $cGradeCount++;
} else {
    $fGradeCount++;
}


// ========================================
// Student 2 - Prince
// ========================================

$princeGrade = match (true) {
    $princeScore >= 90 => "A",
    $princeScore >= 75 => "B",
    $princeScore >= 60 => "C",
    default => "F"
};

echo "Prince - Score: $princeScore - Grade: $princeGrade<br>";

if ($princeGrade === "A") {
    $aGradeCount++;
} elseif ($princeGrade === "B") {
    $bGradeCount++;
} elseif ($princeGrade === "C") {
    $cGradeCount++;
} else {
    $fGradeCount++;
}


// ========================================
// Student 3 - Randhir
// ========================================

$randhirGrade = match (true) {
    $randhirScore >= 90 => "A",
    $randhirScore >= 75 => "B",
    $randhirScore >= 60 => "C",
    default => "F"
};

echo "Randhir - Score: $randhirScore - Grade: $randhirGrade<br>";

if ($randhirGrade === "A") {
    $aGradeCount++;
} elseif ($randhirGrade === "B") {
    $bGradeCount++;
} elseif ($randhirGrade === "C") {
    $cGradeCount++;
} else {
    $fGradeCount++;
}


// ========================================
// Student 4 - Anurag
// ========================================

$anuragGrade = match (true) {
    $anuragScore >= 90 => "A",
    $anuragScore >= 75 => "B",
    $anuragScore >= 60 => "C",
    default => "F"
};

echo "Anurag - Score: $anuragScore - Grade: $anuragGrade<br>";

if ($anuragGrade === "A") {
    $aGradeCount++;
} elseif ($anuragGrade === "B") {
    $bGradeCount++;
} elseif ($anuragGrade === "C") {
    $cGradeCount++;
} else {
    $fGradeCount++;
}


// ========================================
// Grade Summary
// ========================================

echo "<br>";
echo "<strong>Grade Summary</strong><br>";

echo "A Grade Students: $aGradeCount<br>";
echo "B Grade Students: $bGradeCount<br>";
echo "C Grade Students: $cGradeCount<br>";
echo "F Grade Students: $fGradeCount<br>";