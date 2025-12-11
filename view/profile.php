<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Fetch Profile Info
$sql_profile = "SELECT profile_id, anonymous_username, avatar_color FROM profiles_safespace WHERE user_id = ?";
$stmt = $conn->prepare($sql_profile);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_profile = $stmt->get_result();
$profile = $result_profile->fetch_assoc();
$stmt->close();

// 2. Fetch User's Posts
$posts = [];
if ($profile) {
    $profile_id = $profile['profile_id'];
    $sql_posts = "SELECT * FROM public_posts_safespace WHERE profile_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql_posts);
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result_posts = $stmt->get_result();
    while ($row = $result_posts->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-header { background: white; padding: 30px; text-align: center; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .avatar-large { width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; }
        .btn-edit { background: #FFD700; color: #333; padding: 5px 15px; border-radius: 15px; text-decoration: none; font-size: 0.9rem; font-weight: bold; margin-right: 10px; }
        .btn-delete { background: #e74c3c; color: white; padding: 5px 15px; border-radius: 15px; border: none; font-size: 0.9rem; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <h1 class="logo">SafeSpace 💜</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="community.php">Community</a>
            <a href="../actions/logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</nav>

<div class="container feed-container">
    
    <div class="profile-header">
        <div class="avatar-large" style="background: <?php echo $profile['avatar_color']; ?>;">
            <?php echo strtoupper(substr($profile['anonymous_username'], 0, 1)); ?>
        </div>
        <h2><?php echo htmlspecialchars($profile['anonymous_username']); ?></h2>
        <p><?php echo count($posts); ?> Posts Shared</p>
    </div>

    <h3>My Posts</h3>
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                
                <p class="post-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                
                <?php
                $post_id = $post['post_id'];
                // Query the images table
                $sql_img = "SELECT image_path FROM post_images_safespace WHERE post_id = $post_id";
                $result_img = $conn->query($sql_img);
                $images = [];
                while($img = $result_img->fetch_assoc()) {
                    $images[] = $img['image_path'];
                }
                ?>

                <?php if (count($images) > 0): ?>
                    <div class="carousel-container">
                        <div class="carousel-slide" id="carousel-<?php echo $post_id; ?>">
                            <?php foreach($images as $index => $image): ?>
                                <img src="../assets/uploads/<?php echo $image; ?>" 
                                     class="carousel-img <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     data-index="<?php echo $index; ?>">
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($images) > 1): ?>
                            <button class="prev-btn" onclick="moveSlide(<?php echo $post_id; ?>, -1)">&#10094;</button>
                            <button class="next-btn" onclick="moveSlide(<?php echo $post_id; ?>, 1)">&#10095;</button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="post-actions" style="justify-content: flex-end; margin-top: 15px;">
                    <a href="edit_post.php?id=<?php echo $post['post_id']; ?>" class="btn-edit">Edit</a>
                    <button class="btn-delete" onclick="openDeleteModal(<?php echo $post['post_id']; ?>)">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; color:#888;">You haven't posted anything yet.</p>
    <?php endif; ?>
    
</div>

<div id="deleteModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h3>Are you sure?</h3>
        <p>Do you really want to delete this post? This process cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <a id="confirmDeleteBtn" href="#" class="btn-confirm-delete" style="background: #e74c3c; color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none;">Yes, Delete</a>
        </div>
    </div>
</div>

<script src="../assets/js/profile.js">
</script>
<?php include "../utils/exit_button.php" ?>
</body>
</html>