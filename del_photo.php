<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('img_id', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("DELETE FROM images WHERE image_id = :image_id; DELETE FROM likes WHERE image_id = :image_id; DELETE FROM comments WHERE image_id = :image_id");
		$pdo_p->execute(array(':image_id' => $_POST['img_id']));
		unlink("images/" . $_POST["img_id"] . ".png");
	}
?>
