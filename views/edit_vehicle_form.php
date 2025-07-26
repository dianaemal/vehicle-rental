<?php
session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    require_once '../config/connection.php';
    $vehicle_id = $_GET['id'] ?? null;
    if (!$vehicle_id) {
        die("Vehicle ID is required.");
    }
    $query = "SELECT * FROM vehicle WHERE id = $vehicle_id";
    $result = mysqli_query($connection, $query);
    $vehicle = mysqli_fetch_assoc($result);

    
    if (!$vehicle) {
        die('Query failed: ' . mysqli_error($connection));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Vehicle</h1>
        <div>
            <a href='all_listings.php'>View All Listings</a>
            <a href='admin-dashboard.php'>Go back to dashboard</a>
            <a href='views/logout.php'>Logout</a>
        </div>
    </header>
    <form action="../edit_vehicle.php?id=<?php echo $vehicle_id ?>" method="post" enctype="multipart/form-data">
        <h2>Edit</h2>
        <label for="company">Company:</label>
        <input type="text" id="company" name="company" value=<?= $vehicle['company']?> required>
        
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" value=<?= $vehicle['model']?> required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year"value=<?= $vehicle['year']?> >
        <label for="passengers"> Number of passengers:</label>
        <input type="number" id="passengers" name="passengers" value=<?= $vehicle['passengers']?> required>

        <label for="description">Any other comments/description:</label>
        <textarea id="description" name="description" rows="4" ><?= $vehicle['description']?></textarea>

        
        <label for="category">Category:</label>
        <select id="category" name="category"  required>
            <option value="SUV" <?= $vehicle['category'] == "SUV"? 'selected' : ''?>> SUV</option>
            <option value="Sedan" <?= $vehicle['category'] == "Sedan"? 'selected' : ''?>> Sedan</option>
            <option value="Truck" <?= $vehicle['category'] == "Truck"? 'selected' : ''?>> Truck</option>
            <option value="Van" <?= $vehicle['category'] == "Van"? 'selected' : ''?>> Van</option>
            <option value="Car" <?= $vehicle['category'] == "Car"? 'selected' : ''?>> Car</option>
        </select>
        
        <label for="price_per_day">Price per day:</label>
        <input type="number" id="price" name="price_per_day" value=<?= $vehicle['price_per_day']?> required>

        <label for="image">Change Image (optional):</label>
        <input type="file" id="image" name="new_image" accept="image/*"  >
   
        
        <button type="submit">Edit Vehicle</button>
    <
</form>