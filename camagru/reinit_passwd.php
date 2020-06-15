<?php
	if (isset($_POST['email']) && isset($_POST['new_passwd']))
	{
		if (preg_match('/\s/', $_POST['new_passwd']) !== 0
			|| strlen($_POST['new_passwd']) < 8
			|| strlen($_POST['new_passwd']) > 63)
		{
			header("Location: reinit_passwd.php");
			exit;
		}
		require 'config/database.php';
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT mail FROM users WHERE mail = :email");
		$pdo_p->execute(array(':email' => $_POST['email']));
		$res = $pdo_p->fetchAll();
		if (count($res) !== 1)
		{
			header('Location: reinit_passwd.php');
			exit;
		}
		$tk = md5(rand(1, 1000));
		$passwd = password_hash($_POST['new_passwd'], PASSWORD_DEFAULT);
		$pdo_p = $pdo->prepare("UPDATE users SET token = :token WHERE mail = :email");
		$pdo_p->execute(array(':email' => $_POST['email'], ':token' => $tk));
		$message = wordwrap("http://localhost:8080/passwd_verify.php?email=" . $_POST['email'] . "&token=" . $tk . "&passwd=" . $passwd, 70, "\r\n");
		$mail_sent = mail($_POST['email'], "camagru password change", $message, "From:noreply@camagru.com");
		header("Location: email.html");
		exit;
	}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
	<link rel="stylesheet" type="text/css" href="styles/settings.css">
</head>
<body style="margin:0px;">
	<div id="top-bar">
	<?php
		require_once 'check_log.php';
		if (is_logged() === TRUE)
			require_once 'logged_top_bar.html';
		else
		{
			include 'not_logged_top_bar.html';
		}
	?>
	</div>
	<div>
		<script>
			function submit_form()
			{
				document.getElementById("check_box_form").submit();
			}
		</script>
		<form class="settings-form" action="reinit_passwd.php" method="post">
			<span>email address</span><input type="text" name="email"><br>
			<span>new password</span><input type="text" name="new_passwd"><br>
			<input type="submit" value="submit" name="passwd_change">
		</form>
	</div>
</body>
</html>
