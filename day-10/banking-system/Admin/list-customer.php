<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (empty($_SESSION['userid']) || empty($_SESSION['role'])) { header('Location: ../login.php'); exit; }
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/db.php';

$customers = [];

try {
    $sql = "SELECT ci.CustomerID, ci.AccountNumber, ci.FullName, ci.Email, ci.PhoneNumber, 
                   ci.Address, ci.DateOfBirth, ci.AccountType, ci.Status, ab.BalanceAmount
            FROM customer_info ci
            LEFT JOIN account_balance ab ON ci.CustomerID = ab.CustomerID
            ORDER BY ci.CustomerID DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - Customer List</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Customer List</h3>
        <a href="create-customers.php" class="btn btn-primary">+ Add Customer</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Account Number</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Account Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $i => $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['CustomerID']); ?></td>
                        <td><?php echo htmlspecialchars($customer['AccountNumber']); ?></td>
                        <td><?php echo htmlspecialchars($customer['FullName']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['PhoneNumber']); ?></td>
                        <td> <?php echo ucfirst($customer['AccountType']); ?></td>
                        <td> <?php echo ucfirst($customer['Status']); ?></td>
                        <td>
                            <a href="account-details.php?id=<?= $customer['CustomerID'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="update-customer.php?id=<?= $customer['CustomerID'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="delete-customer.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                <input type="hidden" name="id" value="<?= $customer['CustomerID'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination placeholder - add if customer count grows large -->
    <!--
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
            <li class="page-item"><a class="page-link" href="?page=2">2</a></li>
        </ul>
    </nav>
    -->

</div>

</body>
</html>