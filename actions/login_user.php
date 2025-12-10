<?php

session_start();

require '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password']; 

    if (empty($email) || empty($password)) {
        header("Location: ../view/login.html?error=empty");
        exit();
    }

    $sql = "SELECT user_id, fname, lname, password_hash FROM users_safespace WHERE email = ?";
    
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $hashed_password = ""; 
            $stmt->bind_result($user_id, $fname, $lname, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['email'] = $email;
                header("Location: ../view/dashboard.php");
                exit();

            } else {
                header("Location: ../view/login.php?error=wrong_password");
                exit();
            }

        } else {
            header("Location: ../view/login.php?error=not_found");
            exit();
        }

        $stmt->close();
    } 
    else {
        header("Location: ../view/login.html?error=system");
        exit();
    }
    
    $conn->close();

} else {
    header("Location: ../view/login.html");
    exit();
}
?>