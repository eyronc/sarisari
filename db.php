<?php
	$host = 'localhost';
	$db = 'sarisari';
	$username = 'root';
	$password = '';
	
	try {
		$pdo = new PDO("mysql:host=$host; dbname=$db", $username, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die ("Connection failed. " . $e->getMessage());
	}
?>