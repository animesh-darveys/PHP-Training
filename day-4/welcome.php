<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

$employeeName = trim($_POST["employee_name"] ?? "");
    $employeeDepartment = trim($_POST["employee_department"] ?? "");
    $employeeSalary = trim($_POST["employee_salary"] ?? "");

    // Validate employee name
    if ($employeeName === "") {
        $errors["employee_name"] = "Employee name is required.";
    }

    // Validate department
    if ($employeeDepartment === "") {
        $errors["employee_department"] = "Department is required.";
    }

    // Validate salary
    if ($employeeSalary === "") {
        $errors["employee_salary"] = "Salary is required.";
    }

    echo "Employee Name: " . $employeeName . "<br>";
    echo "Employee Department: " . $employeeDepartment . "<br>";
    echo "Employee Salary: " . $employeeSalary . "<br>";
}