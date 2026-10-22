
<?php

session_start();

if (empty($_SESSION["csrf_token"])) {

    $randomBytes = random_bytes(32);

    $_SESSION["csrf_token"] = bin2hex($randomBytes);
}

print_r($_SESSION);
echo "<pre>";
print_r($_POST);
echo "</pre>";
?>

<form method="POST">
    <input type="text" value="Animesh" name="name">
    <input type="hidden" value="<?= $_SESSION['csrf_token'] ?>" name="csrf_token">
    <button type="submit">Submit</button>
</form>