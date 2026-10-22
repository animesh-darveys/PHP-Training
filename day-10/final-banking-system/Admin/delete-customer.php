<?php

session_start();

require_once '../config/db.php';
require_once './classes/Customer.php';

if (empty($_SESSION['userid']) || empty($_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list-customer.php');
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid CSRF token.');
}

// Validate customer ID.
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    $_SESSION['error'] = 'Invalid customer ID.';
    header('Location: list-customer.php');
    exit;
}
try {
    $customer = new Customer($conn);
    $customer->delete($id);

    $_SESSION['success'] = 'Customer deleted successfully.';

} catch (Throwable $e) {
    error_log($e->getMessage());

    $_SESSION['error'] = $e instanceof RuntimeException &&
                         $e->getMessage() === 'Customer not found.'
        ? 'Customer not found.'
        : 'Unable to delete customer. Please try again.';
}

header('Location: list-customer.php');
exit;