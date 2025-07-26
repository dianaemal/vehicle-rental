<?php
// This file is used to edit vehicle details in the admin dashboard
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    require_once 'config/connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
        isset($_POST['total_price']) && isset($_POST['start_date'])
         && isset($_POST['end_date']) && isset($_POST['vehicle_id'])
          && isset($_POST['user_id'])) {
        $total_price = $_POST['total_price'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $vehicle_id = intval($_POST['vehicle_id']);
        $user_id = intval($_POST['user_id']);

        // Insert booking into the database
        $query = "INSERT INTO booking (vehicle_id, user_id, start_date, end_date, total_price) 
                  VALUES ($vehicle_id, $user_id, '$start_date', '$end_date', $total_price)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Error processing booking: " . mysqli_error($connection));
        } else {
            echo "Booking successful!";
            // Optionally, redirect to a confirmation page or display a success message
            header("Location: views/customer-dashboard.php");
            exit();
        }
    
       
    }
    
?>