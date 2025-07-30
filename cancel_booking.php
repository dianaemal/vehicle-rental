<?php
     session_start();

     if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
         header("location: login-form.php");
         exit();
     }
     if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
         echo "You aren't aithorized to see the content of this page.";
         exit();
    }
    if (!isset($_GET['id'])){
        echo "Booking id is not provided.";
        exit();

    }
  
    require_once 'config/connection.php';
    $booking_id = $_GET['id'];
  
    // change the status of the booking to cancel:
    $query = "UPDATE booking SET status= 'Cancelled' WHERE id= '$booking_id'";
    if (!mysqli_query($connection, $query)) {
        echo "<script> alert('Error in cancellig your booking. Try again!')</script>";
        haeder('location: views/all_booking.php');
        exit();
        
    }
    $user_name = $_SESSION['user_name'];
    // Send notification to host:
    $vehicle_query = "SELECT v.added_by, v.model, v.year, v.company, b.start_date, b.end_date FROM booking b JOIN vehicle v ON b.vehicle_id = v.id WHERE b.id = '$booking_id'";
    $result = mysqli_query($connection, $vehicle_query);
    if ($result){
        $vehicle = mysqli_fetch_assoc($result);
        $user_id = $vehicle["added_by"];
        // set up notification message:
        $notification_message = "$user_name has cancelled their booking for  " . htmlspecialchars($vehicle['company']) . " " . htmlspecialchars($vehicle['model']) . " " . htmlspecialchars($vehicle['year']) . " from " . htmlspecialchars($vehicle['start_date']) . " to " . htmlspecialchars($vehicle['end_date']);
        // insert notification into the database:
        $notification_query = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$notification_message')";
        if (!mysqli_query($connection, $notification_query)) {
            die("Error inserting notification: " . mysqli_error($connection));
        }
        header('Location: views/all_booking.php');
        exit();
    }
   

    

?>