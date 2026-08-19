<?php

$name = "";
$email = "";
$dob = "";
$course = "";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $course = trim($_POST["course"] ?? "");


    // Name validation

    if ($name == "") {

        $errors["name"] = "Name is required.";

    } elseif (strlen($name) < 3) {

        $errors["name"] = "Name must be at least 3 characters.";

    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {

        $errors["name"] = "Name can contain only letters and spaces.";
    }


    // Email validation

    if ($email == "") {

        $errors["email"] = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors["email"] = "Please enter a valid email.";
    }


    // DOB validation

    if ($dob == "") {

        $errors["dob"] = "Date of birth is required.";

    } else {

        $dobDate = DateTime::createFromFormat("Y-m-d", $dob);

        if (!$dobDate || $dobDate->format("Y-m-d") != $dob) {

            $errors["dob"] = "Please enter a valid date.";

        } elseif ($dobDate > new DateTime()) {

            $errors["dob"] = "Date of birth cannot be in the future.";
        }
    }


    // Course validation

    $courses = ["php", "laravel", "python", "javascript"];

    if ($course == "") {

        $errors["course"] = "Please select a course.";

    } elseif (!in_array($course, $courses)) {

        $errors["course"] = "Please select a valid course.";
    }


    // If no errors

    if (empty($errors)) {

        $success = "Student registered successfully.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Registration</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .form-box {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        .field {
            margin-bottom: 15px;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .success {
            color: green;
        }

        button {
            padding: 10px 20px;
        }

    </style>

</head>

<body>

<div class="form-box">

    <h2>Student Registration</h2>

    <?php if (isset($success)) { ?>

        <p class="success">
            <?= htmlspecialchars($success) ?>
        </p>

    <?php } ?>


    <form method="POST" action="">


        <div class="field">

            <label>Name</label>

            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
            >

            <?php if (isset($errors["name"])) { ?>

                <div class="error">
                    <?= htmlspecialchars($errors["name"]) ?>
                </div>

            <?php } ?>

        </div>


        <div class="field">

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
            >

            <?php if (isset($errors["email"])) { ?>

                <div class="error">
                    <?= htmlspecialchars($errors["email"]) ?>
                </div>

            <?php } ?>

        </div>


        <div class="field">

            <label>Date of Birth</label>

            <input
                type="date"
                name="dob"
                value="<?= htmlspecialchars($dob) ?>"
            >

            <?php if (isset($errors["dob"])) { ?>

                <div class="error">
                    <?= htmlspecialchars($errors["dob"]) ?>
                </div>

            <?php } ?>

        </div>


        <div class="field">

            <label>Course</label>

            <select name="course">

                <option value="">Select Course</option>

                <option
                    value="php"
                    <?= $course == "php" ? "selected" : "" ?>
                >
                    PHP
                </option>

                <option
                    value="laravel"
                    <?= $course == "laravel" ? "selected" : "" ?>
                >
                    Laravel
                </option>

                <option
                    value="python"
                    <?= $course == "python" ? "selected" : "" ?>
                >
                    Python
                </option>

                <option
                    value="javascript"
                    <?= $course == "javascript" ? "selected" : "" ?>
                >
                    JavaScript
                </option>

            </select>

            <?php if (isset($errors["course"])) { ?>

                <div class="error">
                    <?= htmlspecialchars($errors["course"]) ?>
                </div>

            <?php } ?>

        </div>


        <button type="submit">
            Register
        </button>

    </form>

</div>

</body>

</html>