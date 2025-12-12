<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/login.css">
  <title>Login - SafeSpace</title>
</head>
<body class="auth-body">

    <div class="auth-container">
        
        <div class="auth-visual login-visual" style="background-image: url('../assets/images/heal2.jpeg');">
            </div>

        <div class="auth-form-wrapper">
            <div class="auth-header">
                <h1 class="logo">SafeSpace<span class="dot">.</span></h1>
                <h3>Log in to your sanctuary</h3>
            </div>

            <form action="../actions/login_user.php" method="POST" class="standard-form">
                
                <div class="input-group">
                    <label for="email">Email or Pseudonym</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <p id="login-message" style="color: red; text-align: center; font-size: 0.9rem; margin-bottom: 10px;"></p>
                <button type="submit" class="btn-primary login-btn">Enter SafeSpace</button>
                
                <p class="auth-footer">
                    New here? <a href="register.php">Create an anonymous account</a> <br>
                </p>
            </form>
        </div>
    </div>
<script src="../assets/js/login.js"></script>
<?php include "../utils/exit_button.php" ?>
</body>
</html>