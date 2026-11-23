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

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {
        try {
            $sql = "SELECT u.UserID, u.Email, c.FullName
                FROM users u
                JOIN customer_info c ON u.UserID = c.UserID
                WHERE u.Email = :email
                LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Email registered ho ya na ho, user ko hamesha yahi message dikhega,
            // taaki is page se pata na chale ki kaunsa email registered hai.
            $message = 'If this email is registered, a new password has been sent to it.';

            if ($user) {

                $newPassword = bin2hex(random_bytes(5)); // e.g. "a1b2c3d4e5"
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Password tabhi save ho jab email chala jaaye, warna user lock ho jaayega.
                $conn->beginTransaction();

                $updateSql = "UPDATE users SET Password = :password WHERE UserID = :userid";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([
                    ':password' => $hashedPassword,
                    ':userid'   => $user['UserID']
                ]);

                $mail = new PHPMailer(true);

                $env = parse_ini_file(__DIR__ . '/config/.env');

                try {
                    $mail->isSMTP();
                    $mail->Host       = $env['MAIL_HOST'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $env['MAIL_USERNAME'];
                    $mail->Password   = $env['MAIL_PASSWORD'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $env['MAIL_PORT'];

                    $mail->setFrom('animesh.gupta@darveys.com', 'Banking System');
                    $mail->addAddress($user['Email'], $user['FullName']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your New Password';
                    $mail->Body    = "Hi {$user['FullName']},<br><br>
                                      Your password has been reset. Your new password is: <b>{$newPassword}</b><br><br>
                                      Please log in and change your password immediately for security.<br><br>
                                      Regards,<br>Banking System";

                    $mail->send();
                    $conn->commit();

                } catch (Exception $e) {
                    // Mail nahi gaya: purana password hi rehne do, error sirf log mein.
                    $conn->rollBack();
                    error_log('Password reset mail failed: ' . $mail->ErrorInfo);
                }
            }

        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log($e->getMessage());
            $message = '';
            $error = 'Something went wrong. Please try again.';
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
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" required>
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