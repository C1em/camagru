<?php
	require 'config/database.php';
	session_start();
	if (isset($_SESSION['user_id']) == 0)
		exit;
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("SELECT image_id FROM likes WHERE user_id = :user_id ORDER BY image_id DESC");
	$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
	$likes = $pdo_p->fetchAll();
	print_r(json_encode($likes));
?>
