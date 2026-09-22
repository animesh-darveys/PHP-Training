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
                <!-- <?php echo number_format($customer['balance'], 2); ?> -->
                <div class="balance-amount">&#8377; 45,320.00</div>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Holder</span>
                <!-- <?php echo htmlspecialchars($customer['full_name']); ?> -->
                <span class="detail-value">John Doe</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <span>
                    <span id="acc-masked" class="detail-value">1234 **** ****</span>
                    <span id="acc-full" class="detail-value d-none">
                        <!-- <?php echo htmlspecialchars($customer['account_number']); ?> -->
                        1234 5678 9012
                    </span>
                    <button type="button" id="toggle-acc" class="btn btn-sm btn-outline-secondary ms-2" onclick="toggleAccountNumber()">Show</button>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Type</span>
                <!-- <?php echo htmlspecialchars(ucfirst($customer['account_type'])); ?> -->
                <span class="detail-value">Savings</span>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">Last Updated</span>
                <!-- <?php echo date('d M Y, h:i A', strtotime($customer['updated_at'])); ?> -->
                <span class="detail-value">21 Sep 2026, 06:40 PM</span>
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