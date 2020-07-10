<?php
	session_start();
	if (isset($_POST) && array_key_exists('notif', $_POST))
		$notif = 1;
	else
		$notif = 0;
	require 'config/database.php';
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("UPDATE users SET notif = :notif WHERE user_id = :user_id");
	$pdo_p->execute(array(':notif' => $notif, ':user_id' => $_SESSION['user_id']));
	header('Location: settings.php');
	exit ;
?>
