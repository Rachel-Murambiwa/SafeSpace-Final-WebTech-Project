const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

// 2. Add click event
hamburger.addEventListener('click', () => {
    // Toggle the 'active' class on the links
    navLinks.classList.toggle('active');
});