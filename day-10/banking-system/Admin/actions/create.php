<?php
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


