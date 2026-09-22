<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "config/database.php";

if (isset($_SESSION['user_id'])) {
    return;
}

if (isset($_COOKIE['user_id'])) {

    $userId = $_COOKIE['user_id'];

    $stmt = $conn->prepare(
        "SELECT id, full_name, email
         FROM students
         WHERE id = :id"
    );

    $stmt->execute([':id' => $userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];

        return;
    }
}

header("Location: login.php");
exit;