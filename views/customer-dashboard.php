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

    // Fetch notifications for the logged-in user
    $user_id = $_SESSION['user_id'];
    $notification_query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
    $notification_result = mysqli_query($connection, $notification_query);
    if (!$notification_result) {
        die("Error fetching notifications: " . mysqli_error($connection));
    }   
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>   
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
    <header>
        <h1>Customer Dashboard</h1>
        

        <div>
            
            <a href='all_listings.php'>View Your bookings</a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')">🔒 Logout</a>
            <<!-- Add Bootstrap Icons (in <head>) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="dropdown" style="display: inline-block; position: relative;">
  <button class="btn btn-light position-relative dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-bell"></i>
    <?php if (mysqli_num_rows($notification_result) > 0): ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo mysqli_num_rows($notification_result); ?>
      </span>
    <?php endif; ?>
  </button>

  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" style="width: 300px; max-height: 300px; overflow-y: auto;">
    <?php if (mysqli_num_rows($notification_result) > 0): ?>
      <?php while ($notification = mysqli_fetch_assoc($notification_result)): ?>
        <li>
          <div class="dropdown-item text-wrap small">
          <span class="me-2 rounded-circle bg-primary" style="width: 8px; height: 8px; display: inline-block;"></span>
            <?php echo htmlspecialchars($notification['message']); ?>
            <div class="text-muted small"><?php echo date("M d, Y H:i", strtotime($notification['created_at'])); ?></div>
          </div>
        </li>
      <?php endwhile; ?>
    <?php else: ?>
      <li><div class="dropdown-item text-muted">No notifications</div></li>
    <?php endif; ?>
  </ul>
</div>

    
  
  </ul>
</div>

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