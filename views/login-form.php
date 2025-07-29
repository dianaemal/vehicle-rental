<Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link href="../css/login-register.css" rel="stylesheet">
</head>
<body> 
<div class="container">
 
    <h1>Log In</h1> 
<form action="../login.php" method="POST">
    
  
    
    <input type="email" name="email" placeholder="Email" required>

    <input name="password" type="password" placeholder="Password" required>
    
    <button type="submit">LogIn</button>
    <p>Don't have an account? <a href="register-form.php">Sign Up</a></p>

</form>
</div> 
</body>
</html>