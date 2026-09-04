<?php

require_once "config/database.php";
require_once "classes/StudentReader.php";

session_start();

$database = new Database();
$conn = $database->getConnection();

$studentReader = new StudentReader($conn);

$course = $_GET["course"] ?? "";
$search = $_GET["search"] ?? "";

$limit = 5;
$page = $_GET["page"] ?? 1;

$offset = ($page - 1) * $limit;

$totalRecords = $studentReader->countStudents(
    $course,
    $search
);

$total_page = ceil($totalRecords / $limit);

$students = $studentReader->getStudents(
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
      <span class="navbar-brand mb-0 h1">Student Management System</span>
      <!-- PHP (Day 9): show logged-in user + logout link -->
      <a href="logout.html" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </nav>

  <div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Students</h4>
      <a href="register.php" class="btn btn-primary">+ Add Student</a>
    </div>

    <form method="GET" action="" class="row g-2 mb-3">
      <div class="col-md-5">
        <input type="text" class="form-control" name="search" placeholder="Search by name"
               value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>">
      </div>
      <div class="col-md-4">
        <select class="form-select" name="course">
          <option value="">All Courses</option>
          <option value="web-development" <?= $course === "web-development" ? 'selected' : '' ?>>Web Development</option>
          <option value="data-science" <?= $course === "data-science" ? 'selected' : '' ?>>Data Science</option>
          <option value="cyber-security" <?= $course === "cyber-security" ? 'selected' : '' ?>>Cyber Security</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-secondary w-100">Filter</button>
      </div>
    </form>
    <?php if (isset($_SESSION['message'])) : ?>
        <h5 class="alert alert-success" id="successMessage"><?php echo $_SESSION['message']; ?></h5>
        <?php unset($_SESSION['message']); ?>
        <script>
            setTimeout(() => {
                const message = document.getElementById('successMessage');

                if (message) {
                    message.style.display = 'none';
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <table class="table table-bordered bg-white align-middle">
      <thead class="table-dark">
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Email</th>
          <th>Course</th>
          <th>DOB</th>
          <th style="width: 160px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) { ?>
        <tr>
          <td><img src="<?= htmlspecialchars($student["profile_photo"]) ?>" width="80" height="80" style="object-fit: cover;" alt="photo"></td>
          <td><?= ucwords($student["full_name"]) ?></td>
          <td><?= strtolower($student["email"]) ?></td>
          <td><?= ucwords(str_replace('-', ' ', $student["course"])) ?></td>
          <td><?= $student["dob"] ?></td>
          <td>
            <a href="student_edit.php?id=<?= $student["id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="services/delete-student.php" class="d-inline">
              <input type="hidden" name="id" value="<?= $student["id"] ?>" />
              <button type="submit" class="btn btn-sm btn-outline-danger" name="student-delete-btn"
                      onclick="return confirm('Delete this student?');">Delete</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>  

    <!-- Day 6: Pagination -->
    <nav>
      <ul class="pagination justify-content-center">
         <?php for($i = 1; $i<= $total_page; $i++ ): ?>
         <li class="page-item"><a class="page-link <?= $page == $i ? 'active' : '' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&course=<?= urlencode($course) ?>"><?php echo $i ?></a></li>
         <?php endfor ?>
      </ul>
    </nav>

  </div>
</body>
</html>
