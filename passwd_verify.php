<?php
	if (!isset($_GET['email']) || !isset($_GET['token']) || !isset($_GET['passwd']))
	{
		echo "error";
	}
	else
	{
		require_once 'config/database.php';
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT * FROM users WHERE mail = :email AND token = :token");
		$pdo_p->execute(array(':email' => $_GET['email'], ':token' => $_GET['token']));
		$res = $pdo_p->fetchAll();
		if (count($res) === 0)
		{
			header('Location: login.php');
			exit;
		}
		$pdo_p = $pdo->prepare("UPDATE users SET passwd = :passwd WHERE mail = :email");
		$pdo_p->execute(array(':email' => $_GET['email'], ':passwd' => $_GET['passwd']));
		session_start();
		$pdo_p = $pdo->prepare("SELECT user_id FROM users WHERE  mail = :email");
		$pdo_p->execute(array(':email' => $_GET['email']));
		$user_id = $pdo_p->fetch();
		$_SESSION['user_id'] = $user_id['user_id'];
		header("Location: index.php");
		exit;
	}
?>
