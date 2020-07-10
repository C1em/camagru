<?php
	require_once 'config/database.php';
	session_start();
	if (isset($_SESSION['user_id']) == 0)
	{
		echo "-1";
		exit;
	}
	if (isset($_POST) && array_key_exists('com', $_POST) && array_key_exists('img_id', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("INSERT INTO comments VALUES (:user_id, :image_id, :comment)");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id'], ':image_id' => $_POST['img_id'], ':comment' => $_POST['com']));

		$pdo_p = $pdo->prepare("SELECT notif, mail FROM users INNER JOIN images ON images.user_id = users.user_id WHERE images.image_id = :image_id");
		$pdo_p->execute(array(':image_id' => $_POST['img_id']));
		$user_info = $pdo_p->fetch();
		if($user_info['notif'] === '1')
		{
			$pdo_p = $pdo->prepare("SELECT login FROM users WHERE user_id = :user_id");
			$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
			$user_login = $pdo_p->fetch();
			$message = wordwrap($user_login['login'] . " commented your photo", 70, "\r\n");
			$mail_sent = mail($user_info['mail'], "photo commented", $message, "From:noreply@camagru.com");
		}
	}
?>
