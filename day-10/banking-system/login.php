<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once './config/db.php';

$role = '';
$username = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $role = trim($_POST['role'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die('User ID and password are required.');
    }

    try {
        $sql = "SELECT UserID, Role, Username, Password
                FROM users
                WHERE Username = :username
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $username
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('Invalid User ID or password.');
        }
        if($role === 'customer'){
        if (!password_verify($password, $user['Password'])) {
            die('Invalid User ID or password.');
        }
        }


        if (!empty($role) && strtolower($role) !== strtolower($user['Role'])) {
            die('Invalid role.');
        }

        $_SESSION['userid'] = $user['UserID'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['role'] = $user['Role'];

        if (strtolower($user['Role']) === 'admin') {
            header("Location: ./admin/list-customer.php");
            exit;

        } elseif (strtolower($user['Role']) === 'customer') {
            header("Location: ./customer/account-details.php");
            exit;

        } else {
            die('Unknown user role.');
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - Login</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Login</h3>

            <form action="login.php" method="POST">

                <div class="mb-3">
                    <label for="role" class="form-label">Login as</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" selected disabled>Select role</option>
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username / Account Number</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or account number" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <button type="submit" class="btn btn-primary w-100">Login</button><br /><br />
                <a href="./send-mail.php" class="btn btn-secondary w-100">Forgot Password</a>

            </form>

        </div>
    </div>
</div>

</body>
</html>