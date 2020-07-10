<?php
	session_start();
	require 'config/database.php';
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("SELECT image_id FROM images WHERE user_id = :user_id");
	$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
	$image_id = $pdo_p->fetchAll();
	echo json_encode($image_id);
?>
