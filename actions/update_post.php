<?php
session_start();
require '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../view/login.php");
        exit();
    }

    $post_id = intval($_POST['post_id']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (empty($content)) {
        header("Location: ../view/edit_post.php?id=$post_id&error=empty");
        exit();
    }

    // Update Query (With ownership check for safety)
    $sql = "UPDATE public_posts_safespace p
            JOIN profiles_safespace pr ON p.profile_id = pr.profile_id
            SET p.content = ?, p.updated_at = NOW()
            WHERE p.post_id = ? AND pr.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $content, $post_id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../view/profile.php?msg=updated");
    } else {
        echo "Error updating post.";
    }
}
?>