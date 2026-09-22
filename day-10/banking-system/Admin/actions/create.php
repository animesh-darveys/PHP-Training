<?php
require_once '../../config/db.php';

$name = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$accountType = trim($_POST['accountType'] ?? '');

function generatePassword($name, $dob) {
    $namePart = strtoupper(substr(preg_replace('/\s+/', '', $name), 0, 4));
    $year = date('Y', strtotime($dob));
    return $namePart . $year;
}

$password = generatePassword($name, $dob);