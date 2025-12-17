<?php
session_start();
require '../db/config.php';

// 1. Security: Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// 2. Fetch the post AND verify ownership
// We join with profiles to ensure the logged-in user owns the profile that made the post
$sql = "SELECT p.content, p.post_id 
        FROM public_posts_safespace p
        JOIN profiles_safespace pr ON p.profile_id = pr.profile_id
        WHERE p.post_id = ? AND pr.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Post doesn't exist OR user doesn't own it
    echo "Access Denied or Post Not Found.";
    exit();
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/community.css">
    <style>
        .edit-container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .edit-header { text-align: center; margin-bottom: 20px; color: #00BCD4; }
        textarea { width: 100%; height: 150px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit; resize: vertical; }
        .btn-save { background: #00BCD4; color: white; border: none; padding: 10px 25px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 1rem; margin-top: 15px; }
        .btn-cancel { background: #eee; color: #333; text-decoration: none; padding: 10px 25px; border-radius: 20px; font-weight: bold; margin-right: 10px; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2 class="edit-header">Edit Your Story ✏️</h2>
    
    <form action="../actions/update_post.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
        
        <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        
        <div style="margin-top: 20px; text-align: right;">
            <a href="profile.php" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
    </form>
</div>
<?php include '../utils/exit_button.php'; ?>
</body>
</html>