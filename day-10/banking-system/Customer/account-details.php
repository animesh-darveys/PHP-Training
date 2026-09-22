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

            <!-- Every value below is fetched from the DB for the logged-in customer only, e.g. WHERE customer_id = $_SESSION['customer_id'] -->

            <div class="detail-row">
                <span class="detail-label">Full Name</span>
                <!-- <?php echo htmlspecialchars($customer['full_name']); ?> -->
                <span class="detail-value">John Doe</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <span>
                    <!-- masked span shows first 4 digits + asterisks; full span is hidden until toggled -->
                    <span id="acc-masked" class="detail-value">1234 **** ****</span>
                    <span id="acc-full" class="detail-value d-none">
                        <!-- <?php echo htmlspecialchars($customer['account_number']); ?> -->
                        1234 5678 9012
                    </span>
                    <button type="button" id="toggle-acc" class="btn btn-sm btn-outline-secondary ms-2" onclick="toggleAccountNumber()">Show</button>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email</span>
                <!-- <?php echo htmlspecialchars($customer['email']); ?> -->
                <span class="detail-value">john.doe@example.com</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone Number</span>
                <!-- <?php echo htmlspecialchars($customer['phone']); ?> -->
                <span class="detail-value">9876543210</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Address</span>
                <!-- <?php echo htmlspecialchars($customer['address']); ?> -->
                <span class="detail-value">221B Baker Street, Delhi</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date of Birth</span>
                <!-- <?php echo htmlspecialchars($customer['dob']); ?> -->
                <span class="detail-value">15 Aug 1995</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Type</span>
                <!-- <?php echo htmlspecialchars(ucfirst($customer['account_type'])); ?> -->
                <span class="detail-value">Savings</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status</span>
                <!-- badge class toggled based on $customer['status'] -->
                <span class="badge badge-active">Active</span>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">Customer Since</span>
                <!-- <?php echo date('d M Y', strtotime($customer['created_at'])); ?> -->
                <span class="detail-value">01 Jan 2024</span>
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