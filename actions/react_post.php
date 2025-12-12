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

    // 2. Get Profile ID (Reactions are linked to Profiles)
    $stmt = $conn->prepare("SELECT profile_id FROM profiles_safespace WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_id);
    $stmt->fetch();
    $stmt->close();

    if (!$profile_id) {
        // If they somehow don't have a profile, just go back
        header("Location: ../view/community.php");
        exit();
    }

    // 3. CHECK: Did this user already like this post?
    $check = $conn->prepare("SELECT reaction_id FROM reactions_safespace WHERE post_id = ? AND profile_id = ?");
    $check->bind_param("ii", $post_id, $profile_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // --- ALREADY LIKED: REMOVE IT (UNLIKE) ---
        $del = $conn->prepare("DELETE FROM reactions_safespace WHERE post_id = ? AND profile_id = ?");
        $del->bind_param("ii", $post_id, $profile_id);
        $del->execute();
        $del->close();
    } else {
        // --- NOT LIKED: ADD IT (LIKE) ---
        // reaction_type_id = 1 (Heart) based on your previous SQL
        $type = 1; 
        $ins = $conn->prepare("INSERT INTO reactions_safespace (post_id, profile_id, reaction_type_id) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $post_id, $profile_id, $type);
        $ins->execute();
        $ins->close();
    }
    $check->close();

    // 4. Redirect back to keep the flow smooth
    // We use $_SERVER['HTTP_REFERER'] to send them back to exactly where they were (Profile or Community)
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: ../view/community.php");
    }
    exit();
}
?>