-- Existing banking_db ko Username login se Email login par shift karne ke liye.
-- Sirf EK BAAR chalana hai (naye setup ke liye database.sql kaafi hai).

USE banking_db;

-- 1. users table mein Email column add karo
ALTER TABLE users ADD COLUMN Email VARCHAR(150) NULL AFTER Role;

-- 2. Customers ka email customer_info se users mein copy karo
UPDATE users u
JOIN customer_info ci ON ci.UserID = u.UserID
SET u.Email = ci.Email;

-- 3. Admin ko email do aur password hash karo (Password: admin123)
UPDATE users
SET Email = 'admin@bank.com',
    Password = '$2y$10$9ilGF0.cjyu1EaOwL/IEK.ytKM3BdYE.GTAWhdBgGHmCzWuyONwaK'
WHERE Role = 'Admin';

-- 4. Email ko required + unique banao, Username column hatao
ALTER TABLE users
    MODIFY Email VARCHAR(150) NOT NULL,
    ADD UNIQUE KEY uq_users_email (Email),
    DROP COLUMN Username;

-- 5. customer_info se duplicate Email column hatao
ALTER TABLE customer_info DROP COLUMN Email;
