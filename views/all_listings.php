<?php 
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();}
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
         echo "<h3>You aren't authorized to see the content of this page.</h3>";
        exit();
    }
        
    require_once "../config/connection.php";
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM vehicle WHERE added_by= $user_id ORDER BY created_at DESC";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Listings</title>
   
    <link href="../css/dashboard.css" rel="stylesheet">
    <style>
        .booking-card{
            width: 300px;
        }
        .edit{
            margin-right: 5px;
            margin-top: 20px;
            color: green;
            cursor: pointer;
          
        }
        .edit:hover{
            text-decoration: none;
        }
    </style>
</head>
<body>
<header>
    <h2>All Vehicle Listings</h2>
        <div>
            <a href='add_vehicle_form.php'> Add New Vehicle</a>
            <a href='admin-dashboard.php'>   Back to dashboard </a>
            <a href='../logout.php' onClick ="return confirm('Are you sure you want to logout?')"> Logout</a>
           
        </div>
    </header>
    
    <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="booking-card">
                <img src="../<?php echo $row['image']; ?>" alt="Vehicle Image" width="200" >
                <h3><?php echo htmlspecialchars($row['company']) . ' ' . htmlspecialchars($row['model']); ?></h3>
                <?php if ($row['year']): ?>
                    <p class="booking-info"><strong>Year:</strong> <?php echo htmlspecialchars($row['year']); ?></p>
                <?php endif; ?>
                
                <p class="booking-info"> <strong>Passengers:</strong> <?php echo htmlspecialchars($row['passengers']); ?></p>
                <p class="booking-info"><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
             
                <p class="booking-info"><strong>Price per day: </strong>$<?php echo htmlspecialchars($row['price_per_day']); ?></p>
                <a href="edit_vehicle_form.php?id=<?php echo $row['id']; ?>" class="edit"> Edit</a>
                <a href="../delete_vehicle.php?id=<?php echo $row['id']; ?>" class="edit" onclick="return confirm('Are you sure you want to delete this vehicle?')"> Delete </a>
                
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No vehicles found.</p>
    <?php endif; ?>

    <?php include "../includes/footer.php";?>
</body>
</html>

       