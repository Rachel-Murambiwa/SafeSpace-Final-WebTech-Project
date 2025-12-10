<?php
session_start();
require '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $country = $_POST['country'];

    $password = $_POST['password_hash']; 
    $confirm_password = $_POST['confirm_password'];

    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($country)) {
        echo "<script>alert('Error: All fields are required.'); window.history.back();</script>";
        exit();
    }
    $email_pattern = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,}$/";
    
    if (!preg_match($email_pattern, $email)) {
        echo "<script>alert('Error: Invalid email format.'); window.history.back();</script>";
        exit();
    }
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($password_pattern, $password)) {
        echo "<script>
            alert('Error: Password is too weak.\\nIt must contain:\\n- At least 8 characters\\n- One uppercase letter\\n- One lowercase letter\\n- One number\\n- One special character (!@#$%^&*)'); 
            window.history.back();
        </script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Error: Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    $checkEmail = $conn->prepare("SELECT user_id FROM users_safespace WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Error: This email is already registered. Please Log In.'); window.location.href='../view/login.html';</script>";
        exit();
    }
    $checkEmail->close();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users_safespace (fname, lname, email, password_hash, country) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssss", $fname, $lname, $email, $hashed_password, $country);

       if ($stmt->execute()) {
            header("Location: ../view/login.php?success=true");
            exit(); 
        } else {
            header("Location: ../view/register.php?success=false");
        }
        }
    $conn->close();

} else {
    header("Location: ../view/register.php");
    exit();
}
?>
