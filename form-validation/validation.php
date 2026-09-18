<?php

$name = '';
$mobile = '';
$email = '';
$age = '';

$nameError = '';
$mobileError = '';
$emailError = '';
$ageError = '';
$dob = '';
$dobError = '';


$allowedCountries = ['IN', 'POK', 'UK', 'USA'];
$country = '';
$countryError = '';

$address = '';
$addressError = '';

$website = '';
$websiteError = '';

$allowedGenders = ['male', 'female'];
$gender = '';
$genderError = '';

$allowedSkills = ['javascript', 'laravel', 'php'];
$skills = [];
$skillsError = '';

$password = '';
$passwordError = '';

$confirmPassword = '';
$confirmPasswordError = '';

$terms = '';
$termError = '';

$profile = [];
$profileError = '';

$documents = [];
$documentsError = '';

// echo "<pre>";
// print_r($_FILES);
// echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $skills = $_POST['technology'] ?? [];
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = $_POST['terms'] ?? '';
    $profile = $_FILES['profile'] ?? [];
    $documents = $_FILES['documents'] ?? [];

    if (!preg_match('/^[A-Za-z ]{3,50}$/', $name)) {
        $nameError = 'Name must be 3-50 characters and contain only letters and spaces';
    }

    if (!preg_match('/^\d{10}$/', $mobile)) {
        $mobileError = 'Mobile must be 10 digits only';
    }

    if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
        $emailError = 'Please enter a valid email address';
    }

    if (!preg_match('/^(18|19|2[0-9]|3[0-9]|40)$/', $age)) {
        $ageError = 'Age must be between 18 and 40';
    }

    if ($dob === '') {
        $dobError = 'Date of birth is required';
    } else {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        if ($birthDate > $today) {
            $dobError = 'Date of birth cannot be in the future';
        } else {
            $minimumAllowedBirthDate = new DateTime();
            $minimumAllowedBirthDate->modify('-30 years');
            if ($birthDate < $minimumAllowedBirthDate) {
                $dobError = 'Age cannot be more than 30 years';
            }
        }
    }

    if ($country === '') {
        $countryError = "Please Select country before submit.";
    } elseif (!in_array($country, $allowedCountries, true)) {
        $countryError = "Please Select valid country.";
    }

    if (!preg_match('/^.{10,200}$/', $address)) {
        $addressError = 'Address must be between 10 to 200 characters.';
    }

    if ($website === '') {
        $websiteError = "This field required";
    } elseif (!filter_var($website, FILTER_VALIDATE_URL)) {
        $websiteError = "Please enter valid url.";
    }

    if ($gender === '') {
        $genderError = "Please select a gender.";
    } elseif (!in_array($gender, $allowedGenders, true)) {
        $genderError = "Invalid selection detected.";
    }

    if (!is_array($skills)) {
        $skillsError = 'Invalid skills data.';
    } elseif (empty($skills)) {
        $skillsError = 'Please select at least one skill.';
    } else {
        foreach ($skills as $skill) {
            if (!in_array($skill, $allowedSkills, true)) {
                $skillsError = 'Invalid skill selected. Please select a valid skill.';
                break;
            }
        }
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@#$]).{8,}$/', $password)) {
        $passwordError = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.';
    }

    if ($confirmPassword === '') {
        $confirmPasswordError = "Please Enter Confirm Password";
    } elseif ($confirmPassword !== $password) {
        $confirmPasswordError = "Passwords do not match.";
    }

    if ($terms != 1) {
        $termsError = "Please select it before form submission.";
    }
    // var_dump(empty($profile));
    // var_dump(!isset($profile['error']));
    // var_dump($profile['error'] === UPLOAD_ERR_NO_FILE);
    
    if (empty($profile) || !isset($profile['error']) || $profile['error'] === UPLOAD_ERR_NO_FILE) {
        $profileError = 'Please select a profile image.';
    } elseif ($profile['error'] !== UPLOAD_ERR_OK) {
        $profileError = 'There was an error uploading the profile image.';
    } elseif ($profile['size'] > 1 * 1024 * 1024) {
        $profileError = 'Maximum file size should be 1MB.';
    } elseif ($profile['size'] === 0) {
        $profileError = 'The uploaded file is empty.';
    } else {
        $allowedMimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($profile['tmp_name']);

        if (!isset($allowedMimeToExt[$mimeType])) {
            $profileError = 'Invalid file format. Only JPG, PNG, and WEBP are allowed.';
        } elseif (@getimagesize($profile['tmp_name']) === false) {
            $profileError = 'The uploaded file is not a valid image.';
        } else {
            $extension = $allowedMimeToExt[$mimeType];
        }
    }

    if (empty($profileError)) {
        $uploadDirectory = 'uploads/profile/';

        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
            $profileError = 'Upload directory is not available.';
        } else {
            $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
            $destination = $uploadDirectory . $newFileName;

            if (!move_uploaded_file($profile['tmp_name'], $destination)) {
                $profileError = 'Failed to upload the profile image.';
            }
        }
    }

    if (empty($documents) || !isset($documents['name']) || empty($documents['name'][0])) {
        $documentsError = 'Please select at least one file.';
    } else {
        $maxSize = 1 * 1024 * 1024;

        foreach ($documents['name'] as $key => $fileName) {
            if ($documents['error'][$key] !== UPLOAD_ERR_OK) {
                $documentsError = "Error uploading file: $fileName";
                break;
            }

            if ($documents['size'][$key] > $maxSize) {
                $documentsError = "File must not exceed 1 MB: $fileName";
                break;
            }

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($documents['tmp_name'][$key]);

            if ($mimeType !== 'application/pdf' || $extension !== 'pdf') {
                $documentsError = "Please select PDF files only: $fileName";
                break;
            }
        }
    }

    if (empty($documentsError)) {
        $uploadDirectory = 'uploads/documents/';

        foreach ($documents['name'] as $key => $fileName) {
            $newFileName = bin2hex(random_bytes(16)) . '.pdf';
            $destination = $uploadDirectory . $newFileName;

            if (!move_uploaded_file($documents['tmp_name'][$key], $destination)) {
                $documentsError = "Failed to upload file: $fileName";
                break;
            }
        }
    }
}
