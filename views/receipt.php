<?php
session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
        echo "You aren't authorized to see the content of this page.";
        exit();
    }
    require_once "../config/connection.php";
    $user_id = $_SESSION['user_id'];
    $booking_id = intval($_GET['id']);

    $query = "SELECT 
        b.total_price,
        b.status,
        b.start_date,
        b.end_date,
        v.company,
        v.model,
        v.image,
        v.category,
        u.user_name,
        l.city,
        l.address
    FROM booking b
    JOIN vehicle v ON b.vehicle_id = v.id
    JOIN location l ON v.location_id = l.id
    JOIN users u ON v.added_by = u.id
    WHERE b.id = $booking_id AND b.user_id = $user_id";

    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Receipt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    </header>
    <div class="receipt">
        <h1>Booking Receipt</h1>
    
        <h2><?php echo $row['company'] . ' ' . $row['model']; ?> (<?php echo $row['category']; ?>)</h2>
        <p><strong>Booking Dates:</strong> <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?></p>
        <p><strong>Total Price:</strong> $<?php echo $row['total_price']; ?></p>
        <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
        <p><strong>Location:</strong> <?php echo $row['address']; ?>, <?php echo $row['city']; ?></p>
        <p><strong>Host:</strong> <?php echo $row['user_name']; ?></p>
        <button onclick="window.print()">Print Receipt</button>
    </div>
    <div class="booking-policy">
    <h3>Booking Terms & Policies</h3>
    <ul>
        <li>Pick-up time: <strong>10:00 AM</strong></li>
        <li>Drop-off time: <strong>9:00 AM</strong></li>
        <li>Cancellation made less than <strong>24 hours</strong> before pick-up will not be refunded.</li>
        <li>Changes to booking dates are allowed <strong>only while the reservation is pending</strong>. Once confirmed by the host, changes are not permitted.</li>
        <li>For further assistance, please contact the host at: <strong><?php echo htmlspecialchars($booking['user_email']); ?></strong></li>
    </ul>
</div>
</body>
</html>
