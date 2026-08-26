<?php

function insertStudent(
    PDO $conn,
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

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':full_name' => $name,
        ':email' => $email,
        ':dob' => $dob,
        ':course' => $course,
        ':profile_photo' => $profilePhoto
    ]);
}