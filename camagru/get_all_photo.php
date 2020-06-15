<?php
	require 'config/database.php';
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("SELECT image_id FROM images");
	$pdo_p->execute(array());
	$image_id = $pdo_p->fetchAll();
	echo json_encode($image_id);
?>
