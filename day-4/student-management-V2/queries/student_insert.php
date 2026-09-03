<?php
$insertSql = "INSERT INTO students(full_name, email, dob, course, profile_photo) VALUES (:full_name, :email, :dob, :course, :profile_photo)";

        $stmt = $conn->prepare($insertSql);

        $stmt->execute([
            ':full_name'     => $studentName,
            ':email'         => $studentEmail,
            ':dob'           => $studentDOB,
            ':course'        => $studentCourse,
            ':profile_photo' => $profilePhoto
        ]);