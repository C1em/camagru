<?php
	if (isset($_POST['old_passwd']) && isset($_POST['new_passwd']))
	{
		if (preg_match('/\s/', $_POST['new_passwd']) !== 0
			|| strlen($_POST['new_passwd']) < 8
			|| strlen($_POST['new_passwd']) > 63)
		{
			header("Location: settings.php");
			exit;
		}
		session_start();
		require 'config/database.php';
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT passwd FROM users WHERE user_id = :user_id");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
		$res = $pdo_p->fetchAll();
		if ($res[0]['passwd'] !== $_POST['old_passwd'])
		{
			header('Location: settings.php');
			exit;
		}
		$pdo_p = $pdo->prepare("UPDATE users SET passwd = :passwd WHERE user_id = :user_id");
		$pdo_p->execute(array(':passwd' => $_POST['new_passwd'], ':user_id' => $_SESSION['user_id']));
	}
	if (isset($_POST['old_mail']) && isset($_POST['new_mail']))
	{
		if (preg_match('/\s/', $_POST['new_mail']) !== 0
			|| preg_match('/@/', $_POST['new_mail']) !== 1
			|| strlen($_POST['new_mail']) > 63)
		{
			header("Location: settings.php");
			exit;
		}
		session_start();
		require 'config/database.php';
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT mail FROM users WHERE user_id = :user_id");
		$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
		$cur_mail = $pdo_p->fetchAll();
		$pdo_p = $pdo->prepare("SELECT mail FROM users WHERE mail = :mail");
		$pdo_p->execute(array(':mail' => $_POST['new_mail']));
		$new_mail = $pdo_p->fetchAll();
		if ($cur_mail[0]['mail'] !== $_POST['old_mail'] || count($new_mail) !== 0)
		{
			header('Location: settings.php');
			exit;
		}
		$tk = md5(rand(1, 1000));
		$pdo_p = $pdo->prepare("UPDATE users SET mail = :email, token = :token, active = 0 WHERE user_id = :user_id");
		$pdo_p->execute(array(':email' => $_POST['new_mail'], ':token' => $tk, ':user_id' => $_SESSION['user_id']));
		$message = wordwrap("http://localhost:8080/verify.php?email=" . $_POST['new_mail'] . "&token=" . $tk, 70, "\r\n");
		$mail_sent = mail($_POST['new_mail'], "camagru email validation", $message, "From:noreply@camagru.com");
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
			header('Location: index.php');
			exit ;
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
		<?php
			require 'config/database.php';
			$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
			$pdo_p = $pdo->prepare("SELECT notif FROM users WHERE user_id = :user_id");
			$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
			$ret = $pdo_p->fetchAll();
			if (count($ret) !== 1)
			{
				header('Location: index.php:');
				exit ;
			}
			if ($ret[0]['notif'] === "1")
				echo '<form class="settings-elem" id="check_box_form" action="notif.php" method="post">notifications <input type="checkbox" name="notif" onclick="submit_form()" checked></form>';
			else
				echo '<form class="settings-elem" id="check_box_form" action="notif.php" method="post">notifications <input type="checkbox" name="notif" onclick="submit_form()"></form>';
		?>
		<script>
			function show_passwd_change()
			{
				if (document.getElementById("hidden_passwd_change").style.display == "none")
					document.getElementById("hidden_passwd_change").style.display = "block";
				else
					document.getElementById("hidden_passwd_change").style.display = "none";
			}
			function show_mail_change()
			{
				if (document.getElementById("hidden_mail_change").style.display == "none")
					document.getElementById("hidden_mail_change").style.display = "block";
				else
					document.getElementById("hidden_mail_change").style.display = "none";
			}
		</script>
		<div class="settings-elem" onclick="show_passwd_change()" style="cursor: pointer;">change password</div>
		<form class="settings-form" id="hidden_passwd_change" action="settings.php" method="post" style="display: none;">
			<span>old password</span><input type="text" name="old_passwd"><br>
			<span>new password</span><input type="text" name="new_passwd"><br>
			<input type="submit" value="submit" name="passwd_change">
		</form>
		<div class="settings-elem" onclick="show_mail_change()" style="cursor: pointer;">change mail</div>
		<form class="settings-form" id="hidden_mail_change" action="settings.php" method="post" style="display: none;">
			<span>old mail</span><input type="text" name="old_mail"><br>
			<span>new mail</span><input type="text" name="new_mail"><br>
			<input type="submit" value="submit" name="mail_change">
		</form>
	</div>
</body>
</html>
