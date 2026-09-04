<?php

class Edit{

    function __construct(private PDO $conn){

        $this->conn = $conn;

    }

    public function findById($student_id){

        $query = "SELECT * FROM students WHERE id=:stud_id";

        $stmt = $this->conn->prepare($query);

        $data= [
            ':stud_id' =>$student_id
        ];

        $stmt->execute($data);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}
 