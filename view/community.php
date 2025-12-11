<?php
session_start();
require '../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// 1. Fetch Posts Joined with Profile Info
$sql = "SELECT p.post_id, p.content, p.created_at, pr.anonymous_username, pr.avatar_color 
        FROM public_posts_safespace p
        JOIN profiles_safespace pr ON p.profile_id = pr.profile_id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <h1 class="logo">SafeSpace 💜</h1>
            <div class="nav-links">
                <a href="profile.php" style="color: #00BCD4;">My Profile</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="../actions/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container feed-container">
        
        <div class="create-post-card">
                    <form action="../actions/add_post.php" method="POST" enctype="multipart/form-data">
            <div class="input-row">
                <div class="avatar-small" style="background: <?php echo $_SESSION['avatar_color'] ?? '#ccc'; ?>">
                    <?php echo strtoupper(substr($_SESSION['anonymous_username'] ?? 'U', 0, 1)); ?>
                </div>
                <textarea name="content" placeholder="Share your story or thoughts..."></textarea>
            </div>

            <div id="preview-container" class="preview-grid"></div>
            
            <div class="actions-row">
                <label for="file-upload" class="custom-file-upload">
                    <i class="fa fa-image"></i> Add Photo
                </label>
                
                <input id="file-upload" type="file" name="post_images[]" multiple accept="image/*" style="display:none;" onchange="handleFiles(this)">
                
                <button type="submit" class="btn-post">Post</button>
            </div>
        </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                
                <div class="post-card">
                    <div class="post-header">
                        <div class="avatar-small" style="background: <?php echo $row['avatar_color']; ?>">
                            <?php echo strtoupper(substr($row['anonymous_username'], 0, 1)); ?>
                        </div>
                        <div class="post-info">
                            <span class="username"><?php echo htmlspecialchars($row['anonymous_username']); ?></span>
                            <span class="time"><?php echo date("M j, g:i a", strtotime($row['created_at'])); ?></span>
                        </div>
                    </div>

                    <p class="post-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

                    <?php
                    $post_id = $row['post_id'];
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

                    <?php
                    // 1. Get Like Count
                    $post_id = $row['post_id'];
                    $sql_likes = "SELECT COUNT(*) as count FROM reactions_safespace WHERE post_id = $post_id";
                    $res_likes = $conn->query($sql_likes);
                    $row_likes = $res_likes->fetch_assoc();
                    $like_count = $row_likes['count'];

                    // 2. Check if CURRENT user liked this
                    // We need the current user's profile_id first (fetched once at top of page ideally, but here works)
                    $curr_user_id = $_SESSION['user_id'];
                    $sql_check_like = "SELECT reaction_id FROM reactions_safespace 
                                    WHERE post_id = $post_id 
                                    AND profile_id = (SELECT profile_id FROM profiles_safespace WHERE user_id = $curr_user_id)";
                    $res_check = $conn->query($sql_check_like);
                    $user_liked = ($res_check->num_rows > 0);
                    ?>

                    <div class="post-actions">
                        
                        <form action="../actions/react_post.php" method="POST" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                            
                            <button type="submit" class="action-btn <?php echo $user_liked ? 'liked-active' : ''; ?>">
                                <i class="<?php echo $user_liked ? 'fas' : 'far'; ?> fa-heart"></i> 
                                <?php echo ($like_count > 0) ? $like_count : 'Like'; ?>
                            </button>
                        </form>
                        
                        <button class="action-btn" onclick="toggleComments(<?php echo $row['post_id']; ?>)">
                            <i class="far fa-comment"></i> Comment
                        </button>
                    </div>

                    <div id="comments-<?php echo $row['post_id']; ?>" class="comment-section" style="display:none;">
    
    <?php
    $current_post_id = $row['post_id'];
    $sql_comments = "SELECT c.content, c.created_at, pr.anonymous_username, pr.avatar_color 
                     FROM comments_safespace c
                     JOIN profiles_safespace pr ON c.profile_id = pr.profile_id
                     WHERE c.post_id = $current_post_id
                     ORDER BY c.created_at ASC";
    $result_comments = $conn->query($sql_comments);
    ?>

    <div class="comments-list">
        <?php if ($result_comments->num_rows > 0): ?>
            <?php while($comment = $result_comments->fetch_assoc()): ?>
               <div class="single-comment">
                <span class="comment-avatar" style="background: <?php echo $comment['avatar_color']; ?>">
                    <?php echo strtoupper(substr($comment['anonymous_username'], 0, 1)); ?>
                </span>
                
                <div class="comment-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; min-width: 200px;">
                        <span class="comment-user"><?php echo htmlspecialchars($comment['anonymous_username']); ?></span>
                        
                        <a href="../actions/flag_comment.php echo $comment['comment_id']; ?>" 
                        class="btn-flag" 
                        title="Report this comment"
                        onclick="return confirm('Are you sure you want to report this comment?');">
                        <i class="far fa-flag"></i>
                        </a>
                    </div>

                    <span class="comment-text"><?php echo htmlspecialchars($comment['content']); ?></span>
                </div>
</div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="font-size: 0.8rem; color: #888; font-style: italic;">No comments yet. Be the first!</p>
        <?php endif; ?>
    </div>

    <form action="../actions/add_comment.php" method="POST" style="margin-top: 15px; display: flex;">
        <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
        <input type="text" name="comment_text" placeholder="Write a supportive comment..." required style="flex: 1;">
        <button type="submit" class="btn-tiny">Send</button>
    </form>
</div>

                </div> <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#777;">No stories yet. Be the first to share! 🌸</p>
        <?php endif; ?>

    </div>

    <script src="../assets/js/community.js"></script>
<?php include "../utils/exit_button.php" ?>
</body>
</html>