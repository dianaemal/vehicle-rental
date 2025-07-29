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
    require_once "../config/connection.php";
    $user_id = $_SESSION['user_id'];

    $query = "SELECT 
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
    JOIN users u ON v.added_by = u.id
    WHERE b.user_id = $user_id
    ORDER BY b.start_date ASC";


    $result = mysqli_query($connection, $query);

    $today = date('Y-m-d');
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
    .container {
        display: flex;
        flex-direction: column;
        gap: 30px;
        padding: 20px;
        align-items: center;
}

.card {
    display: flex;
    flex-direction: row;
    gap: 30px;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: #fff;
    align-items: center;
    width: 70%;
}

.card img {
    width: 400px;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
}

.card .info {
    flex: 1;
}

.card h3 {
    margin: 0 0 10px;
}

.card p {
    margin: 4px 0;
    color: #333;
}

.card a.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.card a.btn:hover {
    background-color: #0056b3;
}
.image-container {
    position: relative;
}

.days-left {
    position: absolute;
    top: 10px;
    left: 10px;
    background: white;
    color: black;
    padding: 4px 8px;
    font-size: 14px;
    border-radius: 4px;
    font-weight: bold;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

    </style>
</head>
<body>
<header>
    <h2>My Bookings</h2>
        <div>
            
            <a href='customer-dashboard.php'>   Back to dashboard </a>
            <a href='../logout.php'> Logout</a>
           
        </div>
    </header>
    <div class="container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <?php if ($row['start_date'] >= $today): ?>
        <?php
            $date1 = new DateTime();
            $date2 = new DateTime($row['start_date']);
            $diff = $date1->diff($date2)->days;

        ?>
        <div class="card">
        <div class="image-container">
        <img src="../<?php echo $row['image']; ?>" alt="Vehicle Image" width="200" >
        <div class="days-left"><?php echo $diff; ?> days left</div>
    </div>
            <div class="info">
                <h3><?php echo $row['company'] . ' ' . $row['model']; ?></h3>
                <p>From: <?php echo $row['start_date'] . ' 10:00 AM'; ?> | To: <?php echo $row['end_date'] . ' 9:00 AM'; ?></p>
                <p>Hosted By: <?php echo $row['user_name']; ?> | Status: <?php echo $row['status']; ?></p>
                <p> Pickup/dropoff location: <?php echo ($row['address']) . ', ' . ($row['city']) . ', BC' ?></P>
                <?php if ($row['status'] == "Pending"):?>
                <a href="" > Change dates</a>
                <?php endif; ?>
                <a href="" > Cancel booking </a>
                <a href="receipt.php?id=<?php echo $row['booking_id']; ?>" class="btn">View Receipt</a>

            </div>
        </div>
    <?php endif; ?>
<?php endwhile; ?>
    </div>
</body>
</html>

