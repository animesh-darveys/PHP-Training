<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once './config/db.php';

$role = '';
$username = '';
$password = '';
$roleError = '';
$usernameError = '';
$passwordError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $role = trim($_POST['role'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($role === '') {
        $roleError = 'Please Select Role';
    }
    if ($username === '') {
        $usernameError = 'Please Enter Username';
    }
    if ($password === '') {
        $passwordError = 'Please Enter Password';
    }

    if (empty($roleError) && empty($usernameError) && empty($passwordError)) {
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
            if ($role === 'customer') {
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
                        <label for="username" class="form-label">Username / Account Number</label>
                        <input type="text" value="<?= htmlspecialchars($username) ?>" class="form-control" id="username" name="username" placeholder="Enter username or account number">
                        <div id="usernameError" class="error <?= $usernameError ? 'show' : '' ?>">
                            <?= htmlspecialchars($usernameError) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
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
    <!-- <script>
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

const usernameInput = document.querySelector("#username");
const usernameError = document.querySelector("#usernameError");
function validateUsername() {
    if (!usernameInput) {
        return true;
    }
    const username = usernameInput.value.trim();
    if (username === "") {
        usernameError.textContent = "Username / Account Number is required.";
        usernameError.classList.add("show");
        return false;
    }

    if (username.length < 3 || username.length > 50) {
        usernameError.textContent =
            "Username must be between 3 and 50 characters.";
        usernameError.classList.add("show");
        return false;
    }
    usernameError.textContent = "";
    usernameError.classList.remove("show");
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
form.addEventListener("submit", function (event) {
    event.preventDefault();
    const isValidRole = validateRole();
    const isValidUsername = validateUsername();
    const isValidPassword = validatePassword();

    if ( !isValidRole || !isValidUsername || !isValidPassword ) {
        return;
    }
    form.submit();
});
</script> -->
</body>

</html>