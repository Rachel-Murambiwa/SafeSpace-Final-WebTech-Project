<?php
session_start();
require '../db/config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$fname = $_SESSION['fname']; // From Login Session

// 2. CHECK: Does user have a profile?
$has_profile = false;

// Check database
$sql = "SELECT anonymous_username, avatar_color FROM profiles_safespace WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $has_profile = true;
    $stmt->bind_result($anon_name, $avatar_color);
    $stmt->fetch();
    
    // Store in session for later use
    $_SESSION['anonymous_username'] = $anon_name;
    $_SESSION['avatar_color'] = $avatar_color;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css"> </head>
<body>

    <nav class="navbar">
        <div class="container nav-container">
            <h1 class="logo">SafeSpace 💜</h1>
            <ul class="nav-links">
                <li><a href="../actions/logout.php" class="btn-join" style="background: #FFB6C1; color: #333;">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container dashboard-container">
        
        <header class="dash-header">
            <h1>Welcome, <?php echo htmlspecialchars($fname); ?> 🌸</h1>
            <p>You are safe here.</p>
        </header>

        <?php if (!$has_profile): ?>
            
            <div class="setup-card">
                <h2>Create Your Persona</h2>
                <p>To keep you safe, we use anonymous names. Choose yours below.</p>
                
                <form action="../actions/create_profile.php" method="POST">
                    
                    <div class="input-group">
                        <label>Choose an Anonymous Username</label>
                        <input type="text" name="anonymous_username" placeholder="e.g. HopefulStar, BlueSky22" required>
                    </div>

                    <label>Choose your Aura (Avatar Color)</label>
                    <div class="color-picker">
                        <input type="radio" name="avatar_color" value="#FFB6C1" id="c1" checked><label for="c1" style="background:#FFB6C1;"></label>
                        <input type="radio" name="avatar_color" value="#40E0D0" id="c2"><label for="c2" style="background:#40E0D0;"></label>
                        <input type="radio" name="avatar_color" value="#00BCD4" id="c3"><label for="c3" style="background:#00BCD4;"></label>
                        <input type="radio" name="avatar_color" value="#E6E6FA" id="c4"><label for="c4" style="background:#E6E6FA;"></label>
                        <input type="radio" name="avatar_color" value="#FFD700" id="c5"><label for="c5" style="background:#FFD700;"></label>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 20px;">Start My Journey</button>
                </form>
            </div>

        <?php else: ?>

            <div class="dash-grid">
                <div class="card profile-card" style="border-top: 5px solid <?php echo $avatar_color; ?>;">
                    <div class="avatar-circle" style="background: <?php echo $avatar_color; ?>;">
                        <?php echo strtoupper(substr($anon_name, 0, 1)); ?>
                    </div>
                    <h3><?php echo htmlspecialchars($anon_name); ?></h3>
                    <p>Your Identity is Safe.</p>
                </div>

                <div class="card">
                    <h3>📖 My Journal</h3>
                    <p>Write your thoughts privately.</p>
                    <a href="#" class="btn-link">Open Journal</a>
                </div>

                <div class="card">
                    <h3>📢 Community Stories</h3>
                    <p>Read stories from others.</p>
                    <a href="#" class="btn-link">Read Stories</a>
                </div>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>