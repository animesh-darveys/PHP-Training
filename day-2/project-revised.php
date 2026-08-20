<?php
// Project: Salary Calculator.
// Given base salary, years of experience and department,
// apply conditional bonus/deduction rules and loop over a list of employees to print a payslip for each.

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
    ],
    [
        "name" => "Ram",
        "baseSalary" => 100000,
        "experience" => 12,
        "department" => "Marketing"
    ],
    [
        "name" => "Pradum",
        "baseSalary" => 40000,
        "experience" => 2,
        "department" => "Account"
    ]
];

foreach ($employees as $employee) {

    $name = $employee["name"];
    $baseSalary = $employee["baseSalary"];
    $experience = $employee["experience"];
    $department = $employee["department"];

    // Calculate Experience-Based Bonus Percentage

    // if ($experience > 10) {
    //     $experienceBonusPercent = 20;
    // } elseif ($experience > 5) {
    //     $experienceBonusPercent = 10;
    // } else {
    //     $experienceBonusPercent = 5;
    // }

    $experienceBonusPercent = match (true) {
        $experience > 10 => 20,
        $experience > 5  => 10,
        default          => 5
    };


    // Calculate Department-Based Bonus Percentage


    if ($department == "IT") {
        $departmentBonusPercentage = 5;
    } elseif ($department == "Account") {
        $departmentBonusPercentage = 3;
    } elseif ($department == "Marketing") {
        $departmentBonusPercentage = 7;
    } else {
        $departmentBonusPercentage = 0;
    }

    // Calculate Total Bonuses


    $experienceBonus = ($baseSalary * $experienceBonusPercent) / 100;

    $departmentBonus = ($baseSalary * $departmentBonusPercentage) / 100;

    $totalBonus = $experienceBonus + $departmentBonus;

    $salaryBeforeDeduction = $baseSalary + $totalBonus;

    // Calculate Salary Deduction Percentage


    if ($salaryBeforeDeduction > 50000) {
        $deductionPercent = 5;
    } else {
        $deductionPercent = 2;
    }

     $deduction = ($salaryBeforeDeduction * $deductionPercent) / 100;
    
    // Display Employee Payslip

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
        . $departmentBonusPercentage
        . "% (₹"
        . number_format($departmentBonus, 2)
        . ")<br>";

    echo "Salary Before Deduction: ₹"
        . number_format($salaryBeforeDeduction, 2)
        . "<br>";
    echo "Deduction:"
        . $deductionPercent
        . "% (₹"
        . number_format($deduction, 2)
        . ")<br>";

    echo "Final Salary: ₹"
        . $salaryBeforeDeduction - $deduction
        . "<br>";
}
?>