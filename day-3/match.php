<?php
$role = "editor";

$access = match ($role) {
    "admin" => "full",
    "editor" => "edit",
    default => "none",
};

echo $access;