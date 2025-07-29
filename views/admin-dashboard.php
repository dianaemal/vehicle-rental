<?php
require_once "../config/connection.php";
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
    header("Location: views/login-form.php");
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<h3>You aren't authorized to see the content of this page.</h3>";
    exit();
}

$query = "SELECT * FROM vehicle ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);

$month = date('m');
$year = date('Y');

$user_id = $_SESSION['user_id'];
$booking = "SELECT COUNT(CASE WHEN b.status = 'Confirmed' THEN 1 END) AS total_bookings,
            SUM(CASE WHEN b.status = 'Confirmed' THEN b.total_price ELSE 0 END) AS total_profit,
            COUNT(CASE WHEN b.status = 'Pending' THEN 1 END) AS total_pending
            FROM booking b
            JOIN vehicle v ON b.vehicle_id = v.id
            WHERE v.added_by = $user_id
              AND MONTH(b.start_date) = $month
              AND YEAR(b.start_date) = $year";

$booking_result = mysqli_query($connection, $booking);

$booking_list_query = "SELECT 
        b.id AS booking_id,
        b.total_price,
        b.status,
        b.start_date,
        b.end_date,
        v.id AS vehicle_id,
        v.company,
        v.model,
        v.image,
        v.category,
        u.id AS user_id,
        u.user_name AS user_name,
        l.city,
        l.address
    FROM booking b
    JOIN vehicle v ON b.vehicle_id = v.id
    JOIN location l ON v.location_id = l.id
    JOIN users u ON b.user_id = u.id
    WHERE v.added_by = $user_id
    ORDER BY b.start_date DESC";
$booking_list_result = mysqli_query($connection, $booking_list_query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}
$notification_query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
$notification_result = mysqli_query($connection, $notification_query);  
if (!$notification_result) {
    die('Query failed: ' . mysqli_error($connection));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/dashboard.css">


   
</head>
<body>
    <header>
        <h2>Welcome <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
       
       
            
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
       
        <div>
          
            <a href='add_vehicle_form.php'> Add New Vehicle</a>
      
            <a href='all_listings.php'>  View all listings </a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')"> Logout</a>
        </div>

    </header>

    <?php if ($booking_result && $row = mysqli_fetch_assoc($booking_result)): ?>
    <div class="stats">
        <div class="stat-box">
            <h3>Total Confirmed Bookings This Month</h3>
            <p><?= $row['total_bookings'] ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Pending Bookings This Month</h3>
            <p><?= $row['total_pending']?></p>
        </div>
        <div class="stat-box">
            <h3>Total Revenue This Month</h3>
            <p>$<?= number_format($row['total_profit'], 2) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <h3 class="section-title">Booking History</h3><span>Click on the booking to view the details.</span>
    <?php if (mysqli_num_rows($booking_list_result) > 0): ?>
    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($booking_list_result)): ?>
        <div class="booking-card" onclick="window.location.href='booking_details.php?id=<?= $row['booking_id'] ?>'" style="cursor: pointer;">
         
            <h4><?= htmlspecialchars($row['company']) ?> <?= htmlspecialchars($row['model']) ?></h4>
            <div class="booking-info"><strong>Type:</strong> <?= htmlspecialchars($row['category']) ?></div>
            <div class="booking-info"><strong>Price:</strong> $<?= htmlspecialchars($row['total_price']) ?></div>
            <div class="booking-info"><strong>Rented By:</strong> <?= htmlspecialchars($row['user_name']) ?></div>
            <div class="booking-info"><strong>Start:</strong> <?= htmlspecialchars($row['start_date']) ?></div>
            <div class="booking-info"><strong>End:</strong> <?= htmlspecialchars($row['end_date']) ?></div>
            <div class="booking-info"><strong>Pick up location: </strong><?= htmlspecialchars($row['address']) ?>, <?= htmlspecialchars($row['city']) ?>, BC</div>
            <div class="booking-info"><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></div>
            
            <div>
                <?php if ($row['status'] == 'Pending'): ?>
                    <div class="button-container">
                        
                    <div class="approve-button">
                        <a href="../approve_booking.php?id=<?= $row['booking_id'] ?>" onclick="return confirm('Are you sure you want to approve this booking?')"> Approve ✅ </a>
                    </div>
                    <div class="reject-button" >
                        <a href="../reject_booking.php?id=<?= $row['booking_id'] ?>" onclick="return confirm('Are you sure you want to reject this booking?')">Reject ❌</a>
                        
                    </div>
                    </div>
                <?php elseif ($row['status'] == 'Confirmed'): ?>
                    <span style="color: green;">Approved</span>

                <?php elseif ($row['status'] == 'Cancelled'): ?>
                    <span style="color: red;">Rejected</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="no-data">
        <p>No bookings found.</p>
    </div>
    <?php endif; ?>
</body>
</html>
