<?php

$name = "";
$email = "";
$salary = "";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | Get Input
    |--------------------------------------------------------------------------
    */

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $salary = trim($_POST["salary"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($name === "") {
        $errors["name"] = "Name is required.";
    } elseif (strlen($name) < 3) {
        $errors["name"] = "Name must be at least 3 characters.";
    }


    if ($email === "") {
        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Please enter a valid email.";
    }


    if ($salary === "") {
        $errors["salary"] = "Salary is required.";
    } elseif (!is_numeric($salary)) {
        $errors["salary"] = "Salary must be a number.";
    } elseif ($salary <= 0) {
        $errors["salary"] = "Salary must be greater than 0.";
    }


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $success = "Employee form submitted successfully.";

        // Database/API processing would happen here.
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Form</title>
</head>

<body>

<h2>Employee Form</h2>


<?php if ($success): ?>

    <p style="color: green;">
        <?= htmlspecialchars($success) ?>
    </p>

<?php endif; ?>


<form method="POST" action="">

    <div>

        <label>Name:</label>

        <input
            type="text"
            name="name"
            value="<?= htmlspecialchars($name) ?>"
        >

        <?php if (isset($errors["name"])): ?>

            <p style="color: red;">
                <?= htmlspecialchars($errors["name"]) ?>
            </p>

        <?php endif; ?>

    </div>


    <br>


    <div>

        <label>Email:</label>

        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($email) ?>"
        >

        <?php if (isset($errors["email"])): ?>

            <p style="color: red;">
                <?= htmlspecialchars($errors["email"]) ?>
            </p>

        <?php endif; ?>

    </div>


    <br>


    <div>

        <label>Salary:</label>

        <input
            type="text"
            name="salary"
            value="<?= htmlspecialchars($salary) ?>"
        >

        <?php if (isset($errors["salary"])): ?>

            <p style="color: red;">
                <?= htmlspecialchars($errors["salary"]) ?>
            </p>

        <?php endif; ?>

    </div>


    <br>

    <button type="submit">
        Submit
    </button>

</form>

</body>

</html>