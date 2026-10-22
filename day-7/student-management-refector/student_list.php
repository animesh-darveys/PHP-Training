<?php

require_once './config/auth.php';
require_once './config/database.php';
require_once './classes/Student.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$student = new Student($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student-delete-btn'])) {
        $studentId = (int) ($_POST['id'] ?? 0);

        if ($studentId > 0) {
            try {
                if ($student->delete($studentId)) {
                    $_SESSION['message'] = 'Student deleted successfully.';
                } else {
                    $_SESSION['message'] = 'Student could not be deleted.';
                }
            } catch (PDOException $e) {
                error_log('Student deletion error: ' . $e->getMessage());
                $_SESSION['message'] = 'An error occurred while deleting the student.';
            }
        }

        header('Location: student_list.php');
        exit;
    }
}

$course = trim($_GET['course'] ?? '');
$search = trim($_GET['search'] ?? '');

$limit = 8;
$page = (int) ($_GET['page'] ?? 1);

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$totalRecords = $student->countStudents($course, $search);
$totalPages = (int) ceil($totalRecords / $limit);

$students = $student->getAllStudent(
    $course,
    $search,
    $limit,
    $offset
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Student Management System</span>
    </div>
</nav>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Students</h4>
        <div class="d-flex" style="gap: 8px;">
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="register.php" class="btn btn-primary">+ Add Student</a>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success" id="successMessage"><?= htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['message']); ?>

        <script>
            setTimeout(function () {
                const message = document.getElementById('successMessage');
                if (message) {
                    message.style.display = 'none';
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" class="form-control" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="col-md-4">
            <select class="form-select" name="course">
                <option value="">All Courses</option>
                <option value="web-development" <?= $course === 'web-development' ? 'selected' : '' ?>>Web Development</option>
                <option value="data-science" <?= $course === 'data-science' ? 'selected' : '' ?>>Data Science</option>
                <option value="cyber-security" <?= $course === 'cyber-security' ? 'selected' : '' ?>>Cyber Security</option>
            </select>
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>DOB</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No students found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <?php if (!empty($student['profile_photo'])): ?>
                                    <img src="<?= htmlspecialchars($student['profile_photo'], ENT_QUOTES, 'UTF-8') ?>" width="60" height="60" style="object-fit: cover;" alt="Student photo">
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($student['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(ucwords(str_replace('-', ' ', $student['course'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['dob'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td>
                                <a href="student_detail.php?id=<?= (int) $student['id'] ?>" class="btn btn-sm btn-outline-success">View</a>
                                <a href="student_edit.php?id=<?= (int) $student['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
                                    <button type="submit" name="student-delete-btn" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item">
                        <a class="page-link <?= $page === $i ? 'active' : '' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&course=<?= urlencode($course) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

</body>
</html>