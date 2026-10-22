<?php

class Customer
{
    private PDO $conn;
    private string $name;
    private string $email;
    private string $phone;
    private string $address;
    private string $dob;
    private string $accountType;
    private string $password;
    private string $role;

    public function __construct(
        PDO $conn,
        string $name = '',
        string $email = '',
        string $phone = '',
        string $address = '',
        string $dob = '',
        string $accountType = '',
        string $password = '',
        string $role = 'Customer'
    ) {
        $this->conn = $conn;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->dob = $dob;
        $this->accountType = $accountType;
        $this->password = $password;
        $this->role = $role;
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->name === '') {
            $errors['name'] = 'This field is required.';
        } elseif (!preg_match('/^[A-Za-z ]{3,50}$/', $this->name)) {
            $errors['name'] = 'Enter 3 to 50 letters.';
        }

        if ($this->email === '') {
            $errors['email'] = 'This field is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($this->phone === '') {
            $errors['phone'] = 'This field is required.';
        } elseif (!preg_match('/^[0-9]{10}$/', $this->phone)) {
            $errors['phone'] = 'Phone number must be exactly 10 digits.';
        }

        if ($this->address === '') {
            $errors['address'] = 'This field is required.';
        } elseif (strlen($this->address) < 5 || strlen($this->address) > 200) {
            $errors['address'] = 'Address must be 5 to 200 characters.';
        }

        $date = DateTime::createFromFormat('!Y-m-d', $this->dob);

        if (
            $this->dob === '' ||
            !$date ||
            $date->format('Y-m-d') !== $this->dob
        ) {
            $errors['dob'] = 'Enter a valid date.';
        } elseif ($date > new DateTime('today')) {
            $errors['dob'] = 'Date of birth cannot be in the future.';
        }

        if (!in_array($this->accountType, ['savings', 'current'], true)) {
            $errors['accountType'] = 'Select a valid account type.';
        }

        return $errors;
    }

    private function generatePassword(): string
    {
        $namePart = strtoupper(
            substr(preg_replace('/\s+/', '', $this->name), 0, 4)
        );

        $year = date('Y', strtotime($this->dob));

        return $namePart . $year;
    }

    public function create(): int
    {
        $errors = $this->validate();

        if (!empty($errors)) {
            throw new InvalidArgumentException('Invalid customer data.');
        }

        $this->conn->beginTransaction();

        try {
            $password = $this->password !== ''
                ? $this->password
                : $this->generatePassword();

            $sql = "INSERT INTO users (Role, Username, Password)
                    VALUES (:role, :username, :password)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'role' => $this->role,
                'username' => $this->name,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            $userId = (int) $this->conn->lastInsertId();

            $accountNumber = 'SBI1001' . $userId;

            $sql = "INSERT INTO customer_info
                    (
                        UserID, AccountNumber, FullName, Email,
                        PhoneNumber, Address, DateOfBirth, AccountType
                    )
                    VALUES
                    (
                        :userId, :accountNumber, :name, :email,
                        :phone, :address, :dob, :accountType
                    )";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'userId' => $userId,
                'accountNumber' => $accountNumber,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'dob' => $this->dob,
                'accountType' => $this->accountType
            ]);

            $customerId = (int) $this->conn->lastInsertId();

            $sql = "INSERT INTO account_balance
                    (CustomerID, BalanceAmount)
                    VALUES (:customerId, :balance)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'customerId' => $customerId,
                'balance' => 200000
            ]);

            $this->conn->commit();

            return $customerId;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    public function find(int $id): array|false
    {
        $sql = "SELECT
                    ci.CustomerID,
                    ci.UserID,
                    ci.AccountNumber,
                    ci.FullName,
                    ci.Email,
                    ci.PhoneNumber,
                    ci.Address,
                    ci.DateOfBirth,
                    ci.AccountType,
                    ci.Status,
                    ab.BalanceAmount
                FROM customer_info ci
                LEFT JOIN account_balance ab
                    ON ci.CustomerID = ab.CustomerID
                WHERE ci.CustomerID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $sql = "SELECT
                    ci.CustomerID,
                    ci.AccountNumber,
                    ci.FullName,
                    ci.Email,
                    ci.PhoneNumber,
                    ci.Address,
                    ci.DateOfBirth,
                    ci.AccountType,
                    ci.Status,
                    ab.BalanceAmount
                FROM customer_info ci
                LEFT JOIN account_balance ab
                    ON ci.CustomerID = ab.CustomerID
                ORDER BY ci.CustomerID DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(
        int $id,
        float $balance,
        string $status,
        Datetime $date
    ): bool {
        $errors = $this->validate();

        if (!empty($errors)) {
            throw new InvalidArgumentException('Invalid customer data.');
        }

        if (!is_finite($balance) || $balance < 0) {
            throw new InvalidArgumentException('Invalid account balance.');
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            throw new InvalidArgumentException('Invalid account status.');
        }

        $this->conn->beginTransaction();

        try {
            $sql = "UPDATE customer_info
                    SET FullName = :name,
                        Email = :email,
                        PhoneNumber = :phone,
                        Address = :address,
                        DateOfBirth = :dob,
                        AccountType = :accountType,
                        Status = :status
                    WHERE CustomerID = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'dob' => $this->dob,
                'accountType' => $this->accountType,
                'status' => $status,
                'id' => $id
            ]);

            if ($stmt->rowCount() === 0 && !$this->find($id)) {
                throw new RuntimeException('Customer not found.');
            }

            $sql = "UPDATE account_balance
                    SET BalanceAmount = :balance,
                        LastUpdatedDate = :date
                    WHERE CustomerID = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'balance' => $balance,
                'date' => $date->format('Y-m-d H:i:s'),
                'id'      => $id
            ]);

            $this->conn->commit();

            return true;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $this->conn->beginTransaction();

        try {
            $sql = "SELECT UserID
                    FROM customer_info
                    WHERE CustomerID = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$customer) {
                throw new RuntimeException('Customer not found.');
            }

            $userId = $customer['UserID'];

            $sql = "DELETE FROM account_balance WHERE CustomerID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            $sql = "DELETE FROM customer_info WHERE CustomerID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            $sql = "DELETE FROM users WHERE UserID = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['userId' => $userId]);

            $this->conn->commit();

            return true;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Get customer details.
    public function getCustomerDetails(int $id): array|false
    {
        $sql = "SELECT
                    ci.CustomerID,
                    ci.AccountNumber,
                    ci.FullName,
                    ci.Email,
                    ci.PhoneNumber,
                    ci.Address,
                    ci.DateOfBirth,
                    ci.AccountType,
                    ci.Status,
                    ab.BalanceAmount,
                    ci.CreatedDate
                FROM customer_info ci
                LEFT JOIN account_balance ab
                    ON ci.CustomerID = ab.CustomerID
                WHERE ci.CustomerID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get customer account details.
    public function getCustomerAccountDetails(int $id): array|false
    {
        $sql = "SELECT
                    ci.CustomerID,
                    ci.AccountNumber,
                    ci.FullName,
                    ci.AccountType,
                    ab.BalanceAmount,
                    ab.LastUpdatedDate
                FROM customer_info ci
                LEFT JOIN account_balance ab
                    ON ci.CustomerID = ab.CustomerID
                WHERE ci.CustomerID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}