<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
   

    require_once '../config/connection.php';
    $query = "SELECT * FROM vehicle";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>   
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Customer Dashboard</h1>
        <div>
            
            <a href='all_listings.php'>View Your bookings</a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')">🔒 Logout</a>

        </div>
    </header>
    <div>
        <h2>Available Vehicles</h2>  
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="vehicle-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="vehicle-card">
                        <img src="../<?php echo $row['image']; ?>" alt="Vehicle Image" width="200">
                        <h3><?php echo htmlspecialchars($row['company']) . ' ' . htmlspecialchars($row['model']); ?></h3>
                        <p>Year: <?php echo htmlspecialchars($row['year']); ?></p>
                        <p>Passengers: <?php echo htmlspecialchars($row['passengers']); ?></p>
                        <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($row['description']); ?></p>
                        <p>Price per day: $<?php echo htmlspecialchars($row['price_per_day']); ?></p>
                        <a href="booking_form.php?id=<?php echo $row['id']; ?>">Book Now</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No vehicles available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>