<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        echo "You aren't authorized to see the content of this page.";
        exit();
    }
    require_once "../config/connection.php";
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $booking_id = intval($_GET['id']);

    $query = "SELECT 
        b.id AS booking_id,
        b.total_price,
        b.status,
        b.start_date,
        b.end_date,
        b.created_at AS booking_created,
        v.company,
        v.model,
        v.image,
        v.category,
        u.user_name,
        u.email,
        u.created_at AS user_created,
        l.city,
        l.address
    FROM booking b
    JOIN vehicle v ON b.vehicle_id = v.id
    JOIN location l ON v.location_id = l.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = $booking_id AND v.added_by = $user_id";

    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Receipt</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/receipt.css">
    
       


</head>
<body>
    <header>
    <h2>Booking Details</h2>
    <div>  
        <a href='admin-dashboard.php'>Back to dashboard </a>
        <a href='../logout.php' onClick ="return confirm('Are you sure you want to logout?')"> Logout</a>  
    </div>

    </header>
    <div>
    <div class="receipt">
        <h1>Booking Details</h1>
        <img src="../<?php echo $row['image']; ?>" width="700" style=" border-radius: 10px;">
        <h2><?php echo $row['company'] . ' ' . $row['model']; ?> (<?php echo $row['category']; ?>)</h2>
        <p><strong>Booking ID:</strong> <?php echo $row['booking_id']; ?></p>
        <p><strong>Booking Made On: </strong><?php echo $row['booking_created']; ?></p>
        <p><strong>Booking Dates:</strong> <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?></p>
        <p><strong>Total Price:</strong> $<?php echo $row['total_price']; ?></p>
        <p><strong>Location:</strong> <?php echo $row['address']; ?>, <?php echo $row['city']; ?></p>
        <p><strong>Status:</strong> <?php echo $row['status']; ?></p>

        <h2>Renter Information</h2>
        <p><strong>Name:</strong> <?php echo $row['user_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
        <p><strong>Member since: </strong><?php echo date("Y-m-d",strtotime($row['user_created'])); ?></p>
       
       
  
    

</div>
<?php include "../includes/footer.php";?>
</body>
</html>
