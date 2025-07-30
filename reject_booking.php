<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        echo "<h3>You aren't authorized to see the content of this page.</h3>";
        exit();
    }
    require_once 'config/connection.php';
    if (isset($_GET['id'])) {
        $booking_id = intval($_GET['id']);
        // get the user for notification::
        $query = "SELECT user_id, company, model FROM booking JOIN vehicle ON booking.vehicle_id = vehicle.id WHERE booking.id = $booking_id";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Error fetching booking details: " . mysqli_error($connection));
        }
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("Booking not found.");
        }
        $user_id = $row['user_id'];
        $query = "UPDATE booking SET status = 'Cancelled' WHERE id = $booking_id";
        if (mysqli_query($connection, $query)) {
            // set up notification message:
            $notification_message = "Your booking for " . htmlspecialchars($row['company']) . " " . htmlspecialchars($row['model']) . " has been rejected by the host.";
            // insert notification into the database:
            $notification_query = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$notification_message')";
            if (!mysqli_query($connection, $notification_query)) {
                die("Error inserting notification: " . mysqli_error($connection));
            }

            header("Location: views/admin-dashboard.php");
            exit();
        } else {
            echo "Error approving booking: " . mysqli_error($connection);
        }
    } else {
        echo "Invalid booking ID.";
    }



?>