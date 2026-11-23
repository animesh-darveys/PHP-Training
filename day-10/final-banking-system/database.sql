-- 1. Database
CREATE DATABASE IF NOT EXISTS banking_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE banking_db;

-- 2. users table (login ke liye: Admin + Customer, Email + Password se login)
CREATE TABLE IF NOT EXISTS users (
    UserID          INT AUTO_INCREMENT PRIMARY KEY,
    Role            VARCHAR(20)  NOT NULL,              -- 'Admin' / 'Customer'
    Email           VARCHAR(150) NOT NULL UNIQUE,       -- login ID
    Password        VARCHAR(255) NOT NULL,              -- password_hash() ke liye 255
    remember_token  VARCHAR(64)  NULL DEFAULT NULL,     -- sha256 hash
    CreatedDate     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_remember_token (remember_token)
) ENGINE=InnoDB;

-- 3. customer_info table (Email users table mein hai)
CREATE TABLE IF NOT EXISTS customer_info (
    CustomerID      INT AUTO_INCREMENT PRIMARY KEY,
    UserID          INT NOT NULL UNIQUE,
    AccountNumber   VARCHAR(30)  NOT NULL UNIQUE,
    FullName        VARCHAR(50)  NOT NULL,
    PhoneNumber     VARCHAR(10)  NOT NULL,
    Address         VARCHAR(200) NOT NULL,
    DateOfBirth     DATE NOT NULL,
    AccountType     ENUM('savings','current') NOT NULL,
    Status          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    CreatedDate     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customer_user
        FOREIGN KEY (UserID) REFERENCES users(UserID)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. account_balance table
CREATE TABLE IF NOT EXISTS account_balance (
    BalanceID        INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID       INT NOT NULL UNIQUE,
    BalanceAmount    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    LastUpdatedDate  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_balance_customer
        FOREIGN KEY (CustomerID) REFERENCES customer_info(CustomerID)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Default Admin user
-- Email: admin@bank.com   Password: admin123  (password_hash se hashed)
INSERT INTO users (Role, Email, Password)
VALUES ('Admin', 'admin@bank.com', '$2y$10$9ilGF0.cjyu1EaOwL/IEK.ytKM3BdYE.GTAWhdBgGHmCzWuyONwaK');
