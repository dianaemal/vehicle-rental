<?php  
// Check if the user is logged in
    session_start();

    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
    require_once 'config/connection.php';
    $vehicle_id = $_GET['id'] ?? null;
    if (!$vehicle_id) {
        die("Vehicle ID is required.");
    }
  
    $query = "SELECT v.id,
         l.id AS location_id,
         v.image
    FROM vehicle v JOIN location l ON v.location_id = l.id WHERE v.id = $vehicle_id";
    $result = mysqli_query($connection, $query);
    $vehicle = mysqli_fetch_assoc($result);
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])
    && isset($_POST['model']) && isset($_POST['passengers']) && isset($_POST['category'])
    && isset($_POST['price_per_day'])  && !empty($_POST['city']) && !empty($_POST['address']))
    
    {
       
        $company = $_POST['company'];
        $model = $_POST['model'];
        $year = $_POST['year'] ? $_POST['year'] : null;
        $added_by = $_SESSION['user_id'];
        $passengers = $_POST['passengers'];
        $description = $_POST['description'] ? $_POST['description'] : null;
        $category = $_POST['category'];
        $price_per_day = $_POST['price_per_day'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $original_image = $vehicle['image'];

        // Handle file upload
        if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === 0) {

            
            // Get the uploaded file name and move it to the target directory
            $image_name = $_FILES['new_image']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . $image_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file)) {
                // delete the old image if it exists
                if ($original_image && file_exists($original_image)) {
                    unlink($original_image);
                    echo "Deleted old image: " . $original_image . "<br>";
                    
                    // Show remaining files
                    $remaining = glob('uploads/*');
                    echo "<h4>Remaining files:</h4>";
                    foreach ($remaining as $file) {
                        echo basename($file) . "<br>";
                    }
                    
                } else {
                    echo "No old image to delete or it does not exist.<br>";
                }
                $original_image = $target_file;
                
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "No image uploaded or upload error.";
        }
        // Update vehicle data in the database
        $location_id = $vehicle['location_id'];
        $location_query = "UPDATE location SET city='$city', address='$address' WHERE id=$location_id";
        if (!mysqli_query($connection, $location_query)) {
            die("Error updating location: " . mysqli_error($connection));
        }
        $query = "UPDATE vehicle SET company='$company', model='$model', year='$year', passengers='$passengers', description='$description', category='$category', price_per_day='$price_per_day', image='$original_image' WHERE id=$vehicle_id";
        if (mysqli_query($connection, $query)) {
            header("Location: views/all_listings.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
?>