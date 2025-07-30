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
    require_once "../config/connection.php";
    $booking_id = $_GET['id'];
    $query = "SELECT b.id as booking_id, v.id as vehicle_id, b.start_date, b.end_date, v.price_per_day, b.total_price FROM booking b  JOIN vehicle v ON b.vehicle_id = v.id WHERE b.id='$booking_id'";
    $result = mysqli_query($connection, $query);
    if (!$result){
        die("Error.");
    }
    $booking = mysqli_fetch_assoc($result);
    $user_id = $_SESSION['user_id'];


    // Query for booking info:
    $vehicle_id = $booking['vehicle_id'];
    $booking_query = "SELECT * FROM booking WHERE vehicle_id = '$vehicle_id' AND status != 'Cancelled' AND id != '$booking_id'";
        
    $booking_result = mysqli_query($connection, $booking_query);
    if (!$booking_result) {
        die("Error fetching booking details: " . mysqli_error($connection));
    }   
    $dates = [];
    if (mysqli_num_rows($booking_result) > 0) {
        while ($row = mysqli_fetch_assoc($booking_result)) {
            // Ensure dates are valid before adding to array
            if (!empty($row['start_date']) && !empty($row['end_date'])) {
                $dates[] = ['from' => $row['start_date'], 'to' => $row['end_date']];
            }
        }
    } else {
        $dates = []; // No bookings found
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking Form</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/booking.css">
    <style>
        .right{
            margin: auto;
        }
        
    </style>
         
</head>
<body>
    <header>
        <h2>Edit the dates of your bookings:</h2>
        <div>
            <a href='customer-dashboard.php'>Go back to dashboard</a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')"> Logout</a>
        </div>
    </header>
   
  
    <div class="main-container">
    <div class="right" >
        <h2>Booking Form</h2>
        <form action="../edit_booking.php?id=<?php echo $booking['booking_id']; ?>" method="post" class="booking-form">
              
            <label for="start_date">From:</label>
            <input type="text" id="start_date" name="start_date" placeholder="Select a date" value="<?php echo $booking['start_date']?>" required>
            <label for="end_date">To:</label>
            <input type="text" id="end_date" name="end_date" placeholder="Select a date" value="<?php echo $booking['end_date'];?>"  required>
            <input type="hidden" name="vehicle_id" value="<?php echo $booking['vehicle_id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" id="total_price_input" name="total_price" value="<?php echo $booking['total_price'];?>"> 
            <script>
                const bookedRanges = <?php echo json_encode($dates); ?>;
                const pricePerDay = <?php echo $booking['price_per_day']; ?>;
            </script>
        
            <label> Total Price:</label>
            <div> $<span id="total_price"><?php echo $booking['total_price'];?></span></div>
            <button type="submit"> Edit</button>
        </form>
       
    </div>
    </div>
    <?php include "../includes/footer.php";?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../js/datePicker.js"></script>
    
</body>
  
</html>
<?php
