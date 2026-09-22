<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking System - Login</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Login</h3>

            <!-- action="login.php" method="POST" -->
            <form action="login.php" method="POST">

                <!-- Role: decides whether username or account number logic runs on the PHP side -->
                <div class="mb-3">
                    <label for="role" class="form-label">Login as</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" selected disabled>Select role</option>
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>

                <!-- Admin uses username, Customer uses account number - same input, label/placeholder can be swapped via JS based on role if needed -->
                <div class="mb-3">
                    <label for="identifier" class="form-label">Username / Account Number</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Enter username or account number" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- CSRF token hidden field - generate with PHP: <?php echo $_SESSION['csrf_token']; ?> -->
                <input type="hidden" name="csrf_token" value="">

                <button type="submit" class="btn btn-primary w-100">Login</button>

            </form>

        </div>
    </div>
</div>

</body>
</html>