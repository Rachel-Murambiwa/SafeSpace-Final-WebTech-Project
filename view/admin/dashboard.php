<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../../db/config.php';

if (!isset($_SESSION['user_id'])) {
    // Not logged in? Send to login page
    header("Location: ../login.php");
    exit(); // Stop loading the rest of the page immediately
}

// 2. Is the logged-in user an ADMIN?
// We check the session variable set during login
if (!isset($_SESSION['roles']) || $_SESSION['roles'] !== 'admin') {
    // Logged in but NOT an admin?
    // Kick them back to the regular user dashboard
    header("Location: ../dashboard.php");
    exit();
}



// A. DELETE ACTIONS
if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    $conn->query("DELETE FROM users_safespace WHERE user_id=$id");
    header("Location: dashboard.php?tab=users&msg=User Deleted");
}
if (isset($_GET['delete_post'])) {
    $id = intval($_GET['delete_post']);
    $conn->query("DELETE FROM public_posts_safespace WHERE post_id=$id");
    header("Location: dashboard.php?tab=posts&msg=Post Deleted");
}
if (isset($_GET['delete_comment'])) {
    $id = intval($_GET['delete_comment']);
    $conn->query("DELETE FROM comments_safespace WHERE comment_id=$id");
    header("Location: dashboard.php?tab=moderation&msg=Comment Deleted");
}
if (isset($_GET['unflag_comment'])) {
    $id = intval($_GET['unflag_comment']);
    $conn->query("UPDATE comments_safespace SET is_flagged=0 WHERE comment_id=$id");
    header("Location: dashboard.php?tab=moderation&msg=Flag Dismissed");
}
if (isset($_GET['delete_quote'])) {
    $id = intval($_GET['delete_quote']);
    $conn->query("DELETE FROM quotes_safespace WHERE id=$id");
    header("Location: dashboard.php?tab=quotes&msg=Quote Deleted");
}
if (isset($_GET['delete_resource'])) {
    $id = intval($_GET['delete_resource']);
    $conn->query("DELETE FROM resources_safespace WHERE resource_id=$id");
    header("Location: dashboard.php?tab=resources&msg=Resource Deleted");
}

// B. ADD ACTIONS
// 1. Add User (UPDATED with Country & Email)
if (isset($_POST['add_user'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $country = $_POST['country']; // Added Country
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    $check = $conn->query("SELECT user_id FROM users_safespace WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error_msg = "User with this email already exists.";
    } else {
        // Insert Query now includes 'country'
        $stmt = $conn->prepare("INSERT INTO users_safespace (fname, lname, email, country, password_hash, roles) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $lname, $email, $country, $pwd, $role);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?tab=users&msg=User Added");
        } else {
            $error_msg = "Error adding user.";
        }
    }
}
// 2. Add Quote
if (isset($_POST['add_quote'])) {
    $content = $_POST['content'];
    $author = $_POST['author'];
    $stmt = $conn->prepare("INSERT INTO quotes_safespace (content, author) VALUES (?, ?)");
    $stmt->bind_param("ss", $content, $author);
    $stmt->execute();
    header("Location: dashboard.php?tab=quotes&msg=Quote Added");
}
// 3. Add Category
if (isset($_POST['add_category'])) {
    $cat_name = $_POST['cat_name'];
    $stmt = $conn->prepare("INSERT INTO resource_categories_safespace (category_name) VALUES (?)");
    $stmt->bind_param("s", $cat_name);
    $stmt->execute();
    header("Location: dashboard.php?tab=resources&msg=Category Added");
}
// 4. Add Resource
if (isset($_POST['add_resource'])) {
    $cat_id = $_POST['category_id'];
    $title = $_POST['r_title'];
    $content = $_POST['r_content'];
    $stmt = $conn->prepare("INSERT INTO resources_safespace (category_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $cat_id, $title, $content);
    $stmt->execute();
    header("Location: dashboard.php?tab=resources&msg=Resource Added");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SafeSpace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href = "../../assets/css/admin.css">
</head>
<body>

    <div class="sidebar">
        <h2>🛡️ Admin Panel</h2>
        <a onclick="showTab('users')" class="nav-btn active" id="btn-users"><i class="fas fa-users"></i> Manage Users</a>
        <a onclick="showTab('posts')" class="nav-btn" id="btn-posts"><i class="fas fa-newspaper"></i> All Posts</a>
        <a onclick="showTab('moderation')" class="nav-btn" id="btn-moderation"><i class="fas fa-flag"></i> Flagged Comments</a>
        <a onclick="showTab('quotes')" class="nav-btn" id="btn-quotes"><i class="fas fa-quote-right"></i> Quotes</a>
        <a onclick="showTab('resources')" class="nav-btn" id="btn-resources"><i class="fas fa-book-medical"></i> Resources</a>
        <a href="../../actions/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Dashboard</h1>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['fname'] ?? 'Admin'); ?></strong></span>
        </div>

        <?php if(isset($_GET['msg'])) echo "<div style='background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px;'>".htmlspecialchars($_GET['msg'])."</div>"; ?>
        <?php if(isset($error_msg)) echo "<div style='background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px;'>$error_msg</div>"; ?>

        <div id="users" class="content-section active">
            <div class="card">
                <h3>➕ Add New User</h3>
                <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <input type="text" name="fname" placeholder="First Name" required>
                    <input type="text" name="lname" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <select name="country" id="country" required>
                        <option value="" disabled selected>Country of Origin</option>
                        <option value="Algeria">Algeria</option>
                        <option value="Angola">Angola</option>
                        <option value="Benin">Benin</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cabo Verde">Cabo Verde</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo (Republic)">Congo (Republic)</option>
                        <option value="Congo (Democratic Republic)">Congo (Democratic Republic)</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Egypt">Egypt</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Eswatini">Eswatini</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Mali">Mali</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="South Sudan">South Sudan</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Togo">Togo</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                    </select>

                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role">
                        <option value="general_user">General User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" name="add_user" class="btn btn-blue" style="grid-column: span 3;">Add User</button>
                </form>
            </div>

            <div class="card">
                <h3>All Users</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th> <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT * FROM users_safespace ORDER BY user_id DESC");
                        while($row = $res->fetch_assoc()): 
                            $role = $row['roles'] ?? 'user'; 
                        ?>
                        <tr>
                            <td>#<?php echo $row['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['country']); ?></td> <td><span class="badge <?php echo ($role=='admin')?'badge-admin':'badge-user'; ?>"><?php echo $role; ?></span></td>
                            <td>
                                <a href="?delete_user=<?php echo $row['user_id']; ?>" class="btn btn-red" onclick="return confirm('Delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="posts" class="content-section">
            <div class="card">
                <h3>All Community Posts</h3>
                <table>
                    <thead><tr><th>ID</th><th>User</th><th>Content</th><th>Date</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT p.post_id, p.content, p.created_at, pr.anonymous_username 
                                FROM public_posts_safespace p 
                                LEFT JOIN profiles_safespace pr ON p.profile_id = pr.profile_id 
                                ORDER BY p.created_at DESC";
                        $res = $conn->query($sql);
                        while($row = $res->fetch_assoc()): 
                        ?>
                        <tr>
                            <td>#<?php echo $row['post_id']; ?></td>
                            <td style="color: #00BCD4; font-weight: bold;"><?php echo htmlspecialchars($row['anonymous_username'] ?? 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['content'], 0, 60)); ?>...</td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?delete_post=<?php echo $row['post_id']; ?>" class="btn btn-red" onclick="return confirm('Delete?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="moderation" class="content-section">
            <div class="card">
                <h3>🚩 Flagged Comments</h3>
                <table>
                    <thead><tr><th>Comment</th><th>Posted By</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT c.comment_id, c.content, pr.anonymous_username 
                                FROM comments_safespace c 
                                JOIN profiles_safespace pr ON c.profile_id = pr.profile_id 
                                WHERE c.is_flagged = 1";
                        $res = $conn->query($sql);
                        if ($res->num_rows > 0):
                            while($row = $res->fetch_assoc()): 
                        ?>
                        <tr style="background: #fff5f5;">
                            <td style="color: #c0392b; font-weight:bold;">"<?php echo htmlspecialchars($row['content']); ?>"</td>
                            <td><?php echo htmlspecialchars($row['anonymous_username']); ?></td>
                            <td>
                                <a href="?unflag_comment=<?php echo $row['comment_id']; ?>" class="btn btn-green">Dismiss</a>
                                <a href="?delete_comment=<?php echo $row['comment_id']; ?>" class="btn btn-red">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="3" style="text-align:center;">No flagged comments today! 🎉</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="quotes" class="content-section">
            <div class="card">
                <h3>Add Motivational Quote</h3>
                <form method="POST">
                    <textarea name="content" placeholder="Quote text..." required rows="2"></textarea>
                    <input type="text" name="author" placeholder="Author Name">
                    <button type="submit" name="add_quote" class="btn btn-blue">Add Quote</button>
                </form>
            </div>
            <div class="card">
                <h3>Existing Quotes</h3>
                <table>
                    <thead><tr><th>Quote</th><th>Author</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT * FROM quotes_safespace ORDER BY id DESC");
                        if ($res): while($row = $res->fetch_assoc()): 
                        ?>
                        <tr>
                            <td>"<?php echo htmlspecialchars($row['content']); ?>"</td>
                            <td>- <?php echo htmlspecialchars($row['author']); ?></td>
                            <td><a href="?delete_quote=<?php echo $row['id']; ?>" class="btn btn-red">Delete</a></td>
                        </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="resources" class="content-section">
            <div class="grid-2">
                <div class="card">
                    <h3>Manage Categories</h3>
                    <form method="POST">
                        <input type="text" name="cat_name" placeholder="New Category Name" required>
                        <button type="submit" name="add_category" class="btn btn-blue">Add Category</button>
                    </form>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <?php
                        $cats = $conn->query("SELECT * FROM resource_categories_safespace");
                        while($c = $cats->fetch_assoc()) echo "<li>" . htmlspecialchars($c['category_name']) . "</li>";
                        ?>
                    </ul>
                </div>
                <div class="card">
                    <h3>Add New Resource</h3>
                    <form method="POST">
                        <input type="text" name="r_title" required placeholder="Resource Title">
                        <select name="category_id" required>
                            <option value="">Select Category...</option>
                            <?php
                            $cats->data_seek(0);
                            while($c = $cats->fetch_assoc()) echo "<option value='".$c['category_id']."'>".$c['category_name']."</option>";
                            ?>
                        </select>
                        <textarea name="r_content" rows="3" required placeholder="Phone/Link"></textarea>
                        <button type="submit" name="add_resource" class="btn btn-blue">Add Resource</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <h3>Active Resources</h3>
                <table>
                    <thead><tr><th>Title</th><th>Category</th><th>Content</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php
                        $sql = "SELECT r.resource_id, r.title, r.content, c.category_name 
                                FROM resources_safespace r 
                                LEFT JOIN resource_categories_safespace c ON r.category_id = c.category_id 
                                ORDER BY r.resource_id DESC";
                        $res = $conn->query($sql);
                        while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><span class="badge badge-admin"><?php echo htmlspecialchars($row['category_name'] ?? 'None'); ?></span></td>
                            <td><?php echo htmlspecialchars(substr($row['content'], 0, 30)); ?>...</td>
                            <td><a href="?delete_resource=<?php echo $row['resource_id']; ?>" class="btn btn-red" onclick="return confirm('Delete?');">Delete</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'users';
        showTab(activeTab);

        function showTab(tabId) {
            document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            const targetSection = document.getElementById(tabId);
            const targetBtn = document.getElementById('btn-' + tabId);
            if(targetSection) targetSection.classList.add('active');
            if(targetBtn) targetBtn.classList.add('active');
        }
    </script>
</body>
</html>