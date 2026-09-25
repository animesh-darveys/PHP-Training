<?php
session_start();
require_once './config/db.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $username = trim($_POST['username'] ?? '');

    if (empty($username)) {
        $error = 'Please enter your username or email.';
    } else {
        try {
            $sql = "SELECT u.UserID, u.Username, c.Email
                FROM users u
                JOIN customer_info c ON u.UserID = c.UserID
                WHERE u.Username = :username OR c.Email = :username
                LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = 'No account found with that username/email.';
            } else {

                $newPassword = bin2hex(random_bytes(5)); // e.g. "a1b2c3d4e5"
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updateSql = "UPDATE users SET Password = :password WHERE UserID = :userid";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([
                    ':password' => $hashedPassword,
                    ':userid'   => $user['UserID']
                ]);

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'animesh.gupta@darveys.com';
                    $mail->Password   = '16chars'; // app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('animesh.gupta@darveys.com', 'Banking System');
                    $mail->addAddress($user['Email'], $user['Username']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your New Password';
                    $mail->Body    = "Hi {$user['Username']},<br><br>
                                      Your password has been reset. Your new password is: <b>{$newPassword}</b><br><br>
                                      Please log in and change your password immediately for security.<br><br>
                                      Regards,<br>Banking System";

                    $mail->send();
                    $message = 'A new password has been sent to your registered email.';

                } catch (Exception $e) {
                    $error = "Password updated but email could not be sent. Error: {$mail->ErrorInfo}";
                }
            }

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Forgot Password</h3>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="mb-3">
                    <label for="username" class="form-label">Username or Email</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or email" required>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <button type="submit" class="btn btn-primary w-100">Send New Password</button>

            </form>

            <div class="text-center mt-3">
                <a href="login.php">Back to Login</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>