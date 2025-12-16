<?php
session_start();
require '../db/config.php';

// 1. Get the Category ID from the URL (e.g., ?cat_id=1)
if (!isset($_GET['cat_id'])) {
    header("Location: dashboard.php");
    exit();
}

$cat_id = intval($_GET['cat_id']);

// 2. Fetch the Category Name (for the title)
$cat_sql = "SELECT category_name FROM resource_categories_safespace WHERE category_id = ?";
$stmt = $conn->prepare($cat_sql);
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$cat_result = $stmt->get_result();
$category = $cat_result->fetch_assoc();
$page_title = $category ? $category['category_name'] : "Resources";

// 3. Fetch the Resources for this category
$res_sql = "SELECT * FROM resources_safespace WHERE category_id = ?";
$stmt = $conn->prepare($res_sql);
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$resources = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?> - SafeSpace</title>
    <link rel="stylesheet" href="../assets/css/community.css">
    <style>
        .resource-list-container { 
            max-width: 800px; 
            margin: 50px auto; 
            padding: 20px;
         }
        .back-btn { 
            display: inline-block; 
            margin-bottom: 20px; 
            color: #666; 
            text-decoration: none; 
        }
        .res-item { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 15px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            border-left: 5px solid #00BCD4; 
        }
        .res-title { 
            font-size: 1.2rem; 
            font-weight: bold; 
            color: #333; 
            margin-bottom: 5px; 
        }
        .res-content { 
            font-size: 1rem; 
            color: #555; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <h1 class="logo">SafeSpace 💜</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</nav>

<div class="resource-list-container">
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <h1 style="margin-bottom: 30px; color: #2c3e50;">
        Category: <span style="color: #00BCD4;"><?php echo htmlspecialchars($page_title); ?></span>
    </h1>

    <?php if ($resources->num_rows > 0): ?>
        <?php while($row = $resources->fetch_assoc()): ?>
            <div class="res-item">
                <div class="res-title"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="res-content">
                    <?php 
                        $content = htmlspecialchars($row['content']);
                        // Simple check: if it looks like a URL, make it clickable
                        if (filter_var($content, FILTER_VALIDATE_URL)) {
                            echo "<a href='$content' target='_blank'>$content</a>";
                        } else {
                            echo $content;
                        }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="res-item" style="border-left-color: #ccc;">
            <p>No resources found in this category yet.</p>
        </div>
    <?php endif; ?>

</div>

</body>
</html>