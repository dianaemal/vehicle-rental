<?php

  
    require_once 'config/connection.php';
 
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
        echo "<h3>You aren't authorized to see the content of this page.</h3>";
        exit();
    }
    
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])
    && isset($_POST['model']) && !empty($_POST['passengers']) && !empty($_POST['category'])
    && isset($_POST['price_per_day']) && isset($_FILES['image']) && !empty($_POST['city']) && !empty($_POST['address']))
    
    {
     
        $city = $_POST['city'];
        $address = $_POST['address'];
        // Insert the location into the database
        $location_query = "INSERT INTO location (city, address) VALUES ('$city', '$address')";
        if (mysqli_query($connection, $location_query)) {
            $location_id = mysqli_insert_id($connection); // Get the last inserted location ID
        } else {
            die("Error inserting location: " . mysqli_error($connection));
        }
        $location = $location_id ? $location_id : null; // Use the location ID or null if not set
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
                $query = "INSERT INTO vehicle (added_by, location_id, company, model, year, passengers, description, category, price_per_day, image)
                          VALUES (? , ? , ?, ?, ?, ?, ?, ?, ?, ?)";
               
                $stmt = mysqli_prepare($connection, $query);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "iisssissss", 
                    $added_by, $location_id, $company, $model, $year, $passengers, $description, $category, $price_per_day, $target_file);
                
                    if (mysqli_stmt_execute($stmt)) {
                        header("Location: views/all_listings.php");
                        exit();
                    } else {
                        echo "Execute failed: " . mysqli_stmt_error($stmt);
                    }
                
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Prepare failed: " . mysqli_error($connection);
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "No image uploaded or upload error.";
        }
    }


?>