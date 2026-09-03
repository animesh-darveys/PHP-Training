<?php 
require_once "../config/database.php";
session_start();

if(isset($_POST["student-delete-btn"])){
echo "deleted";
$id= $_POST["id"];
// echo $id;

$sql = "DELETE FROM students WHERE id = :id";

$data = [
    ':id' => $id
];

$stmt = $conn->prepare($sql);
$execute_query = $stmt->execute($data);

if ($execute_query) {

            $_SESSION['message'] = "Deleted Successfully";

        } else {

            $_SESSION['message'] = "Not Deleted";
        }

        header('Location: ../student_list.php');
        exit;

}
?>