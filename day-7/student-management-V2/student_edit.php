  <?php
    require_once "config/database.php";
    require_once "classes/Edit.php";

    $database = new Database();

    $conn = $database->getConnection();

    $edit = new Edit($conn);
 
    if(isset($_GET['id'])) {
      $student_id = $_GET['id'];
    }

    $result = $edit->findById($student_id);
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
      <span class="navbar-brand mb-0 h1">Student Management System</span>
    </div>
  </nav>

  <div class="container" style="max-width: 600px;">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Edit Student</h4>
        
        <form method="POST" action="services/update-student" enctype="multipart/form-data">
          <input type="hidden" name="student_id" value="<?php echo $result->id; ?>">

          <div class="mb-3 text-center">
            <img src="<?= htmlspecialchars($result->profile_photo) ?>" width="80" height="80" style="object-fit: cover;" alt="photo" class="rounded-circle mb-2">
          </div>

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="student_name"
                   value="<?= htmlspecialchars($result->full_name) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email"
                   value="<?= htmlspecialchars($result->email) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="dob"
                   value="<?= htmlspecialchars($result->dob) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Course</label>
            <select class="form-select" name="course" required>
              <option value="web-development" <?= $result->course === 'web-development' ? 'selected' : '' ?>>
                  Web Development
              </option>

              <option value="data-science" <?= $result->course === 'data-science' ? 'selected' : '' ?>>
                  Data Science
              </option>

              <option value="cyber-security" <?= $result->course === 'cyber-security' ? 'selected' : '' ?> >
                  Cyber Security
              </option>
          </select>

          </div>
          
          <div class="mb-3">
            <label class="form-label">Replace Photo (optional)</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
          </div>

          <button type="submit" class="btn btn-primary w-100" name="student-update-btn">Save Changes</button>
        </form>
      </div>
    </div>

    <p class="text-center mt-3"><a href="student_list.php">&larr; Back to list</a></p>
  </div>
</body>
</html>