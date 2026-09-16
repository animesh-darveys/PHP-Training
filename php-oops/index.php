<?php

require_once __DIR__ . "/vendor/autoload.php";


use Anime\PhpOops\Models\Admin;
use Anime\PhpOops\Models\Staff;

$admin = new Admin();
$staff = new Staff();


$admin->hello();

echo "<br>";

$staff->hello();