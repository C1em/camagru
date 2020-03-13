<!--don't log if already log -->

<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('password', $_POST)
	&& array_key_exists('login', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT user_id FROM users WHERE login = :login AND passwd = :password");
		$pdo_p->execute(array(':login' => $_POST['login'], ':password' => $_POST['password']));
		$ret = $pdo_p->fetchAll();
		if (count($ret) === 1)
		{
			session_start();
			$_SESSION['user_id'] = $ret[0]['user_id'];
			header("Location: index.php");
			exit;
		}
		header("Location: login.php");
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
	<form id="form" action="login.php" method="post">
		<span>login</span><br>
		<input class="text-input-form" type="text" name="login"><br><br>
		<div>password</span><br>
		<input class="text-input-form" type="text" name="password"><br><br>
		<input id="submit-button" type="submit" value="login">
	</form>
	</div>
</body>
</html>
