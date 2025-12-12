<?php
session_start();
require '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../view/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $anon_name = trim($_POST['anonymous_username']);
    $avatar_color = $_POST['avatar_color'];

    if (empty($anon_name) || empty($avatar_color)) {
        echo "<script>alert('Please choose a username and color.'); window.history.back();</script>";
        exit();
    }

    $check = $conn->prepare("SELECT profile_id FROM profiles_safespace WHERE anonymous_username = ?");
    $check->bind_param("s", $anon_name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('That anonymous name is already taken! Be creative.'); window.history.back();</script>";
        exit();
    }
    $check->close();

    $sql = "INSERT INTO profiles_safespace (user_id, anonymous_username, avatar_color) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iss", $user_id, $anon_name, $avatar_color);
        
        if ($stmt->execute()) {
            $_SESSION['anonymous_username'] = $anon_name;
            $_SESSION['avatar_color'] = $avatar_color;
            header("Location: ../view/dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>