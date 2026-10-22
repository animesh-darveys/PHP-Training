<?php

$servername = "localhost";
$username = "root";
$password = null;
$dbname = "student_management";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connection done";
    echo "<br>";

} catch (PDOException $error) {
    die("Connection failed: " . $error->getMessage());
}

try {

    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        dob DATE NOT NULL,
        course VARCHAR(50) NOT NULL,
        profile_photo VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $conn->exec($sql);

    echo "Table has been created/already created";
    echo "<br>";

} catch (PDOException $e) {

    echo "Getting error during creating table: " . $e->getMessage();
    echo "<br>";

}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $full_name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $dob = trim($_POST["dob"] ?? "");
        $course = trim($_POST["course"] ?? "");

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {

    $fileName = $_FILES["photo"]["name"];
    $tmpName = $_FILES["photo"]["tmp_name"];

    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newFileName = time() . "_" . basename($fileName);

    move_uploaded_file(
        $tmpName,
        $uploadDir . $newFileName
    );

    $profilePhoto = $uploadDir . $newFileName;
}

        $insertSql = "INSERT INTO students(full_name, email, dob, course, profile_photo) VALUES (:full_name, :email, :dob, :course, :profile_photo)";

        $stmt = $conn->prepare($insertSql);

        $stmt->execute([
            ':full_name'     => $full_name,
            ':email'         => $email,
            ':dob'           => $dob,
            ':course'        => $course,
            ':profile_photo' => $profilePhoto
        ]);

        echo "Student Data Added successfully";
        echo "<br>";

    } catch (PDOException $e) {

        echo "Getting error during insertion.". $e->getMessage();
        echo "<br>";

    }
}

$read_sql = "SELECT * FROM students";

$stmt_select = $conn->prepare($read_sql);

$stmt_select->execute();

$students = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
?>
<table class="table table-bordered bg-white align-middle">
      <thead class="table-dark">
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Email</th>
          <th>Course</th>
          <th>DOB</th>
          <th style="width: 160px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) { ?>
        <tr>
          <td><img src="<?= htmlspecialchars($student["profile_photo"]) ?>" width="80" class="rounded-circle" alt="photo"></td>
          <td><?= $student["full_name"] ?></td>
          <td><?= $student["email"] ?></td>
          <td><?= $student["course"] ?></td>
          <td><?= $student["dob"] ?></td>
          <td>
            <a href="student_edit.html" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="delete_student.php" class="d-inline">
              <button type="submit" class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Delete this student?');">Delete</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>