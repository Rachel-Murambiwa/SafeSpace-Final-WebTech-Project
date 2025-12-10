// 1. SELECT ELEMENTS
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmpassword');
const form = document.querySelector('.register-form');

const requirements = {
    length: document.getElementById('req-length'),
    upper: document.getElementById('req-upper'),
    lower: document.getElementById('req-lower'),
    number: document.getElementById('req-number'),
    special: document.getElementById('req-special')
};

// 2. DEFINE PATTERNS
const patterns = {
    length: /.{8,}/,
    upper: /[A-Z]/,
    lower: /[a-z]/,
    number: /[0-9]/,
    special: /[\W_]/
};

// 3. EVENT LISTENER 1: REAL-TIME TYPING (Input Event)
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const value = passwordInput.value;

        for (const [key, pattern] of Object.entries(patterns)) {
            const element = requirements[key];
            if (pattern.test(value)) {
                element.classList.remove('invalid');
                element.classList.add('valid');
            } else {
                element.classList.remove('valid');
                element.classList.add('invalid');
            }
        }
    });
}

// 4. EVENT LISTENER 2: FORM SUBMISSION (Submit Event)
if (form) {
    form.addEventListener('submit', function(event) {
        // Prevent submission if passwords don't match
        if (passwordInput.value !== confirmPasswordInput.value) {
            alert("Error: Passwords do not match.");
            event.preventDefault(); // STOP form from going to PHP
            return;
        }

        // Prevent submission if requirements aren't met
        // We check if any requirement still has the 'invalid' class
        const invalidRequirements = document.querySelectorAll('#password-requirements .invalid');
        if (invalidRequirements.length > 0) {
            alert("Please meet all password requirements.");
            event.preventDefault(); // STOP form
        }
    });
}

const matchMessage = document.getElementById('match-message');

function checkMatch() {
    // If we can't find the message area or inputs, stop to prevent errors
    if (!matchMessage || !passwordInput || !confirmPasswordInput) return;

    const pass = passwordInput.value;
    const confirm = confirmPasswordInput.value;

    // If the confirm box is empty, clear the message
    if (confirm === "") {
        matchMessage.textContent = "";
        matchMessage.className = "";
        return;
    }

    // Check if they match
    if (pass === confirm) {
        matchMessage.textContent = "Passwords Match";
        matchMessage.className = "match-success";
    } else {
        matchMessage.textContent = "Passwords Do Not Match";
        matchMessage.className = "match-error";
    }
}

// 3. ADD EVENT LISTENERS (This is what makes it work!)
if (confirmPasswordInput) {
    // Run checkMatch every time user types in "Confirm Password"
    confirmPasswordInput.addEventListener('input', checkMatch);
}

if (passwordInput) {
    // Run checkMatch if user goes back and changes "Original Password" too
    passwordInput.addEventListener('input', checkMatch);
}

// ... existing code ...

// CHECK FOR SUCCESS PARAMETER IN URL
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // If the URL contains "?success=true"
    if (urlParams.get('success') === 'true') {
        const msgElement = document.getElementById('success-message');
        if (msgElement) {
            msgElement.textContent = "Registered Successfully!";
            msgElement.style.color = "#2ecc71"; // Green color
            msgElement.style.fontWeight = "bold";
            msgElement.style.textAlign = "center";
            msgElement.style.marginTop = "15px";
            
            // Optional: Clear the form fields
            const form = document.querySelector('.register-form');
            if(form) form.reset();
        }
    }
});