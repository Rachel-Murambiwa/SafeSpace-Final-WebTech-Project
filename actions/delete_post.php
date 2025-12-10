<?php
session_start();
require '../db/config.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $check = "SELECT p.post_id FROM public_posts_safespace p 
              JOIN profiles_safespace pr ON p.profile_id = pr.profile_id
              WHERE p.post_id = ? AND pr.user_id = ?";
    
    $stmt = $conn->prepare($check);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $del = $conn->prepare("DELETE FROM public_posts_safespace WHERE post_id = ?");
        $del->bind_param("i", $post_id);
        $del->execute();
    }
}
header("Location: ../view/profile.php");
exit();
?>