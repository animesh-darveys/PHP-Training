<?php
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/db.php';
require_once 'actions/create.php';

if (empty($_SESSION['userid']) || empty($_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
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