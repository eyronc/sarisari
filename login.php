<?php
	session_start();
	require_once 'functions.php';
	
	$error = '';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = getUserByUsername($pdo, $username);
		
		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['role'] = $user['role'];
			
			header("Location: index.php");
			exit;
		} else {
			$error = "Invalid username or password. ";
		}
	}
?>

<!DOCTYPE html> 
	<body>
		<h3>Login</h3>
		<?php if($error) echo $error ?>
		<form method = "POST">
			Username: <input type="text" name="username" required>
			Password: <input type="password" name="password" required>
			<button type="submit">Login</button>
		</form>
		<a href="register.php">Register</a>
	</body>
</html>