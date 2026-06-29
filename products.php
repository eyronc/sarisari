<?php
	session_start();
	require_once 'functions.php';
	
	if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
		header("Location: login.php");
		exit;
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['update'])) {
			updateProduct($pdo, [$_POST['id'], $_POST['name'], $_POST['price'], $_POST['stock']]);
		}
		if(isset($_POST['delete'])) {
			deleteProduct($pdo, [$_POST['id']]);
		}
	}
	
	$search = $_GET['search'] ?? null;
	$products = getProducts($pdo, $search);
?>

<!DOCTYPE html>
<html>
	<body>
		<h3>Products</h3>
		<a href="index.php">Back to Dashboard</a>
		
		<form method="GET">
			<input type="text" name="search" value="<?= $search ?? '' ?>">
			<button type="submit">Search</button>
		</form>
		
		<table>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
				<th>Actions</th>
			</tr>
			
			<?php foreach ($products as $p): ?>
			<tr>
				<td><?= $p['id']?></td>
				<td><?= $p['name']?></td>
				<td><?= $p['price']?></td>
				<td><?= $p['stock']?></td>
				<td>
					<form method="POST">
						<input type="hidden" name="id" value="<?= $p['id']?>">
						<input type="text" name="name" value="<?= $p['name']?>">
						<input type="text" name="price" value="<?= $p['price']?>">
						<input type="text" name="stock" value="<?= $p['stock']?>">
						<button type="submit" name="update">Update</button>
					</form>
					<form method="POST">
						<input type="hidden" name="id" value="<?= $p['id']?>">
						<button type="submit" name="delete">Delete</button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</body>
</html>