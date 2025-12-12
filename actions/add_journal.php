<?php
session_start();
require '../db/config.php';

// 1. Security Check: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $mood = $_POST['mood']; // "Happy", "Sad", "Anxious", etc.

    // 2. Validation
    if (empty($title) || empty($content)) {
        echo "<script>alert('Please write something before saving.'); window.history.back();</script>";
        exit();
    }

    // 3. Insert into Database
    $sql = "INSERT INTO journal_entries_safespace (user_id, title, content, mood) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("isss", $user_id, $title, $content, $mood);
        
        if ($stmt->execute()) {
            // Success: Go back to journal page
            header("Location: ../view/journal.php?success=saved");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>