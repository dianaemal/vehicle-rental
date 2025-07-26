<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
    header("Location: views/login-form.php");
    exit();
}       
?>


<form action="../add_vehicle.php" method="post" enctype="multipart/form-data">
        <h2>Add New Vehicle</h2>
        <label for="company">Company:</label>
        <input type="text" id="company" name="company" required>
        
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" >
        <label for="passengers"> Number of passengers:</label>
        <input type="number" id="passengers" name="passengers" required>

        <label for="description">Any other comments/description:</label>
        <textarea id="description" name="description" rows="4" ></textarea>

        
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="SUV">SUV</option>
            <option value="Sedan">Sedan</option>
            <option value="Truck">Truck</option>
            <option value="Van">Van</option>
        </select>
        
        <label for="price_per_day">Price per day:</label>
        <input type="number" id="price" name="price_per_day" required>

        <label for="image">Vehicle Image:</label>
        <input type="file" id="image" name="image" accept="image/*" >
   
        
        <button type="submit">Add Vehicle</button>
    <
</form>