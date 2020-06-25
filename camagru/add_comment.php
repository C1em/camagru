<?php
	// print_r($_POST['com']);
	require_once 'config/database.php';
	session_start();
	if (isset($_POST) && array_key_exists('com', $_POST) && array_key_exists('img_id', $_POST)
	&& isset($_SESSION['user_id']))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("INSERT INTO comments VALUES (:user_id, :image_id, :comment)");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id'], ':image_id' => $_POST['img_id'], ':comment' => $_POST['com']));
		print_r($pdo_p->errorInfo());
		exit;
	}
?>
