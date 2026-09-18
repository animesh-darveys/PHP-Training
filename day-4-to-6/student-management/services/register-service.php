<?php
require_once "auth.php";
require_once "config/database.php";
require_once "services/insert-student.php";
require_once "services/register-validation.php";

$studentName = "";
$studentEmail = "";
$studentDOB = "";
$studentCourse = "";
$studentImage = "";
$studentPassword = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

 $csrfToken = $_POST["csrf_token"] ?? "";

    if (
        empty($csrfToken) ||
        empty($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $csrfToken)
    ) {
        die("Invalid CSRF token");
    }

    $studentName = trim($_POST["name"] ?? "");
    $studentEmail = trim($_POST["email"] ?? "");
    $studentDOB = trim($_POST["dob"] ?? "");
    $studentCourse = trim($_POST["course"] ?? "");
    $studentPassword = $_POST["password"] ?? "";

    // Validate student
    $errors = validateStudent(
        $studentName,
        $studentEmail,
        $studentDOB,
        $studentCourse,
        $_FILES["photo"] ?? null
    );

    if (empty($errors)) {
        
        require_once "config/database.php";

        $hashedPassword = password_hash(
            $studentPassword,
            PASSWORD_DEFAULT
        );

        try {

        $profilePhoto = null;

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {

            $fileName = $_FILES["photo"]["name"];
            $tmpName = $_FILES["photo"]["tmp_name"];

            $uploadDir = "uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = time() . "_" . basename($fileName);

            move_uploaded_file(
                $tmpName,
                $uploadDir . $newFileName
            );

            $profilePhoto = $uploadDir . $newFileName;
        }

        insertStudent(
            $conn,
            $studentName,
            $studentEmail,
            $hashedPassword,
            $studentDOB,
            $studentCourse,
            $profilePhoto
        );

        header("Location: student_list.php");
        exit;

        } catch (PDOException $e) {

        echo "Getting error during insertion.". $e->getMessage();
        echo "<br>";

        }
        }
    }

?>
