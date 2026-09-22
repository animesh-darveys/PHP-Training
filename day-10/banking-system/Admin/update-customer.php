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

            <!-- Existing customer fetched via: WHERE id = $_GET['id'] before rendering this form -->
            <!-- action="update-customer.php" method="POST" -->
            <form action="update-customer.php" method="POST">

                <!-- Hidden customer id - identifies which record to update -->
                <!-- value="<?php echo $customer['id']; ?>" -->
                <input type="hidden" name="id" value="1">

                <!-- Account Number: read-only, never editable once assigned -->
                <div class="mb-3">
                    <label class="form-label">Account Number</label>
                    <!-- value="<?php echo htmlspecialchars($customer['account_number']); ?>" -->
                    <input type="text" class="form-control" value="1234 5678 9012" readonly disabled>
                </div>

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <!-- value="<?php echo htmlspecialchars($customer['full_name']); ?>" -->
                    <input type="text" class="form-control" id="full_name" name="full_name" value="John Doe" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <!-- value="<?php echo htmlspecialchars($customer['email']); ?>" -->
                    <input type="email" class="form-control" id="email" name="email" value="john.doe@example.com" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <!-- value="<?php echo htmlspecialchars($customer['phone']); ?>" -->
                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" value="9876543210" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <!-- <?php echo htmlspecialchars($customer['address']); ?> -->
                    <textarea class="form-control" id="address" name="address" rows="2" required>221B Baker Street, Delhi</textarea>
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <!-- value="<?php echo htmlspecialchars($customer['dob']); ?>" -->
                    <input type="date" class="form-control" id="dob" name="dob" value="1995-08-15" required>
                </div>

                <div class="mb-3">
                    <label for="account_type" class="form-label">Account Type</label>
                    <select class="form-select" id="account_type" name="account_type" required>
                        <!-- selected="<?php echo $customer['account_type'] === 'savings' ? 'selected' : ''; ?>" -->
                        <option value="savings" selected>Savings</option>
                        <option value="current">Current</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <!-- selected="<?php echo $customer['status'] === 'active' ? 'selected' : ''; ?>" -->
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- CSRF token hidden field -->
                <input type="hidden" name="csrf_token" value="">

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