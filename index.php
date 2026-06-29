<?php
	session_start();
	require_once 'functions.php';
	
	if(!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit;
	}
	
	$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
	<h2>Welcome, <?= $_SESSION['username']?> (<?= $role?>)</h2>
	<a href = "logout.php">Logout</a>
	
	<?php if ($role === 'admin'): ?>
	<h3>Admin Dashboard </h3>
	<ul>
		<li><a href="customers.php">Manage Customers</a></li>
		<li><a href="products.php">Manage Products</a></li>
		<li><a href="transactions.php">Manage Transactions</a></li>
	</ul>
	
	<?php elseif ($role === 'user'): ?>
	<h3>My Transactions</h3>
	<?php 
		$myTransactions = getMyTransactions($pdo, $_SESSION['user_id']); 
		if ($myTransactions === false) {
			echo "Query failed!";
		}
	?>
	
	<table>
		<tr>
			<th>ID</th>
			<th>Customer</th>
			<th>Total</th>
		</tr>
		
		<?php foreach ($myTransactions as $t): ?>
		<tr>
			<td><?= $t['id']?></td>
			<td><?= $t['customer_name']?></td>
			<td><?= formatPrice($t['total_amount'])?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
</html>