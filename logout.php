<?php
    require_once 'config/connection.php';
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }

    $_SESSION['is_logged_in'] = 0;
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['role']);
    session_destroy();
    header("Location: views/login-form.php?message=Logged out successfully.");
    exit();

?>