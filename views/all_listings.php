<?php 
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();}
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <h1>All Vehicle Listings</h1>
        <div>
            <a href='add_vehicle_form.php'>➕ Add New Vehicle</a>
            <a href='admin-dashboard.php'>  Go back to dashboard </a>
            <a href='views/logout.php'>🔒 Logout</a>
           
        </div>
    </header>
    <?php if (mysqli_num_rows($result) > 0): ?>

        <div>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="vehicle-card">
                <img src="../<?php echo $row['image']; ?>" alt="Vehicle Image" width="200" >
                <h3><?php echo htmlspecialchars($row['company']) . ' ' . htmlspecialchars($row['model']); ?></h3>
                <?php if ($row['year']): ?>
                    <p>Year: <?php echo htmlspecialchars($row['year']); ?></p>
                <?php endif; ?>
                
                <p>Passengers: <?php echo htmlspecialchars($row['passengers']); ?></p>
                <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                <?php if ($row['description']): ?>
                    <p>Description: <?php echo htmlspecialchars($row['description']); ?></p>
                <?php endif; ?>
                <p>Price per day: $<?php echo htmlspecialchars($row['price_per_day']); ?></p>
                <a href="edit_vehicle_form.php?id=<?php echo $row['id']; ?>">✏️ Edit</a>
                <a href="../delete_vehicle.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑️ Delete </a>
                
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No vehicles found.</p>
    <?php endif; ?>
</body>
</html>

       