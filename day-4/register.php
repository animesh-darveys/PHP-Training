<?php
$studentName = "";
$studentEmail = "";
$studentDOB = "";
$studentCourse = "";
$studentImage = "";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $studentName = trim($_POST["student_name"] ?? "");
    $studentEmail = trim($_POST["email"] ?? "");
    $studentDOB = trim($_POST["dob"] ?? "");
    $studentCourse = trim($_POST["course"] ?? "");
    $studentImage = $_FILES["photo"] ?? null;

    $allowedCourses = [
        "web-development",
        "data-science",
        "cyber-security"
    ];

        // Validate Student name
    if (empty($_POST["student_name"])) {
        $errors["studentName"] = "Student name is required.";
    } elseif (strlen($studentName) < 3) {
        $errors["studentName"] = "Student name should be greater then 3 charachter.";
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $errors["studentEmail"] = "Email is required.";
    } elseif (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {
        $errors["studentEmail"] = "Please enter a valid email address.";
    }

    // Validate DOB
    if (empty($_POST["dob"])) {
        $errors["studentDOB"] = "DOB is required.";
    } else {

        $dob = DateTime::createFromFormat("!Y-m-d", $studentDOB);

        if (
            $dob === false ||
            $dob->format("Y-m-d") !== $studentDOB
        ) {
            $errors["studentDOB"] = "Please enter a valid DOB.";
        } elseif ($dob > new DateTime("today")) {
            $errors["studentDOB"] = "DOB cannot be in the future.";
        } else {

            $today = new DateTime("today");
            $minimumAgeDate = (clone $today)->modify("-3 years");

            if ($dob > $minimumAgeDate) {
                $errors["studentDOB"] = "Student must be at least 3 years old to enroll.";
            }
        }
    }

    if (empty($_POST["course"])) {
        $errors["studentCourse"] = "Please select a course.";
    } elseif (!in_array($studentCourse, $allowedCourses, true)) {
        $errors["studentCourse"] = "Invalid course selected.";
    }
    
    if (
        !isset($_FILES["photo"]) ||
        $_FILES["photo"]["error"] === UPLOAD_ERR_NO_FILE
    ) {
        $errors["studentImage"] = "Please select an image.";
    }

    if (empty($errors)) {
      $success = "Student form submitted successfully.";
    }
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

        <!-- PHP: show once, from session flash (Day 9) or a $success flag -->
        <!-- <div class="alert alert-success">Student registered successfully.</div> -->
        <?php if (!empty($success)): ?>

            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Register Student</h4>

                <form method="POST" action="" enctype="multipart/form-data" novalidate>

                    <!-- PHP (Day 9): <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> -->

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="student_name"
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
                        <label class="form-label">Profile Photo (Day 6)</label>
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

        <!-- <p class="text-center mt-3">
            <a href="students_list.html">View all students</a>
            <a href="login.html">Login</a>
        </p> -->
    </div>
</body>

</html>