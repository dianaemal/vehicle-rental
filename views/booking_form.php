<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
        echo `You aren't aithorized to see the content of this page.`;
        exit();
   }
    require_once '../config/connection.php';
    $user_id = $_SESSION['user_id'];
    $vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : null;
    if (!$vehicle_id) {
        die("Vehicle ID is required.");
    }
    $query = "SELECT 
       *
    FROM vehicle v
    JOIN location l ON v.location_id = l.id
    WHERE v.id = $vehicle_id
    ORDER BY v.created_at DESC";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Error fetching vehicle details: " . mysqli_error($connection));
    }
    $vehicle = mysqli_fetch_assoc($result);
    if (!$vehicle) {
        die("Vehicle not found.");
    }
    $host = $vehicle['added_by'];
    //get the total number of rented cars from the host:
    $rented_query = "SELECT COUNT(*) as total_rented FROM booking WHERE vehicle_id = $vehicle_id ";
    $rented_result = mysqli_query($connection, $rented_query);
    if (!$rented_result) {
        die("Error fetching rented cars: " . mysqli_error($connection));
    }
    $rented_data = mysqli_fetch_assoc($rented_result);
    $total_rented = $rented_data['total_rented'];   
    $host_query = "SELECT * FROM users WHERE id = $host";
    $host_result = mysqli_query($connection, $host_query);
    if (!$host_result) {
        die("Error fetching host details: " . mysqli_error($connection));
    }
    $host_data = mysqli_fetch_assoc($host_result);
    if (!$host_data) {
        die("Host not found.");
    }
    $host_name = $host_data['user_name'];
    $host_email = $host_data['email'];


    // Query for booking info:
    $booking_query = "SELECT * FROM booking WHERE vehicle_id = $vehicle_id AND status != 'Cancelled'";
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
    <title>Booking Form</title>
    <link rel="stylesheet" href="../css/dashboard.css">
     <link rel="stylesheet" href="../css/booking.css">
     <style>
        .right{
            height: 500px;
            max-height: 500px;
        }
        
    </style>
         
</head>
<body>
    <header>
        <h2>Enter the information for your bookings:</h2>
        <div>
            <a href='customer-dashboard.php'>Go back to dashboard</a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')"> Logout</a>
        </div>
    </header>
    <div class="image-div">
    <img src="../<?php echo $vehicle['image']; ?>" alt="Vehicle Image" class="image">
    <div class="overlay"></div>
    <div class="text">
        <h2><?php echo htmlspecialchars($vehicle['company']) . ' ' . htmlspecialchars($vehicle['model']); ?></h2>
        <p>Year: <?php echo htmlspecialchars($vehicle['year']); ?></p>
        <p>Passengers: <?php echo htmlspecialchars($vehicle['passengers']); ?></p>
        <p>Category: <?php echo htmlspecialchars($vehicle['category']); ?></p>
       
        <p>Price per day: $<?php echo htmlspecialchars($vehicle['price_per_day']); ?></p>
    </div>
    </div>
  
    <div class="main-container">
        <div class="left">

            <?php if ($vehicle['description']): ?>
            <div class="vehicle-description">
                <h2>Description</h2>
                <p><?php echo $vehicle['description']; ?></p>
            </div>
            <?php endif;?>

            <!-- Host Info Section -->
            <div class="host">
            <h2 style="margin-bottom: 40px;">Host Information</h2>
            <div class="host-info">
                <img src="../images/profile.png" alt="Profile Image" class="profile">
                <div class="host-details">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($host_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($host_email); ?></p>
                    <p><strong>Total Rented Cars:</strong> <?php echo $total_rented; ?></p>
                </div>
            </div>
            </div>
        </div>
       

    <div class="right">
        <h2>Booking Form</h2>
        <form action="../process_booking.php?id=<?php echo $vehicle_id; ?>" method="post" class="booking-form">
              
            <label for="start_date">From:</label>
            <input type="text" id="start_date" name="start_date" placeholder="Select a date" required>
            <label for="end_date">To:</label>
            <input type="text" id="end_date" name="end_date" placeholder="Select a date" required>
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="pickup-info"> 
                <label> Pickup/Dropoff Location:</label>
                <P> <?php echo htmlspecialchars($vehicle['address']) . ', ' . htmlspecialchars($vehicle['city']) . ', BC' ?></P>
            </div>
            <input type="hidden" id="total_price_input" name="total_price" value="0.00"> 
            <script>
                const bookedRanges = <?php echo json_encode($dates); ?>;
                const pricePerDay = <?php echo $vehicle['price_per_day']; ?>;
            </script>
        
            <label> Total Price:</label>
            <div> $<span id="total_price">0.00</span></div>
            <button type="submit">Book Now</button>
        </form>
       
    </div>
    </div>
    
   
    <?php include "../includes/footer.php";?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="path/to/your/custom-datepicker.js" defer></script>
    <script src="../js/datePicker.js"></script>
</body>

</html>

