
<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h4><?php echo ($mode === 'edit') ? '✏️ Edit Student Profile' : '📝 Register New Student'; ?></h4>

            <?php if (isset($errors['database'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errors['database']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <?php if ($mode === 'edit'){ ?>
                    <?php if (!empty($photo)): ?>
                       <div class="mb-3 text-center"> <img
                            src="<?= htmlspecialchars($photo, ENT_QUOTES, 'UTF-8') ?>"
                            width="80"
                            height="80"
                            style="object-fit: cover;"
                            class="rounded-circle"
                            alt="Student photo"
                        >
                       </div>
                    <?php endif; ?>
                <?php } ?>

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
                >
                <div class="mb-3">
                    <label class="form-label">Role</label>

                    <select class="form-select" name="role" <?php echo ($mode === 'edit') ? 'disabled' : ''; ?>>
                        <option value="admin"
                            <?= $role === 'admin' ? 'selected' : '' ?>>
                            Admin
                        </option>
                        <option value="student"
                            <?= $role === 'student' ? 'selected' : '' ?>>
                            Student
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>

                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        value="<?= htmlspecialchars($name) ?>"
                    >

                    <div class="text-danger small">
                        <?= htmlspecialchars($errors['studentName'] ?? '') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        value="<?= htmlspecialchars($email) ?>"
                    >

                    <div class="text-danger small">
                        <?= htmlspecialchars($errors['studentEmail'] ?? '') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>

                    <input
                        type="date"
                        class="form-control"
                        name="dob"
                        value="<?= htmlspecialchars($dob) ?>"
                    >

                    <div class="text-danger small">
                        <?= htmlspecialchars($errors['studentDOB'] ?? '') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Course</label>

                    <select class="form-select" name="course">
                        <option value="">Select Course</option>

                        <option value="web-development"
                            <?= $course === 'web-development' ? 'selected' : '' ?>>
                            Web Development
                        </option>

                        <option value="data-science"
                            <?= $course === 'data-science' ? 'selected' : '' ?>>
                            Data Science
                        </option>

                        <option value="cyber-security"
                            <?= $course === 'cyber-security' ? 'selected' : '' ?>>
                            Cyber Security
                        </option>
                    </select>

                    <div class="text-danger small">
                        <?= htmlspecialchars($errors['studentCourse'] ?? '') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Photo</label>

                    <input
                        type="file"
                        class="form-control"
                        name="photo"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <div class="text-danger small">
                        <?= htmlspecialchars($errors['studentImage'] ?? '') ?>
                    </div>
                </div>
                
                <?php if($mode==='create'){ ?>
                <div class="mb-3">
                    <label class="form-label" required>Password</label>
                    <input type="password" name="password" id="password" class="form-control">

                    <div class="invalid-feedback d-block text-danger small">
                        <?= htmlspecialchars($errors["password"] ?? "") ?>
                    </div>
                </div>
                <?php } ?>
                <button type="submit" class="btn btn-primary w-100">
                    <?php echo ($mode === 'edit') ? 'Update Student' : 'Register Student'; ?>
                </button>

            </form>

        </div>
    </div>

    <p class="text-center mt-3">
        <a href="student_list.php">View all students</a>
    </p>

</div>