<?php
// This file is used to edit vehicle details in the admin dashboard

    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
        echo "<h3>You aren't authorized to see the content of this page.</h3>";
        exit();
    }
    require_once 'config/connection.php';
    
    $user_name = $_SESSION['user_name'] ?? 'Guest';
    
    // get vehicle name:
    if (isset($_POST['vehicle_id'])) {
        $vehicle_id = intval($_POST['vehicle_id']);
        $query = "SELECT * FROM vehicle WHERE id = $vehicle_id";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Error fetching vehicle details: " . mysqli_error($connection);
           exit();
        }
        $vehicle = mysqli_fetch_assoc($result);
   
      
        if (!$vehicle) {
            echo "Vehicle not found.";
            exit();
        }
       
    } else {
        echo "Vehicle ID is required.";
        exit();
    }
    $host = $vehicle['added_by'];

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
            echo "Error processing booking: " . mysqli_error($connection);
            exit();
        }
         else {
            
            // Optionally, redirect to a confirmation page or display a success message
            // set up notification message:
            $notification_message = "$user_name has requested to book " . htmlspecialchars($vehicle['company']) . " " . htmlspecialchars($vehicle['model']) . " " . htmlspecialchars($vehicle['year']) . " from $start_date to $end_date.";
            
            // insert notification into the database:
            $notification_query = "INSERT INTO notifications (user_id, message) VALUES ($host, '$notification_message')";
            if (!mysqli_query($connection, $notification_query)) {
                echo "Error inserting notification: " . mysqli_error($connection);
                exit();
           
            }
            header("Location: views/all_booking.php");
            exit();
        }
    
       
    }
    
?>