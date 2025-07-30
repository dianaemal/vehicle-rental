<?php
session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        echo "<h3>You aren't authorized to see the content of this page.</h3>";
        exit();
    }
    
    require_once '../config/connection.php';
    $vehicle_id = $_GET['id'] ?? null;
    if (!$vehicle_id) {
        die("Vehicle ID is required.");
    }
    $query = "SELECT v.id
    , v.company, v.model, v.year, v.passengers, v.description, v.category, v.price_per_day, v.image, l.city, l.address
    , v.added_by, l.id AS location_id 
    FROM vehicle v JOIN location l ON v.location_id = l.id WHERE v.id = $vehicle_id";
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <header>
        <h2>Edit Vehicle</h2>
        <div>
            <a href='all_listings.php'>View All Listings</a>
            <a href='admin-dashboard.php'>Go back to dashboard</a>
            <a href='../logout.php' onClick ="return confirm('Are you sure you want to logout?')">Logout</a>
        </div>
    </header>
    <form action="../edit_vehicle.php?id=<?php echo $vehicle_id ?>" method="post" enctype="multipart/form-data">
        <small>Required feilds are marked by *</small>

        <div class="form-group">
            <div class="form-div">
                <label for="company">Company</label><br>
                <input type="text" id="company" name="company" value=<?= $vehicle['company']?> required>
            </div>
            <div class="form-div">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" value=<?= $vehicle['model']?> required>
            </div>
        </div>

        <div class="form-group">
            <div class="form-div">
                <label for="year">Year</label><br>
                <input type="number" id="year" name="year"value=<?= $vehicle['year']?> >
            
            </div>
            <div class="form-div">
                <label for="passengers"> Number of passengers</label><br>
                <input type="number" id="passengers" name="passengers" value=<?= $vehicle['passengers']?> required>
            </div>
        </div>


  
        <div class="form-group">
        <div class="form-div">
        <label for="category">Category</label><br>
        <select id="category" name="category"  required>

            <option value="SUV" <?= $vehicle['category'] == "SUV"? 'selected' : ''?>> SUV</option>
            <option value="Sedan" <?= $vehicle['category'] == "Sedan"? 'selected' : ''?>> Sedan</option>
            <option value="Truck" <?= $vehicle['category'] == "Truck"? 'selected' : ''?>> Truck</option>
            <option value="Van" <?= $vehicle['category'] == "Van"? 'selected' : ''?>> Van</option>
        
        </select>
        </div>
        <div class="form-div">
        
        <label for="price_per_day">Price per day</label>
        <input type="number" id="price" name="price_per_day" value=<?= $vehicle['price_per_day']?> required>
</div>
</div>

<div class="form-group">
<div class="form-div">
        <label for="city">Choose a city in BC</label>
        <select name="city" id="city" required>
            <option value="">-- Select a City --</option>
            <option value="Vancouver" <?= $vehicle['city'] == "Vancouver"? 'selected' : ''?>>Vancouver</option>
            <option value="Victoria" <?= $vehicle['city'] == "Victoria"? 'selected' : ''?>>Victoria</option>
            <option value="Surrey" <?= $vehicle['city'] == "Surrey"? 'selected' : ''?>>Surrey</option>
            <option value="Burnaby" <?= $vehicle['city'] == "Burnaby"? 'selected' : ''?>>Burnaby</option>
            <option value="Richmond" <?= $vehicle['city'] == "Richmond"? 'selected' : ''?>>Richmond</option>
            <option value="Abbotsford" <?= $vehicle['city'] == "Abbotsford"? 'selected' : ''?>>Abbotsford</option>
            <option value="Kelowna" <?= $vehicle['city'] == "Kelowna"? 'selected' : ''?>>Kelowna</option>
            <option value="Nanaimo" <?= $vehicle['city'] == "Nanaimo"? 'selected' : ''?>>Nanaimo</option>
            <option value="Kamloops" <?= $vehicle['city '] == "Kamloops"? 'selected' : ''?>>Kamloops</option>
            <option value="Langley" <?= $vehicle['city'] == "Langley"? 'selected' : ''?>>Langley</option>
            <option value="Coquitlam" <?= $vehicle['city'] == "Coquitlam"? 'selected' : ''?>>Coquitlam</option>
            <option value="Delta" <?= $vehicle['city'] == "Delta"? 'selected' : ''?>>Delta</option>
            <option value="Maple Ridge" <?= $vehicle['city'] == "Maple Ridge"? 'selected' : ''?>>Maple Ridge</option>
            <option value="Prince George" <?= $vehicle['city'] == "Prince George"? 'selected' : ''?>>Prince George</option>
            <option value="New Westminster" <?= $vehicle['city'] == "New Westminster"? 'selected' : ''?>>New Westminster</option>
            <option value="Chilliwack" <?= $vehicle['city'] == "Chilliwack"? 'selected' : ''?>>Chilliwack</option>  
            <option value="North Vancouver" <?= $vehicle['city'] == "North Vancouver"? 'selected' : ''?>>North Vancouver</option>
            <option value="West Vancouver" <?= $vehicle['city'] == "West Vancouver"? 'selected' : ''?>>West Vancouver</option>
            <option value="Port Coquitlam" <?= $vehicle['city'] == "Port Coquitlam"? 'selected' : ''?>>Port Coquitlam</option>
            <option value="Penticton" <?= $vehicle['city'] == "Penticton"? 'selected' : ''?>>Penticton</option>
        </select>
</div>
<div class="form-div">
        <label for="address">Address*</label><br>
        <input type="text" id="address" name="address" value="<?= $vehicle['address']?>" placeholder="123 Main St" required>
</div>
</div>
        <label for="description">Any other comments/description</label>
        <textarea id="description" name="description" rows="4" ><?= $vehicle['description']?></textarea>

        <label for="image">Change Image (optional)</label>
        <input type="file" id="image" name="new_image" accept="image/*"  >
   
        
        <button type="submit">Edit Vehicle</button>
    
</form>
<?php include "../includes/footer.php";?>
</body>
</html>