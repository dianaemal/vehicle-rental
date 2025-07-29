<?php
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== 1) {
        header("Location: views/login-form.php");
        exit();
    }
   

    require_once '../config/connection.php';
    $query = "SELECT * FROM vehicle";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    // Handle search and filter parameters
    $search = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';
    $category = isset($_GET['category']) ? mysqli_real_escape_string($connection, $_GET['category']) : '';
    $passengers = isset($_GET['passengers']) ? mysqli_real_escape_string($connection, $_GET['passengers']) : '';
    $max_price = isset  ($_GET['max_price']) ? mysqli_real_escape_string($connection, $_GET['max_price']) : '';
    $location = isset($_GET['location']) ? mysqli_real_escape_string($connection, $_GET['location']) : '';  
    $date = isset($_GET['date']) ? mysqli_real_escape_string($connection, $_GET['date']) : '';
    $conditions = [];
    if ($date){
      $conditions [] = "vehicle.id NOT IN (
          SELECT vehicle_id FROM booking WHERE '$date' BETWEEN start_date AND end_date AND status IN ( 'Confirmed', 'Pending' )
      )";
  }
    if ($search){
        $conditions [] = "(v.company LIKE '%$search%' OR v.model LIKE '%$search%')";
    }
    if($category){
        $conditions [] = "v.category = '$category'";  
    }
    if ($passengers){
        $conditions [] = "v.passengers = '$passengers'";
    }
    if ($max_price){
        $conditions [] = "v.price_per_day <= $max_price";
    }
    if ($location){
        $conditions [] = "l.city = '$location'";
    }
    
    if (!empty($conditions)) {
        $clause .= " WHERE " . implode(' AND ', $conditions);
    }
    $query = "SELECT v.id AS vehicle_id, 
              v.company, v.model, v.year, v.passengers, v.category, v.price_per_day, v.image
    
    
    
     FROM vehicle v JOIN location l ON v.location_id = l.id" . $clause;
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
        
        
    

    // Fetch notifications for the logged-in user
    $user_id = $_SESSION['user_id'];
    $notification_query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
    $notification_result = mysqli_query($connection, $notification_query);
    if (!$notification_result) {
        die("Error fetching notifications: " . mysqli_error($connection));
    }   
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>   
    <link rel="stylesheet" href="../css/dashboard.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- Add Bootstrap Icons (in <head>) -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


</head>
<body>
    <header>
        <h2>Customer Dashboard</h2>
        

       

<div class="dropdown" style="display: inline-block; position: relative;">
  <button class="btn btn-light position-relative dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-bell"></i>
    <?php if (mysqli_num_rows($notification_result) > 0): ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo mysqli_num_rows($notification_result); ?>
      </span>
    <?php endif; ?>
  </button>

  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" style="width: 300px; max-height: 300px; overflow-y: auto;">
    <?php if (mysqli_num_rows($notification_result) > 0): ?>
      <?php while ($notification = mysqli_fetch_assoc($notification_result)): ?>
        <li>
          <div class="dropdown-item text-wrap small">
          <span class="me-2 rounded-circle bg-primary" style="width: 8px; height: 8px; display: inline-block;"></span>
            <?php echo htmlspecialchars($notification['message']); ?>
            <div class="text-muted small"><?php echo date("M d, Y H:i", strtotime($notification['created_at'])); ?></div>
          </div>
        </li>
      <?php endwhile; ?>
    <?php else: ?>
      <li><div class="dropdown-item text-muted">No notifications</div></li>
    <?php endif; ?>
  </ul>
</div>
<div>
            
            <a href='all_booking.php'>View Your bookings</a>
            <a href='../logout.php' onclick="return confirm('Are you sure you want to logout?')"> Logout</a>
        </div>

    
  


      
    </header>
    <form class="row g-3 mb-4" method="GET" action="">
      <!-- 🔍 Text search for company or model -->
      <div class="col-md-2">
        <input type="text" name="search" class="form-control" placeholder="Search by brand or model">
      </div>

      <!-- 📂 Category filter -->
      <div class="col-md-2">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          <option value="SUV">SUV</option>
          <option value="Sedan">Sedan</option>
          <option value="Truck">Truck</option>
          <option value="Van">Van</option>
        </select>
      </div>

      <!-- 👤 Passengers filter -->
      <div class="col-md-2">
        <select name="passengers" class="form-select">
          <option value="">Any Capacity</option>
          <option value="2">2 Passengers</option>
          <option value="4">4 Passengers</option>
          <option value="6">6 Passengers</option>
          <option value="7+">7+ Passengers</option>
        </select>
      </div>

      <!-- 💰 Price filter -->
      <div class="col-md-2">
        <input type="number" name="max_price" class="form-control" placeholder="Max Price per Day">
      </div>

      <!-- 🌍 Location filter (you'll populate options with PHP later) -->
      <div class="col-md-2">
        <select name="location" class="form-select">
          <option value="">All Locations</option>
          <!-- Populate dynamically from your locations table -->
         
          <option value="Abbotsford">Abbotsford</option>
          <option value="Surrey">Surrey</option>
        
        </select>
      </div>

        <!--  start date filter -->
        <div class="col-md-2">
        <input type="date" name="date" class="form-control" placeholder="Select Date">
      </div>

      <!-- 🔘 Submit -->
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Apply</button>
      </div>
    </form>


    <div>
        <h2>Available Vehicles</h2>  
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="card-container">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="booking-card">
                        <img src="../<?php echo $row['image']; ?>" alt="Vehicle Image" width="200">
                        <h3><?php echo htmlspecialchars($row['company']) . ' ' . htmlspecialchars($row['model']); ?></h3>
                        <p class="booking-info"><strong>Year:</strong> <?php echo htmlspecialchars($row['year']); ?></p>
                        <p class="booking-info">Passengers: <?php echo htmlspecialchars($row['passengers']); ?></p>
                        <p class="booking-info">Category: <?php echo htmlspecialchars($row['category']); ?></p>
                       
                        <p class="booking-info">Price per day: $<?php echo htmlspecialchars($row['price_per_day']); ?></p>
                        <a href="booking_form.php?id=<?php echo $row['vehicle_id']; ?>">Book Now</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No vehicles available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>