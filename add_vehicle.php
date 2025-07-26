<?php
// Check if the user is an admin
  
    require_once 'config/connection.php';
 
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])
    && isset($_POST['model']) && isset($_POST['passengers']) && isset($_POST['category'])
    && isset($_POST['price_per_day']) && isset($_FILES['image'])) 
    
    {
     
        $company = $_POST['company'];
        $model = $_POST['model'];
        $year = $_POST['year'] ? $_POST['year'] : null;
        $added_by = $_SESSION['user_id'];
        $passengers = $_POST['passengers'];
        $description = $_POST['description'] ? $_POST['description'] : null;
        $category = $_POST['category'];
        $price_per_day = $_POST['price_per_day'];

       

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            
            // Get the uploaded file name and move it to the target directory
            $image_name = $_FILES['image']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . $image_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Insert vehicle data into the database
                $query = "INSERT INTO vehicle (added_by, company, model, year, passengers, description, category, price_per_day, image)
                          VALUES ('$added_by', '$company', '$model', '$year', '$passengers', '$description', '$category', '$price_per_day', '$target_file')";
                if (mysqli_query($connection, $query)) {
                    echo "Vehicle added successfully.";
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "No image uploaded or upload error.";
        }
    }


?>