<?php
session_start();
require '../db/config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fname = $_SESSION['fname']; 

$has_profile = false;

$sql = "SELECT anonymous_username, avatar_color FROM profiles_safespace WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $has_profile = true;
    $stmt->bind_result($anon_name, $avatar_color);
    $stmt->fetch();
    
    $_SESSION['anonymous_username'] = $anon_name;
    $_SESSION['avatar_color'] = $avatar_color;
}
$stmt->close();

if (!isset($_SESSION['daily_quote'])) {
    $quote_sql = "SELECT content, author FROM quotes_safespace ORDER BY RAND() LIMIT 1";
    $q_stmt = $conn->prepare($quote_sql);
    
    if ($q_stmt) {
        $q_stmt->execute();
        $q_result = $q_stmt->get_result();

        if ($q_result->num_rows > 0) {
            $quote_data = $q_result->fetch_assoc();
            $_SESSION['daily_quote'] = $quote_data['content'];
            $_SESSION['daily_author'] = $quote_data['author'];
        } 
        else {
            $_SESSION['daily_quote'] = "You are stronger than you know.";
            $_SESSION['daily_author'] = "SafeSpace";
        }
        $q_stmt->close();
    }
}

$daily_quote = $_SESSION['daily_quote'];
$daily_author = $_SESSION['daily_author'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_mood'])) {
    $mood_val = $_POST['mood_value']; 
    $mood_lbl = $_POST['mood_label']; 
    

    $check_sql = "SELECT id FROM mood_log WHERE user_id = ? AND entry_date = CURRENT_DATE";
    $c_stmt = $conn->prepare($check_sql);
    $c_stmt->bind_param("i", $user_id);
    $c_stmt->execute();
    
    if ($c_stmt->get_result()->num_rows == 0) {
        $ins_sql = "INSERT INTO mood_log (user_id, mood_value, mood_label) VALUES (?, ?, ?)";
        $i_stmt = $conn->prepare($ins_sql);
        $i_stmt->bind_param("iis", $user_id, $mood_val, $mood_lbl);
        $i_stmt->execute();
    }
 
    header("Location: dashboard.php");
    exit();
}

$dates = [];
$scores = [];

$chart_sql = "SELECT entry_date, mood_value FROM mood_log WHERE user_id = ? ORDER BY entry_date ASC LIMIT 7";
$ch_stmt = $conn->prepare($chart_sql);
$ch_stmt->bind_param("i", $user_id);
$ch_stmt->execute();
$result = $ch_stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $dates[] = date('M d', strtotime($row['entry_date'])); 
    $scores[] = $row['mood_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/style.css">     <link rel="stylesheet" href="../assets/css/dashboard.css"> </head>
<body>

    <header class="navbar">
        <div class="container nav-container">
            
            <div class="logo">
                <h1>SafeSpace 💜</h1>
            </div>

            <button class="hamburger" id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <nav>
                <ul class="nav-links" id="nav-links">
                    <li><a href="profile.php" style="color: #00BCD4;">My Profile</a></li>
                    <li><a href="../actions/logout.php" class="btn-join" style="background: #FFB6C1; color: #333;">Logout</a></li>
                </ul>
            </nav>

        </div>
    </header>

    <div class="container dashboard-container">
        
        <header class="dash-header">
            <h1>Welcome, <?php echo htmlspecialchars($fname); ?> 🌸</h1>
            <p>You are safe here.</p>
        </header>

        <?php if (!$has_profile): ?>
            
            <div class="setup-card">
                <h2>Create Your Persona</h2>
                <p>To keep you safe, we use anonymous names. Enter yours below.</p>
                
                <form action="../actions/create_profile.php" method="POST">
                    
                    <div class="input-group">
                        <label>Enter an Anonymous Username</label>
                        <input type="text" name="anonymous_username" placeholder="e.g. HopefulStar, BlueSky22" required>
                    </div>

                    <label>Choose your Aura (Avatar Color)</label>
                    <div class="color-picker">
                        <input type="radio" name="avatar_color" value="#ef092cff" id="c1" checked><label for="c1" style="background:#ef092cff;"></label>
                        <input type="radio" name="avatar_color" value="#ea2fc8e1" id="c2"><label for="c2" style="background:#3a2;"></label>
                        <input type="radio" name="avatar_color" value="#4117e7ff" id="c3"><label for="c3" style="background:#4117e7ff;"></label>
                        <input type="radio" name="avatar_color" value="#E6E6FA" id="c4"><label for="c4" style="background:#E6E6FA;"></label>
                        <input type="radio" name="avatar_color" value="#FFD700" id="c5"><label for="c5" style="background:#FFD700;"></label>
                        <input type="radio" name="avatar_color" value="#0eef1dff" id="c6"><label for="c6" style="background:#0eef1dff;"></label>
                        <input type="radio" name="avatar_color" value="#690598ff" id="c7"><label for="c7" style="background:#690598ff;"></label>
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
                    <a href="../view/journal.php" class="btn-link">Open Journal</a>
                </div>

                <div class="card">
                    <h3>📢 Community Stories</h3>
                    <p>Read stories from others.</p>
                    <a href="../view/community.php" class="btn-link">Read Stories</a>
                </div>

                <div class="card quote-card">
                <div class="quote-icon">❝</div>
                <p class="quote-text">"<?php echo htmlspecialchars($daily_quote); ?>"</p>
                <p class="quote-author">- <?php echo htmlspecialchars($daily_author); ?></p>
            </div>
                <div class="card mood-card">
                    <h3>Check-in: How are you feeling?</h3>
                    
                    <form method="POST" class="mood-form">
                        <input type="hidden" name="log_mood" value="1">
                        
                        <button type="submit" name="mood_value" value="4" onclick="this.form.mood_label.value='Happy'" class="mood-btn happy">
                            😊<br>Happy
                        </button>
                        <button type="submit" name="mood_value" value="3" onclick="this.form.mood_label.value='Calm'" class="mood-btn calm">
                            😌<br>Calm
                        </button>
                        <button type="submit" name="mood_value" value="2" onclick="this.form.mood_label.value='Anxious'" class="mood-btn anxious">
                            😰<br>Anxious
                        </button>
                        <button type="submit" name="mood_value" value="1" onclick="this.form.mood_label.value='Sad'" class="mood-btn sad">
                            😔<br>Sad
                        </button>
                        
                        <input type="hidden" name="mood_label" id="mood_label">
                    </form>

                    <div class="chart-container">
                        <canvas id="moodChart"></canvas>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <script src ="../assets/js/dashboard.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const dates = <?php echo json_encode($dates); ?>;
    const scores = <?php echo json_encode($scores); ?>;

    const ctx = document.getElementById('moodChart').getContext('2d');
    const moodChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates, 
            datasets: [{
                label: 'Mood History',
                data: scores, 
                borderColor: '#00BCD4', 
                backgroundColor: 'rgba(0, 188, 212, 0.1)', 
                borderWidth: 2,
                tension: 0.4, 
                pointBackgroundColor: '#FFB6C1',
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 5,
                    ticks: {
                        // Convert numbers back to words for the Y-axis labels
                        callback: function(value) {
                            if(value === 1) return 'Sad';
                            if(value === 2) return 'Anxious';
                            if(value === 3) return 'Calm';
                            if(value === 4) return 'Happy';
                            return null;
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false } // Hide the legend box
            }
        }
    });
</script>
<?php include "../utils/exit_button.php" ?>
</body>
</html>