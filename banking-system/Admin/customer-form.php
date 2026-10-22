<?php
require_once '../config/db.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$name = '';
$email = '';
$phone = '';
$address = '';
$dob = '';
$accountType = '';

$nameError = '';
$emailError = '';
$phoneError = '';
$addressError = '';
$dobError = '';
$accountTypeError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if($_SESSION['csrf_token'] !== $_POST['csrf_token']){
        die('Invalid CRSF Token');
    }

    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $accountType = trim($_POST['accountType'] ?? '');

    function generatePassword($name, $dob){
        $namePart = strtoupper(substr(preg_replace('/\s+/', '', $name), 0, 4));
        $year = date('Y', strtotime($dob));
        return $namePart . $year;
    }

    $password = generatePassword($name, $dob);

    if ($name === '') {
        $nameError = "This field is required.";
    } elseif (!preg_match('/^[A-Za-z ]{3,50}$/', $name)) {
        $nameError = "Enter at least 3 characters, maximum 50.";
    }

    if ($email === '') {
        $emailError = "This field is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Enter a valid email address.";
    }

    if ($phone === '') {
        $phoneError = "This field is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phoneError = "Phone number must be exactly 10 digits.";
    }

    if ($address === '') {
        $addressError = "This field is required.";
    } elseif (strlen($address) < 5) {
        $addressError = "Address must be at least 5 characters.";
    } elseif (strlen($address) > 200) {
        $addressError = "Address cannot exceed 200 characters.";
    }

    if ($dob === '') {
        $dobError = "This field is required.";
    } elseif (strtotime($dob) === false) {
        $dobError = "Enter a valid date.";
    } elseif (strtotime($dob) > time()) {
        $dobError = "Date of birth cannot be in the future.";
    }

    $allowedAccountTypes = ['savings', 'current'];
    if ($accountType === '') {
        $accountTypeError = "This field is required.";
    } elseif (!in_array($accountType, $allowedAccountTypes)) {
        $accountTypeError = "Please select a valid account type.";
    }

    $hasErrors = $nameError || $emailError || $phoneError || $addressError || $dobError || $accountTypeError;

    if (!$hasErrors) {
        try {
            $conn->beginTransaction();
            $sqlUsers = "INSERT INTO users (Role, Username, Password) 
                                VALUES (:rolePlaceholder, :usenamePlaceholder, :passwordPlaceholder)";
            $stmtUsers = $conn->prepare($sqlUsers);
            $role = 'Customer';
            $userName = $name;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmtUsers->execute([
                'rolePlaceholder' => $role,
                'usenamePlaceholder' => $userName,
                'passwordPlaceholder' => $hashedPassword,
            ]);

            $userId = $conn->lastInsertId();
            
            $accountNumber = 'SBI1001'.$userId;

            $sqlCustomerInfo = "INSERT INTO customer_info (UserID, AccountNumber, FullName, Email, PhoneNumber, Address, DateOfBirth, AccountType) 
                    VALUES (:userIdPlaceholder, :AccountNumberPlaceholder, :fullNamePlaceholder, :emailPlaceholder, :phonePlaceholder, :addressPlaceholder, :dobPlaceholder, :accountTypePlaceholder)";
            $stmt = $conn->prepare($sqlCustomerInfo);

            $data = [
                'userIdPlaceholder' => $userId,
                'AccountNumberPlaceholder'    => $accountNumber,
                'fullNamePlaceholder'=> $name,
                'emailPlaceholder' => $email,
                'phonePlaceholder'    => $phone,
                'addressPlaceholder'    => $address,
                'dobPlaceholder'    => $dob,
                'accountTypePlaceholder'    => $accountType
            ];

            $stmt->execute($data);

            $customerId = $conn->lastInsertId();
            $accountBalance = 200000;

            $sqlAccountBalance = "INSERT INTO account_balance (CustomerID, BalanceAmount) 
                                VALUES (:customerIdPlaceholder, :accountBalancePlaceholder)";
            $stmtAccountBalance = $conn->prepare($sqlAccountBalance);

            $stmtAccountBalance->execute([
                'customerIdPlaceholder' => $customerId,
                'accountBalancePlaceholder' => $accountBalance
            ]);
            $conn->commit();
            $_SESSION['success'] = "Account created successfully! Your account number is " . $accountNumber;

            header("Location: ./list-customer.php");
            exit;

        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION['error'] = "Database Error: " . $e->getMessage();
            header("Location: ./create.php"); // ya jo current form page hai
            exit;
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System - Create Customer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet" />
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 100%; max-width: 700px;">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Create Customer</h3>

                <form action="" method="POST" id="customer-form">

                    <!-- Customer ID: not a form field - auto-assigned as FK after Users record is created -->
                    <!-- Account Number: not a form field - auto-generate on the PHP side (e.g. random/sequential unique number) -->

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="fullname" value="<?= htmlspecialchars($name) ?>">
                        <span id="nameError" class="error <?= $nameError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($nameError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        <span id="emailError" class="error <?= $emailError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($emailError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="10-digit number" value="<?= htmlspecialchars($phone) ?>">
                        <span id="phoneError" class="error <?= $phoneError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($phoneError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" style="resize: none;"><?= htmlspecialchars($address) ?></textarea></textarea>
                        <span id="addressError" class="error <?= $addressError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($addressError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($dob) ?>">
                        <span id="dobError" class="error <?= $dobError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($dobError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="accountType" class="form-label">Account Type</label>
                        <select class="form-select" id="accountType" name="accountType">
                            <option value="" disabled <?= empty($accountType) ? 'selected' : '' ?>>Select account type</option>
                            <option value="savings" <?= $accountType === 'savings' ? 'selected' : '' ?>>Savings</option>
                            <option value="current" <?= $accountType === 'current' ? 'selected' : '' ?>>Current</option>
                        </select>
                        <span id="accountTypeError" class="error <?= !empty($accountTypeError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($accountTypeError) ?></span>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <button type="submit" class="btn btn-primary w-100">Create Customer</button>
                    <br>
                    <a class="btn mt-2 w-100 btn-secondary" style="margin-inline: auto; display: block; width: 100px;" href="http://localhost/php-training/day-10/banking-system/admin/list-customer.php" class="btn">View List</a>

                </form>

            </div>
        </div>

    </div>

    <script src="../assets/script.js"></script>
</body>

</html>