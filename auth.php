<?php
include('db.php');
session_start();

// PHPMailer Classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$action = $_POST['action'] ?? '';

// --- LOGIN LOGIC ---
if ($action == 'login') {
    $email = $conn->real_escape_string($_POST['identity']);
    $pass = $_POST['password'];

    // 1. ADMIN CHECK
    if ($email === 'speed@admin.com' && $pass === 'developer') {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = "Speed Admin";
        $_SESSION['role'] = 'admin';
        echo "admin/index.php"; // Return path for JS redirect
        exit();
    }

    // 2. USER CHECK
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($user = $res->fetch_assoc()) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = 'user';
            echo "dashboard.php"; // Return path for JS redirect
        } else {
            echo "error_password";
        }
    } else {
        echo "error_user_not_found";
    }
    exit();
}

// --- REGISTRATION LOGIC ---
if ($action == 'register') {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($pass !== $confirm) { die("Passwords do not match!"); }

    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$hashed', 'user')";

    if ($conn->query($sql)) {
        header("Location: index.php?registered=success");
    } else {
        echo "Error: " . $conn->error;
    }
    exit();
}

// --- FORGOT PASSWORD (SEND EMAIL) ---
if ($action == 'forgot') {
    $email = $conn->real_escape_string($_POST['email']);
    $token = bin2hex(random_bytes(16));
    
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE users SET reset_token='$token' WHERE email='$email'");
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shadowkenya254@gmail.com'; 
            $mail->Password   = 'kkqzjihxpddrcunp';    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('shadowkenya254@gmail.com', 'Speed Projects');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $link = "http://localhost/PM/register/reset_password.php?token=$token";
            $mail->Body    = "Hello, <br>Click the link below to reset your password:<br><br><a href='$link'>$link</a>";

            $mail->send();
            echo "Reset link sent"; // Key response for JS
        } catch (Exception $e) {
            echo "Mailer Error";
        }
    } else {
        echo "Email not found";
    }
    exit();
}

// --- FINAL PASSWORD RESET ---
if ($action == 'final_reset') {
    $token = $conn->real_escape_string($_POST['token']);
    $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $res = $conn->query("UPDATE users SET password='$new_pass', reset_token=NULL WHERE reset_token='$token'");
    if ($conn->affected_rows > 0) {
        echo "success"; // Key response for JS
    } else {
        echo "invalid_token";
    }
    exit();
}
?>