<?php
    $connection = mysqli_connect("localhost", "root", "", "vehicle-rental");

     // Check connection:
   if (!$connection){
       die("Failed to connect to mysql: " . mysqli_connect_error());
   }


?>