<?php
function insertStudent(
    PDO $conn,
    string $name,
    string $email,
    string $password,
    string $dob,
    string $course,
    string $profilePhoto
): void {

    $sql = "INSERT INTO students
            (full_name, email, password, dob, course, profile_photo)
            VALUES
            (:full_name, :email, :password, :dob, :course, :profile_photo)";

    $stmt = $conn->prepare($sql);

    $data = [
        ':full_name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':dob' => $dob,
        ':course' => $course,
        ':profile_photo' => $profilePhoto
    ];

    $execute_query = $stmt->execute($data);

    if($execute_query){

        $_SESSION['message'] = "Inserted Successfully";
        header('Location: student_list.php');
        exit;

    } else{

        $_SESSION['message'] = "Not Inserted";
        header('Location: student_list.php');
        exit;

    }

}