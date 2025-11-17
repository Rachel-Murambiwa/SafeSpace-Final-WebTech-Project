// script.js — Shared behavior for demo only
// NOTE: This demo uses localStorage for simplicity. Replace with real backend/auth for production.

document.addEventListener("DOMContentLoaded", () => {
  // set footer year
  const yearEls = document.querySelectorAll("#year, #year2");
  yearEls.forEach(el => el.textContent = new Date().getFullYear());

  // hero fade animation
  const hero = document.querySelector(".hero-inner");
  if (hero) {
    hero.style.opacity = 0;
    hero.style.transform = "translateY(8px)";
    setTimeout(() => {
      hero.style.transition = "all 600ms ease";
      hero.style.opacity = 1;
      hero.style.transform = "translateY(0)";
    }, 150);
  }

  // REGISTER handler
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const name = document.getElementById("regName").value.trim();
      const email = document.getElementById("regEmail").value.trim();
      const password = document.getElementById("regPassword").value;

      if (!name || !password) {
        alert("Please enter a display name and password.");
        return;
      }

      // Simple user object — demo only (no hashing). For real apps use secure backend.
      const user = { name, email, password, createdAt: new Date().toISOString() };
      // Save to localStorage using email as key if provided, otherwise use name.
      const key = email || `anon:${name}`;
      localStorage.setItem("safespace_user", JSON.stringify(user));
      // optional: save active flag
      localStorage.setItem("safespace_logged_in", key);

      alert("Account created (demo). You will be redirected to the Home page.");
      window.location.href = "index.html";
    });
  }

  // LOGIN handler
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = document.getElementById("loginEmail").value.trim();
      const password = document.getElementById("loginPassword").value;

      const storedRaw = localStorage.getItem("safespace_user");
      if (!storedRaw) {
        alert("No account found. Please register first.");
        return;
      }
      const stored = JSON.parse(storedRaw);

      // This demo checks against the single stored user. Replace with backend logic.
      if ((email && email === stored.email && password === stored.password) ||
          (!email && password === stored.password)) {
        const key = stored.email || `anon:${stored.name}`;
        localStorage.setItem("safespace_logged_in", key);
        alert("Login successful (demo). Redirecting to Home...");
        window.location.href = "index.html";
      } else {
        alert("Incorrect credentials.");
      }
    });
  }

  // simple nav active link highlight (based on path)
  const links = document.querySelectorAll(".nav-link");
  links.forEach(a => {
    try {
      const href = a.getAttribute("href");
      if (href && location.pathname.endsWith(href)) {
        a.classList.add("active");
      }
    } catch (err) { /* ignore */ }
  });
});
