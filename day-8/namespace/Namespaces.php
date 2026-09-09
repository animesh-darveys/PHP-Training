<?php
require "Admin.php";
require "Customer.php";

$user = new Admin\User();

echo $user->User();

$user = new customer\User();

echo $user->User();
?>