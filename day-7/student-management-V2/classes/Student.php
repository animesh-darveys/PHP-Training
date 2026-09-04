<?php

class Student { 

    public function __construct(
        private string $name,
        private string $email,
        private string $dob,
        private string $course,
        private ?array $photo
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->dob = $dob;
        $this->course = $course;
        $this->photo = $photo;
    }

    public function validate(): array{
        $errors = [];

        $allowedCourses = [
            "web-development",
            "data-science",
            "cyber-security"
        ];

        if (empty($this->name)) {

            $errors["studentName"] = "Student name is required.";

        } elseif (strlen($this->name) < 3) {

            $errors["studentName"] = "Student name should be greater than 3 characters.";
        }


        if (empty($this->email)) {

            $errors["studentEmail"] = "Email is required.";

        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {

            $errors["studentEmail"] = "Please enter a valid email address.";
        }


        if (empty($this->dob)) {

            $errors["studentDOB"] = "DOB is required.";

        } else {

            $dob = DateTime::createFromFormat("!Y-m-d", $this->dob);

            if ($dob === false || $dob->format("Y-m-d") !== $this->dob) {

                $errors["studentDOB"] = "Please enter a valid DOB.";

            } elseif ($dob > new DateTime("today")) {

                $errors["studentDOB"] = "DOB cannot be in the future.";
            }
        }


        if (empty($this->course)) {

            $errors["studentCourse"] = "Please select a course.";

        } elseif (!in_array($this->course, $allowedCourses, true)) {

            $errors["studentCourse"] = "Invalid course selected.";
        }


        if ($this->photo === null || $this->photo["error"] === UPLOAD_ERR_NO_FILE) {
            $errors["studentImage"] = "Please select an image.";
        }

        return $errors;
    }

    public function uploadPhoto(): ? string {
        if ($this->photo === null || $this->photo["error"] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileName = $this->photo["name"];
        $tmpName = $this->photo["tmp_name"];

        // $uploadDir = "uploads/";
            $uploadDir = dirname(__DIR__) . "/uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = time() . "_" . basename($fileName);

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {
            return "uploads/" . $newFileName;
        }

        return null;
    }

}