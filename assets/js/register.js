document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. SELECT ELEMENTS ---
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password'); // FIXED ID
    const form = document.querySelector('.register-form');
    const matchMessage = document.getElementById('match-message');
    const msgElement = document.getElementById('success-message');

    const requirements = {
        length: document.getElementById('req-length'),
        upper: document.getElementById('req-upper'),
        lower: document.getElementById('req-lower'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };

    // --- 2. DEFINE PATTERNS ---
    const patterns = {
        length: /.{8,}/,
        upper: /[A-Z]/,
        lower: /[a-z]/,
        number: /[0-9]/,
        special: /[\W_]/ // Matches symbols and underscores
    };

    // --- 3. PASSWORD VALIDATION (Real-time) ---
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;
            
            // Loop through patterns and update UI
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
            // Also check match whenever original password changes
            checkMatch(); 
        });
    }

    // --- 4. CONFIRM PASSWORD CHECKER ---
    function checkMatch() {
        if (!matchMessage || !passwordInput || !confirmPasswordInput) return;

        const pass = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        if (confirm === "") {
            matchMessage.textContent = "";
            matchMessage.className = "";
            return;
        }

        if (pass === confirm) {
            matchMessage.textContent = "Passwords Match";
            matchMessage.className = "match-success";
        } else {
            matchMessage.textContent = "Passwords Do Not Match";
            matchMessage.className = "match-error";
        }
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkMatch);
    }

    // --- 5. FORM SUBMISSION ---
    if (form) {
        form.addEventListener('submit', function(event) {
            // Check Match
            if (passwordInput.value !== confirmPasswordInput.value) {
                alert("Error: Passwords do not match.");
                event.preventDefault(); 
                return;
            }

            // Check Requirements
            const invalidRequirements = document.querySelectorAll('#password-requirements .invalid');
            if (invalidRequirements.length > 0) {
                alert("Please meet all password requirements.");
                event.preventDefault();
            }
        });
    }

    // --- 6. URL SUCCESS CHECK ---
    // Check if URL has ?success=true
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        if (msgElement) {
            msgElement.textContent = "Registered Successfully!";
            msgElement.style.color = "#2ecc71"; 
            msgElement.style.fontWeight = "bold";
            msgElement.style.textAlign = "center";
            msgElement.style.marginTop = "15px";
            
            if(form) form.reset();
        }
    }
});