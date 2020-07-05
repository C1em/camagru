<?php
	print_r($_POST);
	require_once 'config/database.php';
	session_start();
	if (isset($_POST) && array_key_exists('img_id', $_POST) && isset($_SESSION['user_id']))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("INSERT INTO likes VALUES (:user_id, :image_id)");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id'], ':image_id' => $_POST['img_id']));
	}
	?>
?>
