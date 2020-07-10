<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('password', $_POST)
	&& array_key_exists('login', $_POST))
	{
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT user_id, passwd FROM users WHERE login = :login AND active = 1");
		$pdo_p->execute(array(':login' => $_POST['login']));
		$ret = $pdo_p->fetchAll();
		if (count($ret) === 1 && password_verify($_POST['password'], $ret[0]['passwd']))
		{
			session_start();
			$_SESSION['user_id'] = $ret[0]['user_id'];
			echo "1";
		}
		exit;
	}
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
	<link rel="stylesheet" type="text/css" href="styles/form.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
	<script>
	function sendForm()
	{
		const loginInput = document.getElementById("login-input").value;
		const passwdInput = document.getElementById("passwd-input").value;
		$.ajax({
				type: 'POST',
				url: 'login.php',
				data: {password: passwdInput, login : loginInput},
				success: function(response){
					if (response == "1")
					{
						var localIP = <?php echo "\"$localIP\""; ?>;
						console.log(localIP);
						window.location.href = "http://" + localIP + ":8080";
					}
					else if (!document.getElementById("bad-entry"))
					{
						let badEntry = document.createElement("div");
						badEntry.setAttribute("id", "bad-entry");
						let form = document.getElementById("form");
						const content = document.createTextNode("bad password or login");
						badEntry.appendChild(content);
						form.prepend(badEntry);
					}
				}
			});
	}
	</script>
	<form id="form" action="login.php" method="post">
		<span>login</span><br>
		<input id="login-input" class="text-input-form" type="text" name="login"><br><br>
		<span>password</span><br>
		<input id="passwd-input" class="text-input-form" type="text" name="password"><br><br>
		<input id="submit-button" type="button" value="submit" onclick="sendForm()"><br>
		<a href="reinit_passwd.php" style="color:black; font-size:16px;">password forgotten ?</a>
	</form>
</body>
</html>
