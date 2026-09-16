<?php
require_once "Admin.php";
require_once "Customer.php";

$user = new Admin\User();

echo $user->User();

$user = new customer\User();

echo $user->User();
?>