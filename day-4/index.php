<!DOCTYPE html>
<html>
<head>
    <title>Day 4 - Forms</title>
</head>
<body>

<h2>Employee Form</h2>
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

?>
<form method="POST" action="welcome.php">

    <label>Employee Name:</label>

    <input type="text" name="employee_name">
    <br><br>
    <label>Employee Department:</label>

    <input type="text" name="employee_department">
    <br><br>      
    <label>Employee Salary:</label>

    <input type="text" name="employee_salary">

    <br><br>

    <button type="submit">Submit</button>

</form>

</body>
</html>