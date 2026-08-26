<?php
session_start();
require_once "services/register-service.php";
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Student Management System</span>
        </div>
    </nav>

    <div class="container" style="max-width: 600px;">

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Register Student</h4>

                <form method="POST" action="" enctype="multipart/form-data" novalidate>

                    
                   <input type="hidden"
           name="csrf_token"
           value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
                   <?php /* */?>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name"
                            value="<?= htmlspecialchars($studentName) ?>" required>
                        <div class="invalid-feedback d-block text-danger small">

                            <?php if (isset($errors["studentName"])): ?>
                            <?= htmlspecialchars($errors["studentName"]) ?>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= htmlspecialchars($studentEmail) ?>" required>
                        <div class="invalid-feedback d-block text-danger small">
                            <?php if (isset($errors["studentEmail"])): ?>
                            <?= htmlspecialchars($errors["studentEmail"]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($studentDOB) ?>"
                            required>
                        <div class="invalid-feedback d-block text-danger small">
                            <?php if (isset($errors["studentDOB"])): ?>
                            <?= htmlspecialchars($errors["studentDOB"]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course" required>
                            <option value="">Select Course</option>

                            <option value="web-development" <?=$studentCourse==="web-development" ? "selected" : "" ?>
                                >
                                Web Development
                            </option>

                            <option value="data-science" <?=$studentCourse==="data-science" ? "selected" : "" ?>
                                >
                                Data Science
                            </option>

                            <option value="cyber-security" <?=$studentCourse==="cyber-security" ? "selected" : "" ?>
                                >
                                Cyber Security
                            </option>
                        </select>

                        <div class="invalid-feedback d-block text-danger small">
                            <?= htmlspecialchars($errors["studentCourse"] ?? "") ?>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <div class="invalid-feedback d-block text-danger small">
                            <?php if (isset($errors["studentImage"])): ?>
                            <?= htmlspecialchars($errors["studentImage"]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>

        <p class="text-center mt-3">
            <a href="student_list.php">View all students</a>
            <a href="login.php">Login</a>
        </p>
    </div>
</body>

</html>