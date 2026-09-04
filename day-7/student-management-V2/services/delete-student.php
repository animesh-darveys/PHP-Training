<?php 
require_once "../config/database.php";
require_once "../classes/Delete.php";
session_start();

$database = new Database();
$conn = $database->getConnection();

$delete = new Delete($conn);

if(isset($_POST["student-delete-btn"])){
$id= $_POST["id"];
$delete->deleteStudent($id);

}
?>