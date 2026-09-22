<?php
session_start();
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

            <form action="actions/create.php" method="POST" id="customer-form">

                <!-- Customer ID: not a form field - auto-assigned as FK after Users record is created -->
                <!-- Account Number: not a form field - auto-generate on the PHP side (e.g. random/sequential unique number) -->

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="fullname">
                    <span id="nameError" class="error" role="alert"></span>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span id="emailError" class="error" role="alert"></span>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" placeholder="10-digit number">
                    <span id="phoneError" class="error" role="alert"></span>

                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" style="resize: none;"></textarea>
                    <span id="addressError" class="error" role="alert"></span>

                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob">
                    <span id="dobError" class="error" role="alert"></span>

                </div>

                <div class="mb-3">
                    <label for="accountType" class="form-label">Account Type</label>
                    <select class="form-select" id="accountType" name="accountType">
                        <option value="" selected disabled>Select account type</option>
                        <option value="savings">Savings</option>
                        <option value="current">Current</option>
                    </select>
                    <span id="accountTypeError" class="error" role="alert"></span>

                </div>

                <!-- Status: not a form field - default to 'active' in DB on insert -->
                <!-- Created Date: not a form field - use NOW() / CURRENT_TIMESTAMP in the insert query -->

                <!-- CSRF token hidden field -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <button type="submit" class="btn btn-primary w-100">Create Customer</button>

            </form>

        </div>
    </div>
</div>
<script src="../assets/script.js"></script>
</body>
</html>