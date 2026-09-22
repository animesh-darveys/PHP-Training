// name validation function
const nameInput = document.querySelector("#name");
const nameError = document.querySelector("#nameError");

function validateName() {
    if (!nameInput) {
        return true;
    }
    const name = nameInput.value.trim();
    if (!/^[A-Za-z ]{3,50}$/.test(name)) {
        nameError.textContent = "Name must be 3-50 characters and contain only letters and spaces";
        nameError.classList.add("show");
        return false;
    }
    nameError.textContent = "";
    nameError.classList.remove("show");
    return true;
}

// phone number validation function
const phoneInput = document.querySelector("#phone");
const phoneError = document.querySelector("#phoneError");

function validatePhone() {
    if (!phoneInput) {
        return true;
    }
    const phone = phoneInput.value.trim();
    if (!/^\d{10}$/.test(phone)) {
        phoneError.textContent = "phone must be 10 digits";
        phoneError.classList.add("show");
        return false;
    }
    phoneError.textContent = "";
    phoneError.classList.remove("show");
    return true;
}

// email validation function

const emailInput = document.querySelector("#email");
const emailError = document.querySelector("#emailError");

function validateEmail() {
    if (!emailInput) {
        return true;
    }
    const email = emailInput.value.trim();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
        emailError.textContent = "Please enter a valid email address";
        emailError.classList.add("show");
        return false;
    }
    emailError.textContent = "";
    emailError.classList.remove("show");
    return true;
}

// Address validation

const addressInput = document.querySelector("#address");
const addressError = document.querySelector("#addressError");

function validateAddress() {
    if (!addressInput) {
        return true;
    }
    const address = addressInput.value.trim();
    if (!/^.{10,200}$/.test(address)) {
        addressError.textContent = "Address must be between 10 to 200 characters.";
        addressError.classList.add("show");
        return false;
    }
    addressError.textContent = "";
    addressError.classList.remove("show");
    return true;
}

// Date of birth validation

const dobInput = document.querySelector("#dob");
const dobError = document.querySelector("#dobError");

function validateDob() {
    if (!dobInput) {
        return true;
    }
    const dob = dobInput.value.trim();
    if (dob == '') {
        dobError.textContent = "This input field is required";
        dobError.classList.add("show");
        return false;
    }
    dobError.textContent = "";
    dobError.classList.remove("show");
    return true;
}

// account type validation
const allowedAccountType = ['savings', 'current']
const accountTypeInput = document.querySelector("#accountType");
const accountTypeError = document.querySelector("#accountTypeError");

function validateAccountType(){
    if (!accountTypeInput) {
        return true;
    }
    const accountType = accountTypeInput.value.trim();
    if (!allowedAccountType.includes(accountType)) {
        accountTypeError.textContent="Invalid account type.";
        accountTypeError.classList.add("show");
        return false;
    }
    accountTypeError.textContent = "";
    accountTypeError.classList.remove("show");
    return true;
}


// submit form

const form = document.querySelector("#customer-form");

form.addEventListener("submit", function(event){
    event.preventDefault();

    const isValidName = validateName();
    const isValidPhone =validatePhone();
    const isValidEmail =validateEmail();
    const isValidAddress =validateAddress();
    const isValidDob =validateDob();
    const isValidAccountType =validateAccountType();
    

    if (
        !isValidName ||
        !isValidPhone ||
        !isValidEmail ||
        !isValidAddress ||
        !isValidDob ||
        !isValidAccountType
    ) {
        return;
    }

    form.submit();
});

