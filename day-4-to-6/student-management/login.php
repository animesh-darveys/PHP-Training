
<!--
  LOGIN PAGE (Day 9)
  ----------------------------------------------------------
  Rename to login.php.
  - Uncomment the CSRF hidden input and populate from $_SESSION['csrf_token']
  - On failed login, use PRG: redirect back here, flash an error to
    $_SESSION['login_error'], read + clear it in PHP before rendering
  - On success: session_regenerate_id(), store user in $_SESSION, redirect
    to students_list.php
-->

<?php

session_start();

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare(
        "SELECT id, full_name, email, password
         FROM students
         WHERE email = :email"
    );

    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        // Prevent session fixation
        session_regenerate_id(true);

        // Store logged-in user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];

        // Redirect after successful login
        header('Location: student_list.php');
        exit;

    } else {

        echo "Invalid email or password";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
  <div class="container" style="max-width: 400px;">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3 text-center">Login</h4>

        <!-- PHP: echo flashed login error, then clear it from session -->
        <div class="alert alert-danger d-none" id="login-error-placeholder">
          <!-- PHP: echo $_SESSION['login_error'] ?? '' ; unset($_SESSION['login_error']); -->
          Invalid email or password.
        </div>

        <form method="POST" action="">
          <!-- PHP: <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> -->

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
