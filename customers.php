<?php
	session_start();
	require_once 'functions.php';
	
	if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
		header("Location: login.php");
		exit;
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['update'])) {
			updateCustomer($pdo, [$_POST['id'], $_POST['name']]);
		}
		if(isset($_POST['delete'])) {
			deleteCustomer($pdo, [$_POST['id']]);
		}
	}
	
	$search = $_GET['search'] ?? null;
	$customers = getCustomers($pdo, $search);
?>

<!DOCTYPE html>
<html>
	<body>
		<h3>Customers</h3>
		<a href="index.php">Back to Dashboard</a>
		
		<form method="GET">
			<input type="text" name="search" value="<?= $search ?? '' ?>">
			<button type="submit">Search</button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Actions</th>
			</tr>
			
			<?php foreach ($customers as $c): ?>
			<tr>
				<td><?= $c['id']?></td>
				<td><?= $c['name']?></td>
				<td>
					<form method="POST">
						<input type="hidden" name="id" value="<?= $c['id']?>">
						<input type="text" name="name" value="<?= $c['name']?>">
						<button type="submit" name="update">Update</button>
					</form>
					<form method="POST">
						<input type="hidden" name="id" value="<?= $c['id']?>">
						<button type="submit" name="delete">Update</button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</body>
</html>