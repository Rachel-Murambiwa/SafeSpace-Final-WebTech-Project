
document.addEventListener("DOMContentLoaded", function() {
    // 1. Get the URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const signup = urlParams.get('signup'); 
    
    const msgElement = document.getElementById('login-message');
    const successElement = document.getElementById('success-message'); 

    if (msgElement) {
        // 2. Check for ERRORS
        if (error === 'empty') {
            msgElement.textContent = "Please fill in all fields.";
        } 
        else if (error === 'wrong_password') {
            msgElement.textContent = "Incorrect password. Please try again.";
        } 
        else if (error === 'not_found') {
            msgElement.textContent = "No account found with that email.";
        } 
        else if (error === 'system') {
            msgElement.textContent = "System error. Please try again later.";
        }
    }

    // 3. Check for SUCCESS (from Registration)
    // If you used the ID 'login-message' for success too, you can handle it here:
    if (signup === 'success' && msgElement) {
        msgElement.textContent = "Registration Successful! Please login.";
        msgElement.style.color = "green"; // Override red color
    }
});
