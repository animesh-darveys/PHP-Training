<?php

function validateStudent(
    string $studentName,
    string $studentEmail,
    string $studentDOB,
    string $studentCourse,
    array $photo
    ): array {

    $errors = [];

    $allowedCourses = [
        "web-development",
        "data-science",
        "cyber-security"
    ];

    // Validate Student Name
    if (empty($studentName)) {

        $errors["studentName"] = "Student name is required.";

    } elseif (strlen($studentName) < 3) {

        $errors["studentName"] =
            "Student name should be greater than 3 characters.";
    }


    // Validate Email
    if (empty($studentEmail)) {

        $errors["studentEmail"] = "Email is required.";

    } elseif (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {

        $errors["studentEmail"] =
            "Please enter a valid email address.";
    }


    // Validate DOB
    if (empty($studentDOB)) {

        $errors["studentDOB"] = "DOB is required.";

    } else {

        $dob = DateTime::createFromFormat("!Y-m-d", $studentDOB);

        if (
            $dob === false ||
            $dob->format("Y-m-d") !== $studentDOB
        ) {

            $errors["studentDOB"] =
                "Please enter a valid DOB.";

        } elseif ($dob > new DateTime("today")) {

            $errors["studentDOB"] =
                "DOB cannot be in the future.";
        }
    }


    // Validate Course
    if (empty($studentCourse)) {

        $errors["studentCourse"] =
            "Please select a course.";

    } elseif (!in_array($studentCourse, $allowedCourses, true)) {

        $errors["studentCourse"] =
            "Invalid course selected.";
    }


    // Validate Image
    if (
        $photo === null ||
        $photo["error"] === UPLOAD_ERR_NO_FILE
    ) {

        $errors["studentImage"] =
            "Please select an image.";
    }

    return $errors;
}