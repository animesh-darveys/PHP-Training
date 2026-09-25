<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (empty($_SESSION['userid']) || empty($_SESSION['role'])) { header('Location: ../login.php'); exit; }
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/db.php';

$id = $_GET['id'];
$sql = "SELECT ci.CustomerID, ci.AccountNumber, ci.FullName, ci.Email, ci.PhoneNumber, 
               ci.Address, ci.DateOfBirth, ci.AccountType, ci.Status, ab.BalanceAmount,ci.CreatedDate
        FROM customer_info ci
        LEFT JOIN account_balance ab ON ci.CustomerID = ab.CustomerID
        WHERE ci.CustomerID = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - My Account Details</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<style>
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-label { color: #6c757d; font-weight: 500; }
    .detail-value { font-weight: 500; }
    .badge-active { background-color: #198754; }
    .badge-inactive { background-color: #dc3545; }
</style>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 500px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">My Account Details</h3>

            <div class="detail-row">
                <span class="detail-label">Full Name</span>
                <span class="detail-value"><?= $customer['FullName'] ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <span>
                    <!-- masked span shows first 4 digits + asterisks; full span is hidden until toggled -->
                    <span id="acc-masked" class="detail-value">SBI***** </span>
                    <span id="acc-full" class="detail-value d-none">
                        <?php echo htmlspecialchars($customer['AccountNumber']); ?>
                    </span>
                    <button type="button" id="toggle-acc" class="btn btn-sm btn-outline-secondary ms-2" onclick="toggleAccountNumber()">Show</button>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?= $customer['Email'] ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone Number</span>
                <span class="detail-value"><?= $customer['PhoneNumber'] ?></span>

            </div>

            <div class="detail-row">
                <span class="detail-label">Address</span>
                <span class="detail-value"><?= $customer['Address'] ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date of Birth</span>
                <span class="detail-value"><?php echo htmlspecialchars($customer['DateOfBirth']); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Type</span>
                <span class="detail-value"><?php echo htmlspecialchars(ucfirst($customer['AccountType'])); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="badge badge-active"><?= ucFirst($customer['Status']) ?></span>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">Customer Since</span>
                <span class="detail-value"><?= $customer['CreatedDate'] ?></span>
            </div>
            
            <div class="detail-row d-flex justify-content-start" style="border-bottom: none;">
                <a class="btn btn-info m-auto" href="./balance.php?id=<?= $id ?>">View Balance</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAccountNumber() {
    const masked = document.getElementById('acc-masked');
    const full = document.getElementById('acc-full');
    const btn = document.getElementById('toggle-acc');

    const isHidden = full.classList.contains('d-none');
    masked.classList.toggle('d-none', isHidden);
    full.classList.toggle('d-none', !isHidden);
    btn.textContent = isHidden ? 'Hide' : 'Show';
}
</script>

</body>
</html>