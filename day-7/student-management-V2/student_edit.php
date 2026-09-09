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
        
        <form id="edit-form" method="POST" action="services/update-student" enctype="multipart/form-data">
          <input type="hidden" name="student_id" value="<?php echo $result->id; ?>">

          <div class="mb-3 text-center">
            <img src="<?= htmlspecialchars($result->profile_photo) ?>" width="80" height="80" style="object-fit: cover;" alt="photo" class="rounded-circle mb-2">
          </div>

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($result->full_name) ?>">
            <div id="nameError" class="invalid-feedback d-block text-danger small"></div>
          </div>
          

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($result->email) ?>">
            <div id="emailError" class="invalid-feedback d-block text-danger small"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($result->dob) ?>">
            <div id="dateError" class="invalid-feedback d-block text-danger small"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Course</label>
            <select class="form-select" name="course">
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
          <div id="courseError" class="invalid-feedback d-block text-danger small"></div>

          </div>
          
          <div class="mb-3">
            <label class="form-label">Replace Photo (optional)</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
            <div id="photoError" class="invalid-feedback d-block text-danger small"></div>
          </div>

          <button type="submit" class="btn btn-primary w-100" name="student-update-btn">Save Changes</button>
        </form>
      </div>
    </div>

    <p class="text-center mt-3"><a href="student_list.php">&larr; Back to list</a></p>
  </div>
  <script>
    const form = document.getElementById('edit-form');

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
            return true;
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
</body>
</html>