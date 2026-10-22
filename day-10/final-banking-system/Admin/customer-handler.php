<?php

require_once '../config/db.php';
require_once './classes/Customer.php';

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

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die('CSRF token is missing.');
    }

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    // Get form data.
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $accountType = trim($_POST['accountType'] ?? '');

    $customer = new Customer(
        $conn,
        $name,
        $email,
        $phone,
        $address,
        $dob,
        $accountType
    );

    $errors = $customer->validate();

    $nameError = $errors['name'] ?? '';
    $emailError = $errors['email'] ?? '';
    $phoneError = $errors['phone'] ?? '';
    $addressError = $errors['address'] ?? '';
    $dobError = $errors['dob'] ?? '';
    $accountTypeError = $errors['accountType'] ?? '';

    if (empty($errors)) {

        try {

            if ($mode === 'create') {
                $customerId = $customer->create();
                $_SESSION['success'] = 'Customer created successfully.';
                header('Location: ./list-customer.php');
                exit;
            }

            elseif ($mode === 'edit') {

                // Step 1: Get customer ID from URL.
                $id = $_GET['id'] ?? '';

                if (empty($id) || !is_numeric($id) || $id <= 0) {
                    die('Invalid customer ID.');
                }

                $id = (int) $id;

                // Step 2: Get account balance from form.
                $balance = $_POST['balance'] ?? '';

                date_default_timezone_set('Asia/Kolkata');
                
                $date = new DateTime();

                if ($balance === '' || !is_numeric($balance) || $balance < 0) {
                    die('Please enter a valid account balance.');
                }

                $balance = (float) $balance;

                // Step 3: Get account status from form.
                $status = $_POST['status'] ?? '';

                if ($status !== 'active' && $status !== 'inactive') {
                    die('Invalid account status.');
                }

                // Step 4: Update customer.
                $customer->update($id, $balance, $status, $date);

                // Step 5: Show success message.
                $_SESSION['success'] = 'Customer updated successfully.';

                // Step 6: Redirect to customer list.
                header('Location: ./list-customer.php');
                exit;
            }
            else {
                throw new InvalidArgumentException('Invalid operation mode.');
            }
        } catch (InvalidArgumentException $e) {

            // Handle invalid input.
            $_SESSION['error'] = $e->getMessage();

            if ($mode === 'edit') {
                header('Location: ./update-customer.php?id=' . (int) ($_GET['id'] ?? 0));
            } else {
                header('Location: ./create-customer.php');
            }

            exit;
        } catch (Throwable $e) {

            // Handle unexpected errors.
            error_log($e->getMessage());

            $_SESSION['error'] = 'Unable to process customer request. Please try again.';

            if ($mode === 'edit') {
                header('Location: ./update-customer.php?id=' . (int) ($_GET['id'] ?? 0));
            } else {
                header('Location: ./create-customer.php');
            }

            exit;
        }
    }
}
