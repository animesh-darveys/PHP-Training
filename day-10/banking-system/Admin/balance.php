<?php
session_start();

require_once '../config/db.php';

if (empty($_SESSION['userid']) || empty($_SESSION['role'])) { header('Location: ../login.php'); exit; }
$customerID = $_GET['id'];
$sql = "SELECT ci.CustomerID, ci.AccountNumber, ci.FullName, ci.AccountType, ab.BalanceAmount,ab.LastUpdatedDate
FROM customer_info ci
LEFT JOIN account_balance ab ON ci.CustomerID = ab.CustomerID
WHERE ci.CustomerID = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $customerID]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - View Balance</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<style>
    .balance-amount {
        font-size: 2.5rem;
        font-weight: 700;
        color: #198754;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-label { color: #6c757d; font-weight: 500; }
    .detail-value { font-weight: 500; }
</style>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 450px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">My Balance</h3>

            <!-- Balance fetched from DB for the logged-in customer only, e.g. WHERE customer_id = $_SESSION['customer_id'] -->

            <div class="text-center mb-4">
                <div class="detail-label">Available Balance</div>
                <div class="balance-amount"><?= $customer['BalanceAmount'] ?></div>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Holder</span>
                <span class="detail-value"><?= $customer['FullName'] ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <span>
                    <span id="acc-masked" class="detail-value">SBI******</span>
                    <span id="acc-full" class="detail-value d-none">
                        <?= $customer['AccountNumber'] ?>
                    </span>
                    <button type="button" id="toggle-acc" class="btn btn-sm btn-outline-secondary ms-2" onclick="toggleAccountNumber()">Show</button>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Type</span>
                <span class="detail-value"><?= ucfirst($customer['AccountType']) ?></span>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value"><?= date('d M Y, h:i A', strtotime($customer['LastUpdatedDate'])); ?></span>
            </div>

             <div class="detail-row" style="border-bottom: none;">
                <a class="btn btn-info m-auto" href="./account-details.php?id=<?= $customer['CustomerID'] ?>">Go to details page</a>
            </div>

        </div>
    </div>
</div>

<script>
function toggleAccountNumber() {
    const masked = document.getElementById('acc-masked');
    const full = document.getElementById('acc-full');
    const button = document.getElementById('toggle-acc');

    if (full.classList.contains('d-none')) {
        full.classList.remove('d-none');
        masked.classList.add('d-none');
        button.textContent = 'Hide';
    } else {
        full.classList.add('d-none');
        masked.classList.remove('d-none');
        button.textContent = 'Show';
    }
}
</script>

</body>
</html>