<?php
class database{
    private string $servername = "localhost";
    private string $username = "root";
    private ?string $password = null;
    private string $dbname = "student_management";

    private PDO $conn;

    public function __construct(){
        try {
            $this->conn = new PDO("mysql:host={$this->servername};dbname={$this->dbname}", $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("Connection failed: " . $error->getMessage());
        } 
    }

    public function getConnection(): PDO {
        return $this->conn;
    }
}