<?php
    require_once "../config/database.php";

    session_start();

    $profilePhoto = null;

    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {

        $fileName = $_FILES["photo"]["name"];
        $tmpName = $_FILES["photo"]["tmp_name"];

        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = time() . "_" . basename($fileName);

        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {

            $profilePhoto = "uploads/" . $newFileName;
        }
    }


    if(isset($_POST["student-update-btn"])){
        $studentId = $_POST['student_id'];
        $name = $_POST['student_name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $course = $_POST['course'];

        // echo $studendId."<br />";
        // echo $studendName."<br />";
        // echo $studendEmail."<br />";
        // echo $studendDOB."<br />";
        // echo $studendCourse."<br />";

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
    }
?>