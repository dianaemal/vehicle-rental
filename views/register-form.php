<form action="../register.php" method="POST">
    
    <label for="user_name">User Name:</label>
    <input type="text" name="user_name">
    <label for="email">Email:</label>
    <input type="email" name="email">
    <label for="role" >Role:</label>
    <select name="role">
        <option value="Admin">Admin</option>
        <option value="Customer">Customer</option>
    </select>
    <label for="password">Password:</label>
    <input name="password" type="password">
    <label for="re_password">Re-Password:</label>
    <input name="re_password" type="password">
    <button type="submit">Sign Up</button>

</form>