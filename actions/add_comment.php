<?php
session_start();
require '../db/config.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $content = trim($_POST['comment_text']);

    // 2. Validation
    if (empty($content)) {
        header("Location: ../view/community.php"); 
        exit();
    }

    // 3. Get the user's Profile ID
    // (Comments are linked to Profiles, not just User IDs, so we stay anonymous)
    $stmt = $conn->prepare("SELECT profile_id FROM profiles_safespace WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_id);
    $stmt->fetch();
    $stmt->close();

    if (!$profile_id) {
        echo "<script>alert('You need a profile to comment.'); window.history.back();</script>";
        exit();
    }

    // 4. Insert Comment
    $sql = "INSERT INTO comments_safespace (post_id, profile_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("iis", $post_id, $profile_id, $content);
        $stmt->execute();
        $stmt->close();
    }

    // 5. Redirect back to Community Page
    header("Location: ../view/community.php");
    exit();
}
?>