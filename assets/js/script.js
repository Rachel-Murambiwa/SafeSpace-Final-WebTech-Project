// Optional small interaction
document.addEventListener("DOMContentLoaded", () => {
    console.log("SafeSpace home page loaded.");

    // Example simple animation (fade-in)
    const hero = document.querySelector(".hero");
    hero.style.opacity = 0;
    setTimeout(() => {
        hero.style.transition = "1.2s ease";
        hero.style.opacity = 1;
    }, 200);
});
