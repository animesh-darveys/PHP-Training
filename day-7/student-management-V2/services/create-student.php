<?php
require_once "config/database.php";
require_once "classes/Student.php";

$studentName = "";
$studentEmail = "";
$studentDOB = "";
$studentCourse = "";

$database = new Database();
$conn = $database->getConnection();

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

    $photo = $_FILES["photo"] ?? null;

    $student = new Student(
        $studentName,
        $studentEmail,
        $studentDOB,
        $studentCourse,
        $photo,
        $conn
    );

    $errors = $student->validate();

    if (empty($errors)) {
        
        try {

        $profilePhoto = $student->uploadPhoto();

        $result = $student->create(
            $studentName,
            $studentEmail,
            $studentDOB,
            $studentCourse,
            $profilePhoto
        );

        if ($result) {
            $_SESSION['message'] = "Inserted Successfully";
            header('Location: student_list.php');
            exit;
        }

        $_SESSION['message'] = "Not Inserted";
        header('Location: student_list.php');
        exit;

        } catch (PDOException $e) {

        echo "Getting error during insertion.". $e->getMessage();
        echo "<br>";

        }
        }
    }

?>
