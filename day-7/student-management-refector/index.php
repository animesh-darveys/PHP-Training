<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

$role = strtolower(trim($_SESSION['role']));

if ($role === 'student') {
    header('Location: student_detail.php');
    exit;
}

if ($role === 'admin') {
    header('Location: student_list.php');
    exit;
}

// Invalid role
header('Location: login.php');
exit;

?>