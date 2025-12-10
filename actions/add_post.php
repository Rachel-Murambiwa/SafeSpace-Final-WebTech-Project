<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['anonymous_username'])) {
    header("Location: ../view/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Get Profile ID
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT profile_id FROM profiles_safespace WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_id);
    $stmt->fetch();
    $stmt->close();

    if (!$profile_id) die("Error: Profile not found.");

    $content = trim($_POST['content']);
    $title = "Community Post";

    // 2. Insert the Text Post First
    $sql = "INSERT INTO public_posts_safespace (profile_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $profile_id, $title, $content);
    
    if ($stmt->execute()) {
        $post_id = $stmt->insert_id; // Get the ID of the post we just created

        // 3. Handle Multiple Images (Max 10)
        if (isset($_FILES['post_images'])) {
            $target_dir = "../assets/uploads/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

            $total_files = count($_FILES['post_images']['name']);
            
            // Limit to 10
            if ($total_files > 10) $total_files = 10;

            $sql_img = "INSERT INTO post_images_safespace (post_id, image_path) VALUES (?, ?)";
            $stmt_img = $conn->prepare($sql_img);

            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['post_images']['error'][$i] == 0) {
                    $file_name = time() . "_" . $i . "_" . basename($_FILES["post_images"]["name"][$i]);
                    $target_file = $target_dir . $file_name;
                    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    
                    if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                        if (move_uploaded_file($_FILES["post_images"]["tmp_name"][$i], $target_file)) {
                            $stmt_img->bind_param("is", $post_id, $file_name);
                            $stmt_img->execute();
                        }
                    }
                }
            }
        }
        
        header("Location: ../view/community.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>