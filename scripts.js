
function showForm(formType) {
    document.getElementById('loginForm').style.display = formType === 'login' ? 'block' : 'none';
    document.getElementById('signupForm').style.display = formType === 'signup' ? 'block' : 'none';
}

function validateLogin(event) {
    event.preventDefault(); // Prevent default submission to handle validation

    let email = event.target.email.value;
    let password = event.target.password.value;
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address');
    } else if (password.length < 6) {
        alert('Password must be at least 6 characters long');
    } else {
        // If validation passes, submit the form
        event.target.submit(); // Directly submit the form
    }
}
function validateSignup(event) {
    event.preventDefault(); // Prevent the default form submission
    let password = event.target.password.value;
    let phone = event.target.phone.value;
    let email = event.target.email.value;

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address');
    } else if (password.length < 6) {
        alert('Password must be at least 6 characters long');
    } else if (phone.length !== 10 || isNaN(phone)) {
        alert('Phone number must be 10 digits');
    } else {
        // If validation passes, use fetch to submit the form
        const formData = new FormData(event.target);
        fetch('signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Show success or error message to the user
            if (data.success) {
                showForm('login'); // Show login form on successful signup
            }
        })
        showForm('login');
    }
}

function login() {
    const role = document.querySelector('#loginForm input[name="role"]:checked').value;
    const email = document.getElementById("loginEmail").value;

    if (role === "admin") {
        alert("Admin Login: " + email);
        // Process admin login logic
    } else {
        alert("User Login: " + email);
        // Process user login logic
    }
}

function signup() {
    const role = document.querySelector('#signupForm input[name="role"]:checked').value;
    const firstName = document.getElementById("firstName").value;
    const lastName = document.getElementById("lastName").value;
    const email = document.getElementById("signupEmail").value;
    const phone = document.getElementById("phone").value;
    const password = document.getElementById("signup-password").value;

    alert(`${role.charAt(0).toUpperCase() + role.slice(1)} Signup: ${email}`);
    // Process signup logic
}

// Logout function for navigation bar
function logout() {
    const confirmed = confirm("Are you sure you want to log out?");
    if (confirmed) {
        alert("You have been logged out.");
        window.location.href = "#home"; // Redirect to home page or login page
    }
}

// Toggles the visibility of the password field
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = passwordField.nextElementSibling;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.textContent = "🙈"; // Change icon to hide
    } else {
        passwordField.type = "password";
        eyeIcon.textContent = "👁️"; // Change icon to show
    }
}

// Displays the login or signup form
function showForm(formType) {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    
    if (formType === 'login') {
        loginForm.style.display = 'block';
        signupForm.style.display = 'none';
    } else {
        signupForm.style.display = 'block';
        loginForm.style.display = 'none';
    }
}

// Simulates a logout action
function logout() {
    alert('You have been logged out.');
    // Add logout logic here
}
