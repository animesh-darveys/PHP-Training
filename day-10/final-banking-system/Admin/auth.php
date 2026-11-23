<?php

// Har Admin page ke sabse upar include karo:
//     require_once __DIR__ . '/auth.php';
// Login nahi hai to login page par bhejta hai, aur admin nahi hai to 403 deta hai.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['userid']) || empty($_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}

if (strtolower($_SESSION['role']) !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}
