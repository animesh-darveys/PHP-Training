<?php
session_start();
require_once './config/db.php';

if (!isset($_SESSION['userid']) && isset($_COOKIE['remember_token'])) {

    $tokenHash = hash(
        'sha256',
        $_COOKIE['remember_token']
    );

    $stmt = $conn->prepare("
        SELECT UserID, Email, Role
        FROM users
        WHERE remember_token = :token
        LIMIT 1
    ");

    $stmt->execute([
        ':token' => $tokenHash
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        session_regenerate_id(true);

        $_SESSION['userid'] = $user['UserID'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['role'] = $user['Role'];

        if (strtolower($user['Role']) === 'admin') {
            header("Location: ./admin/list-customer.php");
            exit;
        }

        if (strtolower($user['Role']) === 'customer') {
            header("Location: ./customer/account-details.php");
            exit;
        }
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$role = '';
$email = '';
$password = '';
$roleError = '';
$emailError = '';
$passwordError = '';
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $role = trim($_POST['role'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($role === '') {
        $roleError = 'Please Select Role';
    }
    if ($email === '') {
        $emailError = 'Please Enter Email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Please Enter a valid Email';
    }
    if ($password === '') {
        $passwordError = 'Please Enter Password';
    }

    if (empty($roleError) && empty($emailError) && empty($passwordError)) {
        try {
            $sql = "SELECT u.UserID, u.Role, u.Email, u.Password, ci.CustomerID, ci.FullName
                    FROM users u
                    LEFT JOIN customer_info ci ON u.UserID = ci.UserID
                    WHERE u.Email = :email
                    LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':email' => $email
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Galat email, galat password ya galat role: teeno par ek hi message,
            // taaki kisi ko pata na chale ki kaunsa email registered hai.
            if (
                !$user ||
                !password_verify($password, $user['Password']) ||
                strtolower($role) !== strtolower($user['Role'])
            ) {
                $loginError = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $loginError = 'Something went wrong. Please try again.';
        }

        if ($loginError === '') {
            $_SESSION['userid'] = $user['UserID'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['name'] = $user['FullName'] ?? 'Admin';
            $_SESSION['role'] = $user['Role'];
            $_SESSION['CustomerID'] = $user['CustomerID'];

            $rememberToken = bin2hex(random_bytes(32));


            if (strtolower($user['Role']) === 'admin') {
                header("Location: ./admin/list-customer.php");
                exit;
            }

            header("Location: ./customer/account-details.php");
            exit;
        }
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
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .error.show {
            display: block;
        }

        .error:not(.show) {
            display: none;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Login</h3>

                <?php if ($loginError): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
                <?php endif; ?>

                <form action="" method="POST" id="login-form">

                    <div class="mb-3">
                        <label for="role" class="form-label">Login as</label>
                        <select class="form-select" id="role" name="role">
                            <option value="" disabled <?= $role === '' ? 'selected' : '' ?>>Select role</option>
                            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>Customer</option>
                        </select>
                        <div id="roleError" class="error <?= $roleError ? 'show' : '' ?>">
                            <?= htmlspecialchars($roleError) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" value="<?= htmlspecialchars($email) ?>" class="form-control" id="email" name="email" placeholder="Enter Email">
                        <div id="emailError" class="error <?= $emailError ? 'show' : '' ?>">
                            <?= htmlspecialchars($emailError) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted text-danger">(Note: First 4 letters of your name + birth year, e.g., ANIM1995)</small>
                        <div id="passwordError" class="error <?= $passwordError ? 'show' : '' ?>">
                            <?= htmlspecialchars($passwordError) ?>
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <button type="submit" class="btn btn-primary w-100">Login</button><br /><br />
                    <a href="./send-mail.php" class="btn btn-secondary w-100">Forgot Password</a>

                </form>

            </div>
        </div>
    </div>
    <script>
        const roleInput = document.querySelector("#role");

        const roleError = document.querySelector("#roleError");

        const allowedRoles = ["admin", "customer"];

        function validateRole() {
            if (!roleInput) {
                return true;
            }
            const role = roleInput.value.trim();
            if (!allowedRoles.includes(role)) {
                roleError.textContent = "Please select a valid role.";
                roleError.classList.add("show");
                return false;
            }
            roleError.textContent = "";
            roleError.classList.remove("show");
            return true;
        }

        const emailInput = document.querySelector("#email");
        const emailError = document.querySelector("#emailError");

        function validateEmail() {
            if (!emailInput) {
                return true;
            }
            const email = emailInput.value.trim();
            if (email === "") {
                emailError.textContent = "Email is required.";
                emailError.classList.add("show");
                return false;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.textContent = "Please enter a valid email address.";
                emailError.classList.add("show");
                return false;
            }
            emailError.textContent = "";
            emailError.classList.remove("show");
            return true;
        }

        const passwordInput = document.querySelector("#password");
        const passwordError = document.querySelector("#passwordError");

        function validatePassword() {
            if (!passwordInput) {
                return true;
            }
            const password = passwordInput.value;
            if (password === "") {
                passwordError.textContent = "Password is required.";
                passwordError.classList.add("show");
                return false;
            }
            if (password.length < 6) {
                passwordError.textContent = "Password must be at least 6 characters.";
                passwordError.classList.add("show");
                return false;
            }
            if (password.length > 100) {
                passwordError.textContent = "Password cannot exceed 100 characters.";
                passwordError.classList.add("show");
                return false;
            }
            passwordError.textContent = "";
            passwordError.classList.remove("show");
            return true;
        }

        const form = document.querySelector("#login-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            const isValidRole = validateRole();
            const isValidEmail = validateEmail();
            const isValidPassword = validatePassword();

            if (!isValidRole || !isValidEmail || !isValidPassword) {
                return;
            }
            form.submit();
        });
    </script>
</body>

</html>