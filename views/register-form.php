<Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <link href="../css/login-register.css" rel="stylesheet">

</head>
<body>

<div class="container">
 
    

<form action="../register.php" method="POST">
    
    <h1>Sign Up</h1>
    <input type="text" name="user_name" placeholder="User Name" required>

    <input type="email" name="email" placeholder="Email" required>
   
    <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="Admin">Host</option>
        <option value="Customer">Customer</option>
    </select>
 
    <input name="password" type="password" placeholder="Password" required>
    
    <input name="re_password" type="password" placeholder="Re-enter Password" required>
    <button type="submit">Sign Up</button>
    <p>Already have an account? <a href="login-form.php">Log In</a></p>

</form>
</div>

  
</body>
</html>