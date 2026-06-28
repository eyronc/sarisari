<?php
	require_once 'db.php';
	
	function formatPrice ($amount) {
		return "Php " . number_format($amount, 2);
	}
	
	function getUserByUsername ($pdo, $username) {
		$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->execute([$username]);
		return $stmt->fetch();
	}
	
	function registerUser ($pdo, $username, $password) {
		$stmt = $pdo->prepare("INSERT into customers (name) VALUES (?)");
		$stmt->execute([$username]);
		$customerId = $pdo->lastInsertId();
		
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $pdo->prepare("INSERT into users (username, password, role, customer_id) VALUES (?,?,'user',?)");
		$stmt->execute([$username, $hashedPassword, $customerId]);
	}
	
	function getProducts ($pdo, $search = null) {
		if ($search) {
			$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%',?,'%')");
			$stmt->execute([$search]);
		} else {
			$stmt = $pdo->query("SELECT * FROM products");
		}
		
		return $stmt->fetchAll();
	}
	
	function addProduct ($pdo, $name, $price, $stock) {
		$stmt = $pdo->prepare("INSERT into products (name, price, stock) VALUES (?,?,?)");
		$stmt->execute([$name, $price, $stock]);
	}
	
	function updateProduct ($pdo, $id, $name, $price, $stock) {
		$stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id = ?");
		$stmt->execute([$name, $price, $stock, $id]);
	}
	
	function deleteProduct ($pdo, $id) {
		$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
		$stmt->execute([$id]);
	}
	
	function getCustomers ($pdo, $search = null) {
		if ($search) {
			$stmt = $pdo->prepare("SELECT * FROM customers WHERE name LIKE CONCAT('%',?,'%')");
			$stmt->execute([$search]);
		} else {
			$stmt = $pdo->query("SELECT * FROM customers");
		}
		
		return $stmt->fetchAll();
	}
	
	function updateCustomer ($pdo, $id, $name) {
		$stmt = $pdo->prepare("UPDATE customers SET name=? WHERE id = ?");
		$stmt->execute([$name, $id]);
	}
	
	function deleteCustomer ($pdo, $id) {
		$stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
		$stmt->execute([$id]);
	}
	
	function getTransactionsWithCustomers ($pdo, $search = null) {
		$sql = "SELECT t.id, c.name AS customer_name, t.total_amount, t.created_by FROM transactions t
				JOIN customers c ON t.customer_id = c.id";
		
		if ($search) {
			$sql .= " WHERE c.name LIKE CONCAT('%',?,'%')";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$search]);
		} else {
			$stmt = $pdo->query($sql);
		}
		
		return $stmt->fetchAll();
	}
	
	function getMyTransactions ($pdo, $userId) {
		$stmt = $pdo->prepare("SELECT t.id, c.name AS customer_name, t.total_amount, t.created_by FROM transactions t
				JOIN customers c ON t.customer_id = c.id WHERE t.created_by = ?");
		$stmt->execute([$userID]);
	}
	
	function createTransaction ($pdo, $customerId, $createdBy, $items) {
		try {
			$pdo->beginTransaction();
			$totalAmount = 0;
			
			foreach ($items as $item) {
				$totalAmount += $item['quantity'] * $item['unit_price'];
			}
			
			$stmt = $pdo->prepare("INSERT into transactions (customer_id, total_amount, created_by) VALUES (?,?,?)");
			$stmt->execute([$customerId, $totalAmount, $createdBy]);
			$transactionId = $pdo->lastInsertId();
			
			foreach ($items as $item) {
				$subtotal = $item['quantity'] * $item['unit_price'];
				$stmt = $pdo->prepare("INSERT into transaction_items (transaction_id, product_id, quantity, unit_price, subtotal) VALUES (?,?,?,?,?)");
				$stmt->execute([$transactionId, $item['product_id'], $item['quantity'], $item['unit_price'], $subtotal]);
				
				$stmt = $pdo->prepare("UPDATE products SET stock=stock-? WHERE id = ?");
				$stmt->execute([$item['quantity'], $item['product_id']]);
			}
			
			$pdo->commit();
			return true;
		} catch (PDOException $e) {
			$pdo->rollBack();
			return false;
		}
	}
	
	function deleteTransaction ($pdo, $id) {
		$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
		$stmt->execute([$id]);
	}
?>