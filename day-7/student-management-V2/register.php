<?php
require_once './config/auth.php';
require_once "services/create-student.php";
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

                <form id="register-form" method="POST" action="" enctype="multipart/form-data" novalidate>

                   <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($studentName) ?>" required>
                        <div id="nameError" class="invalid-feedback d-block text-danger small">
                            <?php if (isset($errors["studentName"])): ?>
                            <?= htmlspecialchars($errors["studentName"]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= htmlspecialchars($studentEmail) ?>" required>
                        <div id="emailError" class="invalid-feedback d-block text-danger small">
                            <?php if (isset($errors["studentEmail"])): ?>
                            <?= htmlspecialchars($errors["studentEmail"]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($studentDOB) ?>"
                            required>
                        <div id="dateError" class="invalid-feedback d-block text-danger small">
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

                        <div id="courseError" class="invalid-feedback d-block text-danger small">
                            <?= htmlspecialchars($errors["studentCourse"] ?? "") ?>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <div id="photoError" class="invalid-feedback d-block text-danger small">
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

<script>
    const form = document.getElementById('register-form');

    function validateName() {
        const nameInput = document.querySelector('input[name="name"]');
        const nameError = document.getElementById('nameError');
        const name = nameInput.value.trim();

        if (name === '') {
            nameError.textContent = 'Name is required.';
            return false;
        } else if (name.length < 3) {
            nameError.textContent = 'Name must be at least 3 characters.';
            return false;
        } else if (!/^[a-zA-Z\s]+$/.test(name)) {
            nameError.textContent = 'Name should only contain letters and spaces.';
            return false;
        } else {
            nameError.textContent = '';
            return true;
        }
    }
    function validateEmail() {
        const emailInput = document.querySelector('input[name="email"]');
        const emailError = document.getElementById('emailError');
        const email = emailInput.value.trim();
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (email === '') {
            emailError.textContent = 'Email is required....';
            return false;
        } else if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address.';
            return false;
        } else {
            emailError.textContent = '';
            return true;
        }
    }

    function validateDOB() {
        const dobInput = document.querySelector('input[name="dob"]');
        const dateError = document.getElementById('dateError');
        const dob = dobInput.value;

        if (dob === '') {
            dateError.textContent = 'Date of birth is required.';
            return false;
        } else {
            dateError.textContent = '';
            return true;
        }
    }

    function validateCourse() {
        const courseInput = document.querySelector('select[name="course"]');
        const courseError = document.getElementById('courseError');

        if (courseInput.value === '') {
            courseError.textContent = 'Please select a course.';
            return false;
        } else {
            courseError.textContent = '';
            return true;
        }
    }

    function validatePhoto() {
        const photoInput = document.querySelector('input[name="photo"]');
        const photoError = document.getElementById('photoError');

        if (photoInput.files.length === 0) {
            photoError.textContent = 'Profile photo is required.';
            return false;
        }

        const file = photoInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        const maxSizeMB = 2;
        console.log(photoInput.files);


        if (!allowedTypes.includes(file.type)) {
            photoError.textContent = 'Only JPG, PNG, or WEBP images are allowed.';
            return false;
        } else if (file.size > maxSizeMB * 1024 * 1024) {
            photoError.textContent = `Image size must be less than ${maxSizeMB}MB.`;
            return false;
        } else {
            photoError.textContent = '';
            return true;
        }
    }

    form.addEventListener('submit', function (e) {
        const nameOk = validateName();
        const emailOk = validateEmail();
        const dobOk = validateDOB();
        const courseOk = validateCourse();
        const photoOk = validatePhoto();

        if (!nameOk || !emailOk || !dobOk || !courseOk || !photoOk) {
            e.preventDefault();
        }
    });

</script>

</html>