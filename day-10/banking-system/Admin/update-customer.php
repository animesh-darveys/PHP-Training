<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/db.php';
if (empty($_SESSION['userid']) || empty($_SESSION['role'])) { header('Location: ../login.php'); exit; }
$id = $_GET['id'];
$sql = "SELECT ci.CustomerID, ci.AccountNumber, ci.FullName, ci.Email, ci.PhoneNumber, 
               ci.Address, ci.DateOfBirth, ci.AccountType, ci.Status, ab.BalanceAmount
        FROM customer_info ci
        LEFT JOIN account_balance ab ON ci.CustomerID = ab.CustomerID
        WHERE ci.CustomerID = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $accountType = $_POST['accounttype'];
    $balance = $_POST['balance'];
    $status = $_POST['status'];
    $updateError='';

    if ($name === '' || $email === '' || $phone === '' || $address === '' || $dob === '' || $accountType === '' || $status === '') {
        $updateError = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateError = "Enter a valid email address.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $updateError = "Phone number must be exactly 10 digits.";
    }
    if ($updateError === '') {
        try {
            $conn->beginTransaction();

            // Update customer_info table
            $sqlUpdate = "UPDATE customer_info 
                          SET FullName = :fullName,
                              Email = :email,
                              PhoneNumber = :phone,
                              Address = :address,
                              DateOfBirth = :dob,
                              AccountType = :accountType,
                              Status = :status
                          WHERE CustomerID = :id";

            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'fullName'    => $name,
                'email'       => $email,
                'phone'       => $phone,
                'address'     => $address,
                'dob'         => $dob,
                'accountType' => $accountType,
                'status'      => $status,
                'id'          => $id
            ]);

            // Update account_balance table
            $sqlBalance = "UPDATE account_balance 
                           SET BalanceAmount = :balance 
                           WHERE CustomerID = :id";

            $stmtBalance = $conn->prepare($sqlBalance);
            $stmtBalance->execute([
                'balance' => $balance,
                'id'      => $id
            ]);

            $conn->commit();

            $_SESSION['success'] = "Customer updated successfully.";
            header("Location: list-customer.php");
            exit;

        } catch (PDOException $e) {
            $conn->rollBack();
            $updateError = "Database Error: " . $e->getMessage();
        }
    }

    $customer['FullName']     = $name;
    $customer['Email']        = $email;
    $customer['PhoneNumber']  = $phone;
    $customer['Address']      = $address;
    $customer['DateOfBirth']  = $dob;
    $customer['AccountType']  = $accountType;
    $customer['BalanceAmount']= $balance;
    $customer['Status']       = $status;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - Update Customer</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 500px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Update Customer</h3>

            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Account Number</label>
                    <input type="text" class="form-control" value="<?= $customer['AccountNumber'] ?>" readonly disabled>
                </div>

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?= $customer['FullName'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $customer['Email'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <!-- value="<?php echo htmlspecialchars($customer['phone']); ?>" -->
                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" value="<?= $customer['PhoneNumber'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <!-- <?php echo htmlspecialchars($customer['address']); ?> -->
                    <textarea class="form-control" id="address" name="address" rows="2" required><?= $customer['Address'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <!-- value="<?php echo htmlspecialchars($customer['dob']); ?>" -->
                    <input type="date" class="form-control" id="dob" name="dob" value="<?= $customer['DateOfBirth'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="account_type" class="form-label">Account Type</label>
                    <select class="form-select" id="account_type" name="accounttype" required>
                        <option value="savings" <?= $customer['AccountType']==='savings'?'selected':'' ?>>Savings</option>
                        <option value="current" <?= $customer['AccountType']==='current'?'selected':'' ?>>Current</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_type" class="form-label">Account Balance</label>
                    <input type="text" class="form-control" id="balance" name="balance" value="<?= $customer['BalanceAmount'] ?>" required>

                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active" <?= $customer['Status'] === 'active' ? 'selected' : '' ?>selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- CSRF token hidden field -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Update Customer</button>
                    <a href="list-customer.php" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>