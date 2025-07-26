<?php
    echo "Reached top of file<br>";
    require_once "config/connection.php";
  
    


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
        isset($_POST['user_name']) && isset($_POST['email'])
        && isset($_POST['role']) && isset($_POST['password']) && isset($_POST['re_password'])
    ){

        echo "Form submitted<br>";
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $re_password = $_POST['re_password'];

        if ($password !== $re_password) {
            die("Passwords do not match.");
        }
        echo "Passwords match<br>";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      

        // Check if the user already exists
        $check_query = "SELECT email FROM users WHERE email= '$email'";
        $result = mysqli_query($connection, $check_query);
        if (mysqli_num_rows($result) > 0){
            die("User with this email already exists.");
        }
        echo "No existing user<br>";

        $query = "INSERT INTO users (user_name, email, role, password) 
                  VALUES ('$user_name', '$email', '$role', '$hashed_password')";
        if (!mysqli_query($connection, $query)) {
            die("Error: " . mysqli_error($connection));
            
        }
        header("Location: views/login-form.php");
        exit();

        

    }
    echo "Reached end of file<br>";

        

?>