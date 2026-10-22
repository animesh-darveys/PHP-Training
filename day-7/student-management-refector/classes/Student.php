<?php

class Student
{
    private string $name;
    private string $email;
    private string $dob;
    private string $course;
    private ?array $photo;
    private string $role;
    private string $password;
    private PDO $conn;

    public function __construct(
        PDO $conn,
        string $name = '',
        string $email = '',
        string $dob = '',
        string $course = '',
        ?array $photo = null,
        string $role = '',
        string $password = '',

    ) {
        $this->conn = $conn;
        $this->name = $name;
        $this->email = $email;
        $this->dob = $dob;
        $this->course = $course;
        $this->photo = $photo;
        $this->role = $role;
        $this->password = $password;
    }

    public function create(string $profilePhoto): bool
    {
        $sql = "INSERT INTO students_v2
                (full_name, email, dob, course, profile_photo, password, role)
                VALUES
                (:full_name, :email, :dob, :course, :profile_photo, :password, :role)";

        $stmt = $this->conn->prepare($sql);

        $data = [
            ':full_name' => $this->name,
            ':email' => $this->email,
            ':dob' => $this->dob,
            ':course' => $this->course,
            ':profile_photo' => $profilePhoto,
            ':password' => $this->password,
            ':role' => $this->role
        ];

        return $stmt->execute($data);
    }

    public function findById(int $studentId): object|false
    {
        $sql = "SELECT *
                FROM students_v2
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $studentId
        ]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllStudent(
        string $course = '',
        string $search = '',
        int $limit = 5,
        int $offset = 0
    ): array {
        $sql = "SELECT * FROM students_v2";
        $conditions = [];
        $params = [];

        if ($course !== '') {
            $conditions[] = "course = :course";
            $params[':course'] = $course;
        }

        if ($search !== '') {
            $conditions[] = "(full_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY created_at DESC
                  LIMIT :limit
                  OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // COUNT STUDENTS

    public function countStudents(
        string $course = '',
        string $search = ''
    ): int {
        $sql = "SELECT COUNT(*)
                FROM students_v2";

        $conditions = [];
        $params = [];

        if ($course !== '') {
            $conditions[] = "course = :course";
            $params[':course'] = $course;
        }

        if ($search !== '') {
            $conditions[] = "(full_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    // UPDATE

    public function update(
        int $studentId,
        ?string $profilePhoto = null
    ): bool {
        if ($profilePhoto !== null) {

            $sql = "UPDATE students_v2
                    SET
                        full_name = :full_name,
                        email = :email,
                        dob = :dob,
                        course = :course,
                        profile_photo = :profile_photo
                    WHERE id = :id";

            $data = [
                ':full_name' => $this->name,
                ':email' => $this->email,
                ':dob' => $this->dob,
                ':course' => $this->course,
                ':profile_photo' => $profilePhoto,
                ':id' => $studentId
            ];

        } else {

            $sql = "UPDATE students_v2
                    SET
                        full_name = :full_name,
                        email = :email,
                        dob = :dob,
                        course = :course
                    WHERE id = :id";

            $data = [
                ':full_name' => $this->name,
                ':email' => $this->email,
                ':dob' => $this->dob,
                ':course' => $this->course,
                ':id' => $studentId
            ];
        }

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($data);
    }


    public function delete(int $studentId): bool
    {
        $sql = "DELETE FROM students_v2
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $studentId
        ]);
    }


    public function validate(bool $photoRequired = true, bool $passwordRequired = true): array
    {
        $errors = [];

        $allowedCourses = [
            'web-development',
            'data-science',
            'cyber-security'
        ];

        // Name
        if ($this->name === '') {

            $errors['studentName'] = 'Student name is required.';

        } elseif (strlen($this->name) < 3) {

            $errors['studentName'] = 'Student name must be at least 3 characters.';

        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $this->name)) {

            $errors['studentName'] = 'Student name should contain only letters and spaces.';
        }

        // Email
        if ($this->email === '') {

            $errors['studentEmail'] = 'Email is required.';

        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {

            $errors['studentEmail'] = 'Please enter a valid email address.';
        }

        // DOB
        if ($this->dob === '') {

            $errors['studentDOB'] = 'DOB is required.';

        } else {

            $dob = DateTime::createFromFormat('!Y-m-d', $this->dob);

            if ($dob === false || $dob->format('Y-m-d') !== $this->dob) {

                $errors['studentDOB'] = 'Please enter a valid DOB.';

            } elseif ($dob > new DateTime('today')) {

                $errors['studentDOB'] = 'DOB cannot be in the future.';
            }
        }

        // Course
        if ($this->course === '') {

            $errors['studentCourse'] = 'Please select a course.';

        } elseif (!in_array($this->course, $allowedCourses, true)) {

            $errors['studentCourse'] = 'Invalid course selected.';
        }

        // Photo
        if ($photoRequired) {

            if ($this->photo === null || $this->photo['error'] === UPLOAD_ERR_NO_FILE) {

                $errors['studentImage'] = 'Please select an image.';

            } elseif ($this->photo['error'] !== UPLOAD_ERR_OK) {

                $errors['studentImage'] = 'There was a problem uploading the image.';

            } else {

                $this->validatePhotoFile($errors);
            }

        } elseif ($this->photo !== null && $this->photo['error'] !== UPLOAD_ERR_NO_FILE) {

            if ($this->photo['error'] !== UPLOAD_ERR_OK) {

                $errors['studentImage'] = 'There was a problem uploading the image.';

            } else {

                $this->validatePhotoFile($errors);
            }
        }

        // Password
        if ($passwordRequired) {

            if ($this->password === '') {
                $errors['password'] = 'Password is required.';

            } elseif (strlen($this->password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }
        }

        return $errors;
    }

    private function validatePhotoFile(array &$errors): void
    {
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];
        
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $fileType = $this->photo['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $errors['studentImage'] = 'Only JPG, PNG, or WEBP images are allowed.';
            return;
        }

        if ($this->photo['size'] > 2 * 1024 * 1024) {
            $errors['studentImage'] = 'Image size must be less than 2MB.';
        }
    }

    // UPLOAD PHOTO

    public function uploadPhoto(): ?string
    {
        if (
            $this->photo === null ||
            $this->photo['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        if ($this->photo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = dirname(__DIR__) . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($this->photo['name']);

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

        $newFileName = $fileNameWithoutExtension . '-' . time() . '.' . $extension;

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($this->photo['tmp_name'], $uploadPath)) {
            return 'uploads/' . $newFileName;
        }

        return null;
    }
}