<?php
$name = '';
$email = '';
$dob = '';
$course = '';
$password = '';
$role = '';
$errors = [];
$photo = null;
$result = null;

if ($mode === 'edit') {
    if (isset($_GET['id'])) {
        $studentId = (int) $_GET['id'];
    } elseif (isset($_POST['student_id'])) {
        $studentId = (int) $_POST['student_id'];
    }
}

$isCreate = ($mode === 'create');
$isEdit = ($mode === 'edit');

if (!$isCreate && !$isEdit) {
    die('Invalid operation mode.');
}

$database = new Database();
$conn = $database->getConnection();

if ($isEdit) {

    if (!isset($studentId) || $studentId <= 0) {
        die('Invalid student ID.');
    }

    $student = new Student($conn);

    $result = $student->findById($studentId);

    if (!$result) {
        die('Student not found.');
    }

    $name = $result->full_name;
    $email = $result->email;
    $dob = $result->dob;
    $course = $result->course;
    $role = $result->role;
    $photo = $result->profile_photo;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        empty($csrfToken) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $csrfToken)
    ) {
        die('Invalid CSRF token.');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $password = $_POST['password'] ?? '';
    $photo = $_FILES['photo'] ?? null;

    if ($isCreate) {
        $role = trim($_POST['role'] ?? '');
    }

    $student = new Student(
        $conn,
        $name,
        $email,
        $dob,
        $course,
        $photo,
        $role,
        $password
    );

    $errors = $student->validate(
        $isCreate,
        $isCreate
    );

    if (empty($errors)) {

        try {


            if ($isCreate) {

                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $student = new Student(
                    $conn,
                    $name,
                    $email,
                    $dob,
                    $course,
                    $photo,
                    $role,
                    $hashedPassword
                );

                $profilePhoto = $student->uploadPhoto();

                if ($profilePhoto === null) {

                    $errors['studentImage'] =
                        'Photo upload failed.';
                } elseif ($student->create($profilePhoto)) {

                    $_SESSION['message'] =
                        'Student inserted successfully.';

                    header('Location: student_list.php');
                    exit;
                } else {

                    $errors['database'] =
                        'Student could not be inserted.';
                }
            } elseif ($isEdit) {

                $profilePhoto = $result->profile_photo;

                if (
                    isset($_FILES['photo']) &&
                    $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE
                ) {

                    $uploadedPhoto = $student->uploadPhoto();

                    if ($uploadedPhoto === null) {

                        $errors['studentImage'] =
                            'Photo upload failed.';
                    } else {

                        $profilePhoto = $uploadedPhoto;
                    }
                }

                if (empty($errors)) {

                    if ($student->update($studentId, $profilePhoto)) {

                        $_SESSION['message'] =
                            'Student updated successfully.';

                        header('Location: student_list.php');
                        exit;
                    } else {

                        $errors['database'] =
                            'Student could not be updated.';
                    }
                }
            }
        } catch (PDOException $e) {

            error_log($e->getMessage());

            if ($e->getCode() === '23000' && isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                $errors['studentEmail'] = 'This email is already registered. Please use a different email.';
            } else {
                $errors['database'] = 'A database error occurred. Please try again.';
            }
        }
    }

    if ($isEdit) {
        $result->full_name = $name;
        $result->email = $email;
        $result->dob = $dob;
        $result->course = $course;
    }
}
