<?php
require_once __DIR__ . '/../config/db.php';

$db = new Database();

class Students{
    function create(){
        echo 'Create Function <br>';
    }
    function find(){
        echo 'Find by id Function <br>';
    }
    function getAll(){
        echo 'Get All Function <br>';
    }
    function update(){
        echo 'Update Function <br>';
    }
    function delete(){
        echo 'Delete Function <br>';
    }
}
?>