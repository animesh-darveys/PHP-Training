<?php
class Delete{

    function __construct(private PDO $conn){

        $this->conn = $conn;

    }

    function deleteStudent($id){
    $sql = "DELETE FROM students WHERE id = :id";

    $data = [
        ':id' => $id
    ];

    $stmt = $this->conn->prepare($sql);
    $execute_query = $stmt->execute($data);

    if ($execute_query) {

                $_SESSION['message'] = "Deleted Successfully";

            } else {

                $_SESSION['message'] = "Not Deleted";
            }

            header('Location: ../student_list.php');
            exit;

    }
}
?>