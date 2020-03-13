<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('email', $_POST)
	&& array_key_exists('password', $_POST)
	&& array_key_exists('login', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT * FROM users WHERE mail = :email");
		$pdo_p->execute(array(':email' => $_POST['email']));
		$mail_ret = $pdo_p->fetchAll();
		$pdo_p = $pdo->prepare("SELECT * FROM users WHERE login = :login");
		$pdo_p->execute(array(':login' => $_POST['login']));
		$login_ret = $pdo_p->fetchAll();
		if (count($mail_ret) !== 0
		|| count($login_ret) !== 0)
		{
			header("Location: register.php");
			exit;
		}
		$pdo_p = $pdo->prepare("INSERT INTO users (login, passwd, mail) VALUES (:login, :password, :email)");
		$pdo_p->execute(array(':login' => $_POST['login'],'password' => $_POST['password'], ':email' => $_POST['email']));
		session_start();
		$pdo_p = $pdo->prepare("SELECT user_id FROM users WHERE login = :login");
		$pdo_p->execute(array(':login' => $_POST['login']));
		$user_id = $pdo_p->fetch();
		$_SESSION['user_id'] = $user_id['user_id'];
		header("Location: index.php");
		exit;
	}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
	<link rel="stylesheet" type="text/css" href="styles/form.css">
</head>
<body style="margin:0px;">
	<div id="top-bar">
		<?php
			include 'check_log.php';
			if (is_logged() === TRUE)
				include 'logged_top_bar.html';
			else
				include 'not_logged_top_bar.html';
		?>
	</div>
	<form id="form" action="register.php" method="post">
		<span>email</span><br>
		<input class="text-input-form" type="text" name="email"><br><br>
		<span>login</span><br>
		<input class="text-input-form" type="text" name="login"><br><br>
		<div>password</span><br>
		<input class="text-input-form" type="text" name="password"><br><br>
		<input id="submit-button" type="submit" value="register">
	</form>
	</div>
</body>
</html>
