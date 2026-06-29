<?php
    session_start();
    require_once 'functions.php';
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $existing = getUserByUsername ($pdo, $username);

        if ($existing) {
            $error = 'Username already taken.';
        } else {
            registerUser($pdo,$username,$password);
            $success = 'Registered successfully!';
        }

    }
?>
<!DOCTYPE html>
<html>
    <body>
        <h3>Register</h3>
        <?php if($error) echo $error; ?>
        <?php if($success) echo $success; ?>
        <form method = "POST">
            Username: <input type="text" name="username" required>
            Password: <input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>

        <a href = "login.php">Login</a>
    </body>
</html>