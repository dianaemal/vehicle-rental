<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
    header("Location: views/login-form.php");
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<h3>You aren't authorized to see the content of this page.</h3>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        label{
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h2>Add Vehicle</h2>
        <div>
            <a href='all_listings.php'>View All Listings</a>
            <a href='admin-dashboard.php'>Go back to dashboard</a>
            <a href='../logout.php' onClick ="return confirm('Are you sure you want to logout?')" >Logout</a>
        </div>    
    </header>   

<form action="../add_vehicle.php" method="post" enctype="multipart/form-data">
      <small>Required feilds are marked by *</small>
    <div class="form-group">
        <div class="form-div">
        <label for="company">Company*</label><br>
        <input type="text" id="company" name="company" required>
        </div>
        <div class="form-div" >
        
        <label for="model">Model*</label><br>
        <input type="text" id="model" name="model" required>
        </div>
    </div>

    <div class="form-group">
        <div class="form-div">
        <label for="year">Year</label><br>
        <input type="number" id="year" name="year" >
        </div>
        <div class="form-div">
        <label for="passengers"> Number of passengers*</label><br>
        <input type="number" id="passengers" name="passengers" required>
        </div>
    </div>


      

        <div class="form-group">
            <div class="form-div">
            <label for="category">Category*</label><br>
            <select id="category" name="category" required>
                <option value=""> -- Select a Category --</option>
                <option value="SUV">SUV</option>
                <option value="Sedan">Sedan</option>
                <option value="Truck">Truck</option>
                <option value="Van">Van</option>
            </select>
            </div>
            <div class="form-div">
            <label for="price_per_day">Price per day*</label><br>
            <input type="number" id="price" name="price_per_day" required>
            </div>
        </div>
        <div class="form-group">
            <div class="form-div">
        <label for="city">Choose a city in BC*</label><br>
        <select name="city" id="city" required>
            <option value="">-- Select a City --</option>
            <option value="Vancouver">Vancouver</option>
            <option value="Victoria">Victoria</option>
            <option value="Surrey">Surrey</option>
            <option value="Burnaby">Burnaby</option>
            <option value="Richmond">Richmond</option>
            <option value="Abbotsford">Abbotsford</option>
            <option value="Kelowna">Kelowna</option>
            <option value="Nanaimo">Nanaimo</option>
            <option value="Kamloops">Kamloops</option>
            <option value="Langley">Langley</option>
            <option value="Coquitlam">Coquitlam</option>
            <option value="Delta">Delta</option>
            <option value="Maple Ridge">Maple Ridge</option>
            <option value="Prince George">Prince George</option>
            <option value="New Westminster">New Westminster</option>
            <option value="Chilliwack">Chilliwack</option>
            <option value="North Vancouver">North Vancouver</option>
            <option value="West Vancouver">West Vancouver</option>
            <option value="Port Coquitlam">Port Coquitlam</option>
            <option value="Penticton">Penticton</option>
        </select>
</div>
        <div class="form-div">
        <label for="address">Pick up/Drop off Address*</label><br>
        <input type="text" id="address" name="address" placeholder="123 Main St" required>
</div>
    </div>
    <label for="description">Any other comments/description</label>

<textarea id="description" name="description" rows="4" ></textarea>


        <label for="image">Vehicle Image*</label>
        <input type="file" id="image" name="image" accept="image/*"  required>
   
        
        <button type="submit">Add Vehicle</button>
    
</form>
    <?php include "../includes/footer.php";?>
</body>
</html>