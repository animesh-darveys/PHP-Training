<?php
require_once 'validation.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP File</title>
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>
    <div class="register-form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="register-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter Your Name" value="<?= htmlspecialchars($name) ?>">
                <!-- <span id="nameError" class="error" role="alert"></span> -->
                <span id="nameError" class="error <?= !empty($nameError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($nameError) ?></span>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile No.</label>
                <input type="text" name="mobile" id="mobile" placeholder="Enter Mobile Number" value="<?= htmlspecialchars($mobile) ?>">
                <span id="mobileError" class="error <?= !empty($mobileError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($mobileError) ?></span>

            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" value="<?= htmlspecialchars($email) ?>">
                <span id="emailError" class="error <?= !empty($emailError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($emailError) ?></span>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" inputmode="numeric" placeholder="Enter Your Age" value="<?= htmlspecialchars($age) ?>">
                <span id="ageError" class="error <?= !empty($ageError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($ageError) ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" min="1995-01-01" max="2026-10-30" value="<?= htmlspecialchars($dob) ?>">
                <span id="dobError" class="error <?= !empty($dobError) ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($dobError) ?></span>
            </div>

            <div class="form-group">
                <label for="country" class="">Place of Birth</label><br>
                <select class="country" name="country" id="country">
                    <option value="">Select Country</option>
                    <option value="IN" <?= $country === 'IN' ? 'selected' : '' ?>>India</option>
                    <option value="POK" <?= $country === 'POK' ? 'selected' : '' ?>>Pakistan</option>
                    <option value="UK" <?= $country === 'UK' ? 'selected' : '' ?>>United Kingdom</option>
                    <option value="USA" <?= $country === 'USA' ? 'selected' : '' ?>>United States of America</option>
                </select>
                <span id="countryError" class="error <?= $countryError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($countryError) ?></span>
            </div>

            <div class="form-group">
                <label for="address" class="">Permanent Address</label>
                <div>
                    <textarea row="4" class="address" id="address" name="address"><?= htmlspecialchars($address) ?></textarea>
                    <span id="addressError" class="error <?= $addressError ? 'show' : ''; ?>" role="alert"><?= htmlspecialchars($addressError) ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="website" class="">Website URL</label>
                <div>
                    <input type="url" class="website" id="website" name="website" value="<?= htmlspecialchars($website) ?>">
                    <span id="websiteError" class="error <?= $websiteError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($websiteError) ?></span>
                </div>
            </div>


            <div class="form-group">
                <div>Gender:</div>
                <label for="gender-male" class="radio-input">
                    <input type="radio" name="gender" id="gender-male" value="male" <?php $gender === 'male' ? 'checked' : ''; ?>>Male
                </label>
                <label for="gender-female" class="radio-input">
                    <input type="radio" name="gender" id="gender-female" value="female" <?php $gender === 'female' ? 'checked' : ''; ?>>Female
                </label>
                <span id="genderError" class="error <?= $genderError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($genderError) ?></span>
            </div>

            <div class="form-group-checkbox">
                <p>Skills</p>
                <label>
                    <input type="checkbox" name="technology[]" value="php" <?= in_array('php', $skills, true) ? 'checked' : '' ?>>PHP
                </label>
                <label>
                    <input type="checkbox" name="technology[]" value="laravel" <?= in_array('laravel', $skills, true) ? 'checked' : '' ?>>Laravel
                </label>
                <label>
                    <input type="checkbox" name="technology[]" value="javascript" <?= in_array('javascript', $skills, true) ? 'checked' : '' ?>>JavaScript
                </label>
                <span id="skillsError" class="error <?= $skillsError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($skillsError) ?></span>
            </div>

            <div class="form-group">
                <label for="profile">Profile Picture:</label>
                <input type="file" id="profile" name="profile">
                <span id="profileError" class="error <?= $profileError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($profileError) ?></span>
            </div>

            <div class="form-group">
                <label for="documents">Documents:</label>
                <input type="file" id="documents" name="documents[]" multiple>
                <span id="documentsError" class="error" role="alert"></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter Password">
                <span id="passwordError" class="error <?= $passwordError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($passwordError) ?></span>

            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password">
                <span id="confirmError" class="error <?= $confirmPasswordError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($confirmPasswordError) ?></span>
            </div>

            <div class="form-group">

                <label for="terms">
                    <input type="checkbox" id="terms" name="terms" value="1"> I agree to the Terms & Conditions
                </label>
                <span id="termsError" class="error <?= $termsError ? 'show' : '' ?>" role="alert"><?= htmlspecialchars($termsError) ?></span>
            </div>

            <button type="submit" class="button">Submit</button>
        </form>
    </div>
    <!-- <script src="assets/script.js"></script> -->
</body>

</html>