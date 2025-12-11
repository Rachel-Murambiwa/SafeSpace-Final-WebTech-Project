<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeSpace - Where Healing Begins</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

   <header class="navbar">
    <div class="container nav-container">
        
        <div class="logo">
            <h1>SafeSpace</h1>
            <span>Where healing begins</span>
        </div>

        <button class="hamburger" id="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <nav>
            <ul class="nav-links" id="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#mission">Mission</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#resources">Resources</a></li>
                <li><a href="view/login.php" class="btn-login">Login</a></li>
                <li><a href="view/register.php" class="btn-join">Join Now</a></li>
            </ul>
        </nav>

    </div>
</header>

    <section id="home" class="hero">
        <div class="container hero-content">
            <h2>Welcome to SafeSpace</h2>
            <p>A safe haven where women can share, heal, and grow, 
                strengthened by community and free from judgment.</p>
            <div class="hero-btns">
                <a href="view/register.php" class="btn btn-outline">Join Community</a>
                <a href="#about" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </section>

    <section id="mission" class="mission">
        <div class="container">
            <h2>Our Mission</h2>
            <p>SafeSpace is dedicated to creating a supportive environment 
                where women can openly share their experiences, find comfort in community, 
                and embark on their healing journey without fear.</p>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Support Areas</h2>
                <p>Find the space that speaks to your heart</p>
            </div>
            
            <div class="grid-3">
                <div class="card">
                    <div class="icon">💜</div>
                    <h3>Depression Support</h3>
                    <p>A gentle place to share the weight you are carrying. 
                        You are not alone in the darkness.</p>
                </div>
                <div class="card">
                    <div class="icon">💔</div>
                    <h3>Healing Heartbreak</h3>
                    <p>Navigating the end of relationships and finding yourself again.</p>
                </div>
                <div class="card">
                    <div class="icon">🌸</div>
                    <h3>Self-Love Journey</h3>
                    <p>Daily affirmations and discussions on rebuilding self-esteem.</p>
                </div>
                <div class="card">
                    <div class="icon">🌟</div>
                    <h3>Anxiety & Stress</h3>
                    <p>Tools and support for managing overwhelming feelings and finding peace.</p>
                </div>
                <div class="card">
                    <div class="icon">🤝</div>
                    <h3>Friendship</h3>
                    <p>Connect with others and build meaningful relationships in a safe space.</p>
                </div>
                <div class="card">
                    <div class="icon">🦋</div>
                    <h3>Personal Growth</h3>
                    <p>Share your transformation journey and celebrate wins, big and small.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container split-layout">
            <div class="about-text">
                <h2>About SafeSpace</h2>
                <p>We believe that healing happens in community. 
                    Every woman deserves a safe place to be vulnerable.</p>
                <div class="stats">
                    <div><strong>1000+</strong><br>Women</div>
                    <div><strong>24/7</strong><br>Support</div>
                    <div><strong>100%</strong><br>Private</div>
                </div>
            </div>
            <div class="about-visual">
                <div class="placeholder-box">🌺</div>
            </div>
        </div>
    </section>

    <section id="resources" class="resources">
        <div class="container">
            <div class="section-title">
                <h2>Helpful Resources</h2>
            </div>
            <div class="grid-4">
                <div class="resource-card">📚 Self-Help Guides</div>
                <div class="resource-card">🎧 Meditation</div>
                <div class="resource-card">📞 Crisis Hotlines</div>
                <div class="resource-card">💬 Community Forums</div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container split-layout">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>We are here to listen. Your privacy is our priority.</p>
                <p>📧 safespace@gmail.com</p>
                <p>📞 1-800-SAFESPACE</p>
            </div>
            <div class="contact-form">
                <form>
                    <input type="text" placeholder="Your Name" required>
                    <input type="email" placeholder="Your Email" required>
                    <textarea rows="4" placeholder="How can we help?"></textarea>
                    <button type="submit" class="btn-outline">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 SafeSpace. Made with 💜 for healing and hope.</p>
        </div>
    </footer>
<script src = "assets/js/script.js"></script>
<?php include 'utils/exit_button.php'; ?>
</body>
</html>