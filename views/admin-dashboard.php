<?php
require_once "../config/connection.php";
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
    header("Location: views/login-form.php");
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

$booking_list_query = "SELECT * FROM booking b
                       JOIN vehicle v ON b.vehicle_id = v.id
                       JOIN users u ON b.user_id = u.id
                       WHERE v.added_by = $user_id
                       ORDER BY start_date DESC";
$booking_list_result = mysqli_query($connection, $booking_list_query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #2f3640;
            padding: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #273c75;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        header a {
            color: #dcdde1;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .stats {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            gap: 40px;
            justify-content: space-around;
            text-align: center;
        }

        .stat-box h3 {
            margin: 0;
            font-size: 16px;
            color: #718093;
        }

        .stat-box p {
            font-size: 24px;
            margin-top: 5px;
            color: #44bd32;
        }

        .card-container {
            display: flex;
            
            flex-direction: row;
   
            flex-wrap: wrap;
           
            gap: 20px;
        }

        .booking-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .booking-card:hover {
            transform: translateY(-5px);
        }

        .booking-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .booking-info {
            font-size: 14px;
            margin-bottom: 6px;
        }

        h3.section-title {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .no-data {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: #999;
        }
      
    </style>
</head>
<body>
    <header>
        <h2>Welcome <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <div>
            <a href='add_vehicle_form.php'>➕ Add New Vehicle</a>
            <a href='all_listings.php'>  View all listings </a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')">🔒 Logout</a>
           
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

    <h3 class="section-title">Booking List</h3>
    <?php if (mysqli_num_rows($booking_list_result) > 0): ?>
    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($booking_list_result)): ?>
        <div class="booking-card">
            <img src="../<?= htmlspecialchars($row['image']) ?>" alt="Car image" width="200px">
            <h4><?= htmlspecialchars($row['company']) ?> <?= htmlspecialchars($row['model']) ?></h4>
            <div class="booking-info"><strong>Type:</strong> <?= htmlspecialchars($row['category']) ?></div>
            <div class="booking-info"><strong>Price:</strong> $<?= htmlspecialchars($row['total_price']) ?></div>
            <div class="booking-info"><strong>Rented By:</strong> <?= htmlspecialchars($row['user_name']) ?></div>
            <div class="booking-info"><strong>Start:</strong> <?= htmlspecialchars($row['start_date']) ?></div>
            <div class="booking-info"><strong>End:</strong> <?= htmlspecialchars($row['end_date']) ?></div>
            <div>
                <?php if ($row['status'] == 'Pending'): ?>
                    <a href="../approve_booking.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to approve this booking?')">Approve</a>
                    <a href="../reject_booking.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to reject this booking?')">Reject</a>
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
