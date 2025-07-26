<?php
    require_once "config/connection.php";
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['email']) && isset($_POST['password'])
    ){
        $email = $_POST['email'];
        $password = $_POST['password'];
        // to prevent SQL injection:

        $stmt = mysqli_prepare($connection, "SELECT id, password, role, user_name FROM users WHERE email = ?");
        //bind user's entered email to the prepared statement:
        mysqli_stmt_bind_param($stmt, "s", $email);
        //execute the prepared statement on db:
        mysqli_stmt_execute($stmt);
        //bind the result to variables:
        mysqli_stmt_bind_result($stmt, $id, $hashed_password, $role, $user_name);
        //store the result of the executed statement:
        mysqli_stmt_store_result($stmt);
        
        //check if user exists:

        if (mysqli_stmt_num_rows($stmt) == 1) {
            //fetch the result (hashed_password, role) into the variables:
            mysqli_stmt_fetch($stmt);
            //verify password:
            if (password_verify($password, $hashed_password)) {
                //start session and set user data:

                $_SESSION['is_logged_in'] = 1;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $user_name;
                if ($role == 'Admin') {
                    header("Location: views/admin-dashboard.php");
                } else {
                    header("Location: views/customer-dashboard.php");
                }
            }
            else {
                header("Location: views/login-form.php?error=Invalid credentials");
            }

            
        }
        else{
            header("Location: views/login-form.php?error=Invalid credentials");
        }
        

        
    }

?>