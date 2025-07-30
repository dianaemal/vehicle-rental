<?php
echo "<h1>Delete Vehicle</h1>";
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
    header("Location: views/login-form.php");
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<h3>You aren't authorized to see the content of this page.</h3>";
    exit();
}

require_once 'config/connection.php';

$vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if (!$vehicle_id || !$user_id) {
    die("Missing vehicle ID or user not logged in properly.");
}

echo "Attempting to delete vehicle ID: $vehicle_id by user: $user_id<br>";

$query = "DELETE FROM vehicle WHERE id = $vehicle_id AND added_by = $user_id";

if (mysqli_query($connection, $query)) {
    echo "Vehicle deleted successfully.";
    header("Location: views/all_listings.php?message=Vehicle deleted successfully.");
    exit();
} else {
    echo "Error deleting vehicle: " . mysqli_error($connection);
}
?>
