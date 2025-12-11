<a href="https://www.google.com" class="quick-exit-circle" title="Exit Site Immediately">
    &times; </a>

<style>
    .quick-exit-circle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;  /* Fixed width */
        height: 60px; /* Fixed height (same as width) */
        background: #d32f2f; /* Red */
        color: white;
        border-radius: 50%; /* Makes it a perfect circle */
        text-align: center;
        line-height: 55px; /* Vertically centers the X */
        font-size: 2.5rem; /* Size of the X */
        font-weight: bold;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        z-index: 10000;
        border: 3px solid white; /* White ring to make it pop */
        transition: transform 0.2s, background 0.2s;
        display: block; /* Ensures dimensions work */
    }

    .quick-exit-circle:hover {
        background: #b71c1c; /* Darker red on hover */
        transform: scale(1.1); /* Slightly grows when you hover */
        cursor: pointer;
    }

    /* Mobile Adjustment: Slightly smaller on phones */
    @media (max-width: 768px) {
        .quick-exit-circle {
            width: 50px;
            height: 50px;
            line-height: 45px;
            font-size: 2rem;
            bottom: 15px;
            right: 15px;
        }
    }
</style>

<script>
    // Keep the "Escape" key functionality - it's a lifesaver!
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            window.location.href = "https://www.google.com";
        }
    });
</script>