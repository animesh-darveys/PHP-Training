<?php

// Employee data
$employees = [
    [
        "name" => "Animesh",
        "baseSalary" => 35000,
        "experience" => 6,
        "department" => "IT"
    ],
    [
        "name" => "Prince",
        "baseSalary" => 60000,
        "experience" => 3,
        "department" => "Account"
    ]
];


// ========================================
// Process each employee
// ========================================

foreach ($employees as $employee) {

    $name = $employee["name"];
    $baseSalary = $employee["baseSalary"];
    $experience = $employee["experience"];
    $department = $employee["department"];


    // ====================================
    // Experience Bonus
    // ====================================

    $experienceBonusPercent = match (true) {
        $experience >= 5 => 10,
        $experience >= 2 => 5,
        default => 2
    };


    // ====================================
    // Department Bonus
    // ====================================

    $departmentBonusPercent = match ($department) {
        "IT" => 5,
        "HR" => 3,
        "Sales" => 7,
        default => 0
    };


    // ====================================
    // Calculate Bonuses
    // ====================================

    $experienceBonus = ($baseSalary * $experienceBonusPercent) / 100;

    $departmentBonus = ($baseSalary * $departmentBonusPercent) / 100;

    $totalBonus = $experienceBonus + $departmentBonus;


    // ====================================
    // Salary Before Deduction
    // ====================================

    $salaryBeforeDeduction = $baseSalary + $totalBonus;


    // ====================================
    // Deduction
    // ====================================

    $deductionPercent = match (true) {
        $salaryBeforeDeduction > 100000 => 5,
        default => 2
    };

    $deduction = ($salaryBeforeDeduction * $deductionPercent) / 100;


    // ====================================
    // Final Salary
    // ====================================

    $finalSalary = $salaryBeforeDeduction - $deduction;


    // ====================================
    // Payslip
    // ====================================

    echo "<hr>";

    echo "<h2>Employee Payslip</h2>";

    echo "Employee Name: " . $name . "<br>";
    echo "Department: " . $department . "<br>";
    echo "Experience: " . $experience . " years<br>";

    echo "Base Salary: ₹" . number_format($baseSalary, 2) . "<br>";

    echo "Experience Bonus: "
        . $experienceBonusPercent
        . "% (₹"
        . number_format($experienceBonus, 2)
        . ")<br>";

    echo "Department Bonus: "
        . $departmentBonusPercent
        . "% (₹"
        . number_format($departmentBonus, 2)
        . ")<br>";

    echo "Total Bonus: ₹"
        . number_format($totalBonus, 2)
        . "<br>";

    echo "Salary Before Deduction: ₹"
        . number_format($salaryBeforeDeduction, 2)
        . "<br>";

    echo "Deduction: "
        . $deductionPercent
        . "% (₹"
        . number_format($deduction, 2)
        . ")<br>";

    echo "<strong>Final Salary: ₹"
        . number_format($finalSalary, 2)
        . "</strong><br>";
}