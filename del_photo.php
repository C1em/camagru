<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('img_id', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("DELETE images, likes, comments FROM images, likes, comments WHERE images.image_id = :image_id AND likes.image_id = :image_id AND comments.image_id = :image_id");
		$pdo_p->execute(array(':image_id' => $_POST['img_id']));
		unlink("images/" . $_POST["img_id"] . ".png");
	}
?>
