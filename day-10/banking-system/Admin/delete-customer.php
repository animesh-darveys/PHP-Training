<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/db.php';
if (empty($_SESSION['userid']) || empty($_SESSION['role'])) { header('Location: ../login.php'); exit; }
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF token.');
}

$id = $_POST['id'] ?? null;

try {
    $conn->beginTransaction();

    $sql = "SELECT UserID FROM customer_info WHERE CustomerID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        $conn->rollBack();
        $_SESSION['error'] = "Customer not found.";
        header("Location: list-customer.php");
        exit;
    }

    $userId = $customer['UserID'];

    $sqlBalance = "DELETE FROM account_balance WHERE CustomerID = :id";
    $stmtBalance = $conn->prepare($sqlBalance);
    $stmtBalance->execute(['id' => $id]);

    $sqlCustomer = "DELETE FROM customer_info WHERE CustomerID = :id";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->execute(['id' => $id]);

    $sqlUser = "DELETE FROM users WHERE UserID = :userId";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->execute(['userId' => $userId]);

    $conn->commit();

    $_SESSION['success'] = "Customer deleted successfully.";
    header("Location: list-customer.php");
    exit;

} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Database Error: " . $e->getMessage();
    header("Location: list-customer.php");
    exit;
}