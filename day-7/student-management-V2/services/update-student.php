<?php
    require_once "../config/database.php";
    require_once "../classes/Student.php";

    $database = new Database();
    $conn = $database->getConnection();

    session_start();

    $profilePhoto = null;

    if(isset($_POST["student-update-btn"])){
        $studentId = $_POST['student_id'];
        $name = $_POST['student_name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $course = $_POST['course'];

        $photo = $_FILES['photo'] ?? null;


        $student = new Student(
            $name,
            $email,
            $dob,
            $course,
            $photo
        );


        $errors = $student->validate();

        if ($photo === null || $photo['error'] === UPLOAD_ERR_NO_FILE ) {

            unset($errors['studentImage']);

        }


        $profilePhoto = $student->uploadPhoto();

        if (empty($errors)) {

        try {

        if ($profilePhoto) {

            // New photo uploaded, so update photo too
            $sql = "UPDATE students 
                    SET full_name = :full_name,
                        email = :email,
                        dob = :dob,
                        course = :course,
                        profile_photo = :profile_photo
                    WHERE id = :id";

            $data = [
                ':full_name' => $name,
                ':email' => $email,
                ':dob' => $dob,
                ':course' => $course,
                ':profile_photo' => $profilePhoto,
                ':id' => $studentId
            ];

        } else {

            // No new photo, keep existing photo
            $sql = "UPDATE students 
                    SET full_name = :full_name,
                        email = :email,
                        dob = :dob,
                        course = :course
                    WHERE id = :id";

            $data = [
                ':full_name' => $name,
                ':email' => $email,
                ':dob' => $dob,
                ':course' => $course,
                ':id' => $studentId
            ];
        }

        $stmt = $conn->prepare($sql);
        $execute_query = $stmt->execute($data);

        if ($execute_query) {

            $_SESSION['message'] = "Updated Successfully";

        } else {

            $_SESSION['message'] = "Not Updated";
        }

        header('Location: ../student_list.php');
        exit;

    } catch (PDOException $e) {

        echo "Error updating record: " . $e->getMessage();
    }
    }else{
        header('Location: ../student_edit.php?id=' . $studentId);
        exit;
    }
    }
?>