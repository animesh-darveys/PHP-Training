<?php
class Create{

    function __construct(private PDO $conn){

        $this->conn = $conn;

    }

    function insertStudent(
        string $name,
        string $email,
        string $dob,
        string $course,
        string $profilePhoto
    ): void {

        $sql = "INSERT INTO students
                (full_name, email, dob, course, profile_photo)
                VALUES
                (:full_name, :email, :dob, :course, :profile_photo)";

        $stmt = $this->conn->prepare($sql);

        $data= [
            ':full_name' => $name,
            ':email' => $email,
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

}