<?php
session_start();
require '../db/config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 2. Basic Validation
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        header("Location: ../view/profile.php?error=empty_fields");
        exit();
    }

    if ($new_pass !== $confirm_pass) {
        header("Location: ../view/profile.php?error=mismatch");
        exit();
    }

    if (strlen($new_pass) < 6) {
        header("Location: ../view/profile.php?error=short");
        exit();
    }

    // 3. Verify Current Password
    $stmt = $conn->prepare("SELECT password_hash FROM users_safespace WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_pass, $db_hash)) {
        header("Location: ../view/profile.php?error=wrong_current");
        exit();
    }

    // 4. Update to New Password
    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $update = $conn->prepare("UPDATE users_safespace SET password_hash = ? WHERE user_id = ?");
    $update->bind_param("si", $new_hash, $user_id);
    
    if ($update->execute()) {
        header("Location: ../view/profile.php?msg=password_updated");
    } else {
        header("Location: ../view/profile.php?error=db_error");
    }
    $update->close();
}
?>