<?php
session_start();
require '../db/config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

// 2. Handle Flag Request
if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);
    
    // We update the table you showed me
    $stmt = $conn->prepare("UPDATE comments_safespace SET is_flagged = 1 WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);
    
    if ($stmt->execute()) {
        // Optional: Set a session message to show "Comment Reported"
        $_SESSION['msg'] = "Comment has been reported to admins.";
    }
    
    $stmt->close();
}

// 3. Redirect back to where they came from
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: ../view/community.php");
}
exit();
?>