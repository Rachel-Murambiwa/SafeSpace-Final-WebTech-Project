<?php
session_start();
require '../db/config.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch ONLY this user's journal entries (Privacy Enforced here)
$sql = "SELECT * FROM journal_entries_safespace WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Journal - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/community.css"> <link rel="stylesheet" href="../assets/css/journal.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <h1 class="logo">SafeSpace 💜</h1>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="community.php">Community</a>
                <a href="profile.php">My Profile</a>
                <a href="../actions/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container journal-container">
        
        <div class="journal-editor">
            <div class="paper-sheet">
                <h2>Dear Diary... 🖊️</h2>
                <form action="../actions/add_journal.php" method="POST">
                    
                    <input type="text" name="title" class="journal-title" placeholder="Title of your thought..." required>
                    
                    <div class="mood-selector">
            <label>How are you feeling right now?</label>
                    <div class="mood-selector">
                    <div class="mood-grid">
                        <input type="radio" name="mood" value="Honest" id="m_honest"><label for="m_honest" title="Honest">😇</label>
                        <input type="radio" name="mood" value="Exhilarated" id="m_exhil"><label for="m_exhil" title="Exhilarated">🤩</label>
                        <input type="radio" name="mood" value="Inspired" id="m_insp"><label for="m_insp" title="Inspired">✨</label>
                        <input type="radio" name="mood" value="Ecstatic" id="m_ecst"><label for="m_ecst" title="Ecstatic">😁</label>
                        <input type="radio" name="mood" value="Delighted" id="m_delight"><label for="m_delight" title="Delighted">😄</label>
                        <input type="radio" name="mood" value="Relieved" id="m_relieved"><label for="m_relieved" title="Relieved">😅</label>

                        <input type="radio" name="mood" value="Amused" id="m_amused"><label for="m_amused" title="Amused">😂</label>
                        <input type="radio" name="mood" value="Pleased" id="m_pleased"><label for="m_pleased" title="Pleased">🙂</label>
                        <input type="radio" name="mood" value="Lucky" id="m_lucky"><label for="m_lucky" title="Lucky">🤞</label>
                        <input type="radio" name="mood" value="Elated" id="m_elated"><label for="m_elated" title="Elated">🥳</label>
                        <input type="radio" name="mood" value="Content" id="m_content"><label for="m_content" title="Content">😌</label>
                        <input type="radio" name="mood" value="Loved" id="m_loved"><label for="m_loved" title="Loved">🥰</label>

                        <input type="radio" name="mood" value="Enthusiastic" id="m_enth"><label for="m_enth" title="Enthusiastic">🙌</label>
                        <input type="radio" name="mood" value="Romantic" id="m_rom"><label for="m_rom" title="Romantic">😘</label>
                        <input type="radio" name="mood" value="Happy" id="m_happy" checked><label for="m_happy" title="Happy">😊</label>
                        <input type="radio" name="mood" value="Secure" id="m_secure"><label for="m_secure" title="Secure">🔒</label>
                        <input type="radio" name="mood" value="Satisfied" id="m_sat"><label for="m_sat" title="Satisfied">😋</label>
                        <input type="radio" name="mood" value="Lively" id="m_live"><label for="m_live" title="Lively">💃</label>

                        <input type="radio" name="mood" value="Silly" id="m_silly"><label for="m_silly" title="Silly">🤪</label>
                        <input type="radio" name="mood" value="Disgusted" id="m_disg"><label for="m_disg" title="Disgusted">🤢</label>
                        <input type="radio" name="mood" value="Grateful" id="m_grat"><label for="m_grat" title="Grateful">🙏</label>
                        <input type="radio" name="mood" value="Embarrassed" id="m_emb"><label for="m_emb" title="Embarrassed">😳</label>
                        <input type="radio" name="mood" value="Subdued" id="m_sub"><label for="m_sub" title="Subdued">😶</label>
                        <input type="radio" name="mood" value="Confused" id="m_conf"><label for="m_conf" title="Confused">😕</label>

                        <input type="radio" name="mood" value="Speechless" id="m_speech"><label for="m_speech" title="Speechless">😶</label>
                        <input type="radio" name="mood" value="Suspicious" id="m_susp"><label for="m_susp" title="Suspicious">🤨</label>
                        <input type="radio" name="mood" value="Apathetic" id="m_apath"><label for="m_apath" title="Apathetic">😐</label>
                        <input type="radio" name="mood" value="Peeved" id="m_peev"><label for="m_peev" title="Peeved">😒</label>
                        <input type="radio" name="mood" value="Distracted" id="m_dist"><label for="m_dist" title="Distracted">🤯</label>
                        <input type="radio" name="mood" value="Indifferent" id="m_indif"><label for="m_indif" title="Indifferent">🤷</label>

                        <input type="radio" name="mood" value="Shy" id="m_shy"><label for="m_shy" title="Shy">🙈</label>
                        <input type="radio" name="mood" value="Sullen" id="m_sull"><label for="m_sull" title="Sullen">😔</label>
                        <input type="radio" name="mood" value="Annoyed" id="m_annoy"><label for="m_annoy" title="Annoyed">😤</label>
                        <input type="radio" name="mood" value="Awkward" id="m_awk"><label for="m_awk" title="Awkward">😬</label>
                        <input type="radio" name="mood" value="Distressed" id="m_distr"><label for="m_distr" title="Distressed">😩</label>
                        <input type="radio" name="mood" value="Miserable" id="m_mis"><label for="m_mis" title="Miserable">😭</label>

                        <input type="radio" name="mood" value="Hungry" id="m_hung"><label for="m_hung" title="Hungry">😋</label>
                        <input type="radio" name="mood" value="Sleepy" id="m_sleep"><label for="m_sleep" title="Sleepy">😴</label>
                        <input type="radio" name="mood" value="Ill" id="m_ill"><label for="m_ill" title="Ill">🤒</label>
                        <input type="radio" name="mood" value="Injured" id="m_inj"><label for="m_inj" title="Injured">🤕</label>
                        <input type="radio" name="mood" value="Queasy" id="m_queas"><label for="m_queas" title="Queasy">🥴</label>
                        <input type="radio" name="mood" value="Nauseated" id="m_naus"><label for="m_naus" title="Nauseated">🤮</label>
                    </div>
</div>
                    </div>

                    <textarea name="content" class="journal-content" placeholder="Write freely. This is private." required></textarea>
                    
                    <button type="submit" class="btn-save">Save Entry 🔒</button>
                </form>
            </div>
        </div>

        <div class="journal-history">
            <h3>Past Entries</h3>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="journal-card mood-<?php echo strtolower($row['mood']); ?>">
                        <div class="card-header">
                            <span class="entry-date"><?php echo date("M j, Y • g:i a", strtotime($row['created_at'])); ?></span>
                            <span class="entry-mood"><?php echo getMoodEmoji($row['mood']); ?></span>
                        </div>
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No entries yet. Start writing your first thought!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php include "../utils/exit_button.php" ?>
</body>
</html>

<?php
// Helper function to show emoji based on stored text
function getMoodEmoji($mood) {
    // Map text to emojis
    $emojis = [
        'Honest' => '😇', 'Exhilarated' => '🤩', 'Inspired' => '✨', 'Ecstatic' => '😁',
        'Delighted' => '😄', 'Relieved' => '😅', 'Amused' => '😂', 'Pleased' => '🙂',
        'Lucky' => '🤞', 'Elated' => '🥳', 'Content' => '😌', 'Loved' => '🥰',
        'Enthusiastic' => '🙌', 'Romantic' => '😘', 'Happy' => '😊', 'Secure' => '🔒',
        'Satisfied' => '😋', 'Lively' => '💃', 'Silly' => '🤪', 'Disgusted' => '🤢',
        'Grateful' => '🙏', 'Embarrassed' => '😳', 'Subdued' => '😶', 'Confused' => '😕',
        'Speechless' => '😶', 'Suspicious' => '🤨', 'Apathetic' => '😐', 'Peeved' => '😒',
        'Distracted' => '🤯', 'Indifferent' => '🤷', 'Shy' => '🙈', 'Sullen' => '😔',
        'Annoyed' => '😤', 'Awkward' => '😬', 'Distressed' => '😩', 'Miserable' => '😭',
        'Hungry' => '😋', 'Sleepy' => '😴', 'Ill' => '🤒', 'Injured' => '🤕',
        'Queasy' => '🥴', 'Nauseated' => '🤮'
    ];

    if (array_key_exists($mood, $emojis)) {
        return $emojis[$mood] . ' ' . $mood;
    } else {
        return '😐 ' . $mood; 
    }
}
?>