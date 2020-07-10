<?php
	require_once 'config/database.php';
	session_start();
	if (isset($_SESSION['user_id']) == 0)
	{
		echo "-1";
		exit;
	}
	if (isset($_POST) && array_key_exists('img_id', $_POST)
	&& array_key_exists('liked', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		if($_POST['liked'] === '0')
			$pdo_p = $pdo->prepare("INSERT INTO likes VALUES (:user_id, :image_id)");
		else
			$pdo_p = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND image_id = :image_id");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id'], ':image_id' => $_POST['img_id']));
		if($_POST['liked'] === '1')
			return ;
		$pdo_p = $pdo->prepare("SELECT notif, mail FROM users INNER JOIN images ON images.user_id = users.user_id WHERE images.image_id = :image_id");
		$pdo_p->execute(array(':image_id' => $_POST['img_id']));
		$user_info = $pdo_p->fetch();
		if($user_info['notif'] === '1')
		{
			$pdo_p = $pdo->prepare("SELECT login FROM users WHERE user_id = :user_id");
			$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
			$user_login = $pdo_p->fetch();
			$message = wordwrap($user_login['login'] . " liked your photo", 70, "\r\n");
			$mail_sent = mail($user_info['mail'], "photo liked", $message, "From:noreply@camagru.com");
		}
	}
?>
