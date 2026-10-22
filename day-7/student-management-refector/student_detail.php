<?php

require_once './config/auth.php';
require_once './config/database.php';

if (empty($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$query = "
    SELECT id, full_name, email, role, profile_photo, dob, course
    FROM students_v2
    WHERE id = :id
    LIMIT 1
";

if (isset($_GET['id'])) {
    $studentId = (int) $_GET['id'];
} elseif (isset($_POST['student_id'])) {
    $studentId = (int) $_POST['student_id'];
}

$stmt = $conn->prepare($query);
if($_SESSION['role'] === 'student' ){
    $stmt->execute([
    ':id' => $_SESSION['user_id']
]);
}elseif($_SESSION['role'] === 'admin' && isset($studentId) ){
$stmt->execute([
    ':id' => $studentId
]);

}
else{
    header('Location:login.php');
    exit;
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    exit('User not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Student Management System</span>
        <a href="logout.php" class="logout-btn btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">

            <?php if (isset($errors['database'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors['database']) ?></div>
            <?php endif; ?>

            <div class="mb-3 text-center">
                <img src="<?= htmlspecialchars($user['profile_photo'], ENT_QUOTES, 'UTF-8') ?>" width="80" height="80" style="object-fit: cover;" class="rounded-circle" alt="Student photo">
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>

                <select class="form-select" name="role" disabled>
                    <option value=""><?= ucFirst(htmlspecialchars($user['role'])) ?></option>
                </select>

                <div class="text-danger small">
                    <?= htmlspecialchars($errors['studentCourse'] ?? '') ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                <div class="text-danger small"><?= htmlspecialchars($errors['studentName'] ?? '') ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                <div class="text-danger small"><?= htmlspecialchars($errors['studentEmail'] ?? '') ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($user['dob'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                <div class="text-danger small"><?= htmlspecialchars($errors['studentDOB'] ?? '') ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Course</label>
                <select class="form-select" name="course" disabled>
                    <option value="web-development" <?= $user['course'] === 'web-development' ? 'selected' : '' ?>>Web Development</option>
                    <option value="data-science" <?= $user['course'] === 'data-science' ? 'selected' : '' ?>>Data Science</option>
                    <option value="cyber-security" <?= $user['course'] === 'cyber-security' ? 'selected' : '' ?>>Cyber Security</option>
                </select>
                <div class="text-danger small"><?= htmlspecialchars($errors['studentCourse'] ?? '') ?></div>
            </div>

        </div>
    </div>
</div>

</body>
</html>