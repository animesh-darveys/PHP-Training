
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

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

      $csrfToken = $_POST['csrf_token'] ?? '';

      if (
          empty($csrfToken) ||
          !hash_equals($_SESSION['csrf_token'], $csrfToken)
      ) {
          die("Invalid CSRF token");
      }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $query = "SELECT id, full_name, email, password FROM students WHERE email = :email";
    $stmt = $conn->prepare($query);

    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {


        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $session_id = session_regenerate_id(true);

        $_SESSION['uid'] = session_id();


        $cookieName = "username";
        $cookieValue = $user['full_name'];
        setcookie($cookieName, $cookieValue, time() + (86400 * 30), "/");

        $cookieName = "user_id";
        $cookieValue = $user['id'];
        setcookie($cookieName, $cookieValue, time() + (86400 * 30), "/");

        header('Location: student_list.php');

        exit;

    } else {
      $_SESSION['login_error'] = "Invalid email or password";

      header("Location: login.php");
      exit;
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

        <?php if ($loginError): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($loginError); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>