<?php

require_once './customer-handler.php';
require_once './classes/Customer.php';

if ($mode === 'edit') {
    if (!isset($_GET['id'])) {
        die('Customer ID is missing.');
    }
    $editID = (int) $_GET['id'];
    if ($editID <= 0) {
        die('Invalid customer ID.');
    }
    $customerModel = new Customer($conn);
    $customer = $customerModel->find($editID);
    if (!$customer) {
        die('Customer not found.');
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

                <h3 class="text-center mb-4"><?= ($mode === 'edit'?'Update Customer':'Create Customer') ?></h3>

                <form action="" method="POST" id="customer-form">

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="fullname" value="<?= $mode === 'edit' ? htmlspecialchars($customer['FullName'] ?? '') : '' ?>">
                        <span id="nameError" class="error <?= $nameError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($nameError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $mode === 'edit' ? htmlspecialchars($customer['Email'] ?? '') : '' ?>">
                        <span id="emailError" class="error <?= $emailError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($emailError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="10-digit number" value="<?= $mode === 'edit' ? htmlspecialchars($customer['PhoneNumber'] ?? '') : '' ?>">
                        <span id="phoneError" class="error <?= $phoneError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($phoneError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" style="resize: none;"><?= $mode === 'edit' ? htmlspecialchars($customer['Address'] ?? '') : '' ?></textarea></textarea>
                        <span id="addressError" class="error <?= $addressError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($addressError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= $mode === 'edit' ? htmlspecialchars($customer['DateOfBirth'] ?? '') : '' ?>">
                        <span id="dobError" class="error <?= $dobError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($dobError) ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="accountType" class="form-label">Account Type</label>

                        <select class="form-select" id="accountType" name="accountType">

                            <option value="" disabled
                                <?= $mode === 'create' && empty($accountType) ? 'selected' : '' ?>>
                                Select account type
                            </option>

                            <option value="savings"
                                <?= $mode === 'edit'
                                    ? (($customer['AccountType'] ?? '') === 'savings' ? 'selected' : '')
                                    : ($accountType === 'savings' ? 'selected' : '') ?>>
                                Savings
                            </option>

                            <option value="current"
                                <?= $mode === 'edit'
                                    ? (($customer['AccountType'] ?? '') === 'current' ? 'selected' : '')
                                    : ($accountType === 'current' ? 'selected' : '') ?>>
                                Current
                            </option>

                        </select>

                        <span id="accountTypeError"
                            class="error <?= !empty($accountTypeError) ? 'show' : '' ?>"
                            role="alert">
                            <?= htmlspecialchars($accountTypeError) ?>
                        </span>
                    </div>
                    <?php if($mode === 'edit'){ ?>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Balance</label>
                        <input type="text" class="form-control" id="balance" name="balance" value="<?= $mode === 'edit' ? htmlspecialchars($customer['BalanceAmount'] ?? '') : '' ?>">

                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">

                            <option value="active"
                                <?= $mode === 'edit'
                                    ? (($customer['Status'] ?? '') === 'active' ? 'selected' : '')
                                    : (($status ?? 'active') === 'active' ? 'selected' : '') ?>>
                                Active
                            </option>

                            <option value="inactive"
                                <?= $mode === 'edit'
                                    ? (($customer['Status'] ?? '') === 'inactive' ? 'selected' : '')
                                    : (($status ?? '') === 'inactive' ? 'selected' : '') ?>>
                                Inactive
                            </option>

                        </select>
                    </div>
                    <?php }?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <button type="submit" class="btn btn-primary w-100"><?= ($mode === 'edit' ? 'Update Customer':'Create Customer') ?></button>
                    <br>
                    <a class="btn mt-2 w-100 btn-secondary" style="margin-inline: auto; display: block; width: 100px;" href="http://localhost/php-training/day-10/final-banking-system/admin/list-customer.php" class="btn">View List</a>

                </form>

            </div>
        </div>

    </div>

    <script src="../assets/script.js"></script>
</body>

</html>