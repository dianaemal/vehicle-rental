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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
        isset($_POST['total_price']) && isset($_POST['start_date'])
         && isset($_POST['end_date']))
           {
        $total_price = $_POST['total_price'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
      

        // Insert booking into the database
        $query = "UPDATE booking SET  start_date = '$start_date', end_date = '$end_date', total_price = $total_price WHERE id=$booking_id ";
        
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Error processing booking: " . mysqli_error($connection);
            exit();
        }
        else{
            header('Location: views/all_booking.php');
        }
    }
  
   

    

?>