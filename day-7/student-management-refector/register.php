<?php

require_once './config/auth.php';
require_once './config/database.php';
require_once './classes/Student.php';
if (empty($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] === 'student') {
    header('Location: student_detail.php');
    exit;
}

$mode = 'create';

require_once './includes/student_handler.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Student Management System</span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>
<?php require_once './includes/student_form.php';?>
</body>
</html>
