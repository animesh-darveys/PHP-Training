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

    <!-- Optional search box - filters table rows via PHP query param or JS -->
    <form action="list-customer.php" method="GET" class="mb-3">
        <div class="input-group" style="max-width: 350px;">
            <input type="text" class="form-control" name="search" placeholder="Search by name or account number">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

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
                    <!-- Loop starts here: <?php foreach ($customers as $i => $customer): ?> -->
                    <tr>
                        <td>1</td>
                        <!-- <?php echo htmlspecialchars($customer['account_number']); ?> -->
                        <td>1234 5678 9012</td>
                        <!-- <?php echo htmlspecialchars($customer['full_name']); ?> -->
                        <td>John Doe</td>
                        <!-- <?php echo htmlspecialchars($customer['email']); ?> -->
                        <td>john.doe@example.com</td>
                        <!-- <?php echo htmlspecialchars($customer['phone']); ?> -->
                        <td>9876543210</td>
                        <!-- <?php echo ucfirst($customer['account_type']); ?> -->
                        <td>Savings</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <!-- href="customer-account-details.php?id=<?php echo $customer['id']; ?>" -->
                            <a href="customer-account-details.php" class="btn btn-sm btn-outline-primary">View</a>
                            <!-- href="update-customer.php?id=<?php echo $customer['id']; ?>" -->
                            <a href="update-customer.php" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <!-- form posts to delete-customer.php with id + csrf token; confirm before submit -->
                            <form action="delete-customer.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                <input type="hidden" name="id" value="1">
                                <input type="hidden" name="csrf_token" value="">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>4321 8765 2109</td>
                        <td>Jane Smith</td>
                        <td>jane.smith@example.com</td>
                        <td>9123456780</td>
                        <td>Current</td>
                        <td><span class="badge bg-danger">Inactive</span></td>
                        <td>
                            <a href="customer-account-details.php" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="update-customer.php" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="delete-customer.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                <input type="hidden" name="id" value="2">
                                <input type="hidden" name="csrf_token" value="">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <!-- <?php endforeach; ?> -->

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