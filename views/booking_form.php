<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    require_once '../config/connection.php';
    $user_id = $_SESSION['user_id'];
    $vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : null;
    if (!$vehicle_id) {
        die("Vehicle ID is required.");
    }
    $query = "SELECT * FROM vehicle WHERE id = $vehicle_id";
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
    $booking_query = "SELECT * FROM booking WHERE vehicle_id = $vehicle_id";
    $booking_result = mysqli_query($connection, $booking_query);
   if (!$booking_result) {
      die("Error fetching booking details: " . mysqli_error($connection));
   }   

    $dates = [];
    if (mysqli_num_rows($booking_result) > 0) {
        while ($row = mysqli_fetch_assoc($booking_result)) {
            $dates[] = ['from' => $row['start_date'], 'to' => $row['end_date']];
            // Assuming you want to store both start and end dates for disabling in the date picker
            // If you only need start dates, you can change this to:
            // $start_dates[] = $row['start_date'];
            // If you need to store end dates as well, you can do:
        }
    } else {
        $dates = []; // No bookings found
    }
   //while ($row = mysqli_fetch_assoc($booking_result)) {
     //  $start_dates[] = $row['start_date'];
  // }




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Form</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
     <!-- Flatpickr JS -->
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        header div {
            margin-top: 10px;
        }
        header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        img {
            display: block;
            margin: 20px auto;
        }
        form {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input[type="date"],
        form input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
        }
        form button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #333;
            color: white;
        }
        footer p {
            margin: 0;
        }
        h2, h3 {
            text-align: center;
        }
        .host-info {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;  
            border-radius: 5px;
        }
        .host-info p {
            margin: 5px 0;
        }
        .flatpickr-day.disabled {
            position: relative;
            color: #ccc !important;
            cursor: not-allowed;
        }

        .flatpickr-day.disabled::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 15%;
            width: 70%;
            height: 2px;
            background-color: red;
            transform: rotate(-20deg);
            pointer-events: none;
        }
    </style>
    
</head>
<body>
    <header>
        <h1>Enter the information for your bookings:</h1>
        <div>
            <a href='customer-dashboard.php'>Go back to dashboard</a>
            <a href='views/logout.php' onclick="return confirm('Are you sure you want to logout?')">🔒 Logout</a>
        </div>
    </header>
    <img src="../<?php echo $vehicle['image']; ?>" alt="Vehicle Image" width="600" height="400">
    <h2><?php echo htmlspecialchars($vehicle['company']) . ' ' . htmlspecialchars($vehicle['model']); ?></h2>
    <p>Year: <?php echo htmlspecialchars($vehicle['year']); ?></p>
    <p>Passengers: <?php echo htmlspecialchars($vehicle['passengers']); ?></p>
    <p>Category: <?php echo htmlspecialchars($vehicle['category']); ?></p>
    <p>Description: <?php echo htmlspecialchars($vehicle['description']); ?></p>
    <p>Price per day: $<?php echo htmlspecialchars($vehicle['price_per_day']); ?></p>
    <div>
        <h3>Host Information</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($host_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($host_email); ?></p>
        <p><strong>Total Rented Cars:</strong> <?php echo $total_rented; ?></p>
    </div>
    <h3>Booking Form</h3>
    <form action="../process_booking.php?id=<?php echo $vehicle_id; ?>" method="post">
        <label for="start_date">From:</label>
        <input type="text" id="start_date" name="start_date" placeholder="Select a date" required>
        <label for="end_date">To:</label>
        <input type="text" id="end_date" name="end_date" placeholder="Select a date" required>
        <label for="pickup_location">Pickup Location:</label>
        <input type="text" id="pickup_location" name="pickup_location" required>    
        <label for="dropoff_location">Dropoff Location:</label>
        <input type="text" id="dropoff_location" name="dropoff_location" required>
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" id="total_price_input" name="total_price" value="0.00">
     
       

        
        <button type="submit">Book Now</button>
    </form>
    <script>
        const bookedRanges = <?php echo json_encode($dates); ?>;
    </script>
  
    <span> Total Price: $<span id="total_price">0.00</span></span>
   
    <script>
        const pricePerDay = <?php echo $vehicle['price_per_day']; ?>;
    </script>
    <script src="../js/datePicker.js"></script>
    <footer>
        <p>&copy; 2023 Vehicle Rental Service</p>
    </footer>
</body>
</html>
<?php
// Close the database connection