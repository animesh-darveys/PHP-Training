<?php
require_once './config/auth.php';
require_once './config/database.php';

if (isset($_SESSION['role'])) {
  if($_SESSION['role'] === 'student'){
    header('Location: student_detail.php');
    exit;
  }
  
  if($_SESSION['role'] === 'admin'){
    header('Location: student_list.php');
    exit;
  }
}

$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $database = new Database();
    $conn = $database->getConnection();

    $csrfToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (
        !is_string($csrfToken) ||
        !is_string($sessionToken) ||
        $csrfToken === '' ||
        $sessionToken === '' ||
        !hash_equals($sessionToken, $csrfToken)
    ) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $query =  "SELECT id, full_name, email, password, role
         FROM students_v2
         WHERE email = :email
         LIMIT 1";

    $stmt = $conn->prepare($query);

    $stmt->execute([
        ':email' => $email
    ]);


    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['uid'] = session_id();
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === "admin"){
          header('Location: student_list.php');
        }elseif($user['role'] === "student"){
          header('Location: student_detail.php');
        }

        exit;

    } else {

        $_SESSION['login_error'] = "Invalid email or password";

        header('Location: login.php');
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
        <h4 class="card-title mb-3 text-center">Login Form</h4>

        <?php if ($loginError): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($loginError); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
          </div>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>