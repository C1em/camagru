<?php
	require_once 'config/database.php';
	if (isset($_POST) && array_key_exists('email', $_POST)
	&& array_key_exists('password', $_POST)
	&& array_key_exists('login', $_POST))
	{
		if (preg_match('/\s/', $_POST['password']) !== 0
			|| strlen($_POST['password']) < 8
			|| strlen($_POST['password']) > 63)
		{
			echo "-1";
			exit;
		}
		if (preg_match('/\s/', $_POST['login']) !== 0
			|| strlen($_POST['login']) > 63)
		{
			echo "-2";
			exit;
		}
		if (preg_match('/\s/', $_POST['email']) !== 0
			|| preg_match('/@/', $_POST['email']) !== 1
			|| strlen($_POST['email']) > 63)
		{
			echo "-3";
			exit;
		}
		$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
		$pdo_p = $pdo->prepare("SELECT * FROM users WHERE mail = :email");
		$pdo_p->execute(array(':email' => $_POST['email']));
		$mail_ret = $pdo_p->fetchAll();
		$pdo_p = $pdo->prepare("SELECT * FROM users WHERE login = :login");
		$pdo_p->execute(array(':login' => $_POST['login']));
		$login_ret = $pdo_p->fetchAll();
		if (count($mail_ret) !== 0)
		{
			echo "-4";
			exit;
		}
		if (count($login_ret) !== 0)
		{
			echo "-5";
			exit;
		}
		$tk = md5(rand(1, 1000));
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$pdo_p = $pdo->prepare("INSERT INTO users (login, passwd, mail, token) VALUES (:login, :password, :email, :token)");
		$pdo_p->execute(array(':login' => $_POST['login'],':password' => $password, ':email' => $_POST['email'], ':token' => $tk));
		$message = wordwrap("http://localhost:8080/verify.php?email=" . $_POST['email'] . "&token=" . $tk, 70, "\r\n");
		$mail_sent = mail($_POST['email'], "camagru email validation", $message, "From:noreply@camagru.com");
		if ($mail_sent === FALSE)
		{
			echo "-6";
			exit;
		}
		echo "1";
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
			const emailInput = document.getElementById("email-input").value;
			if (document.getElementById("bad-entry"))
				document.getElementById("bad-entry").remove();
			if (document.getElementById("good-entry"))
				document.getElementById("good-entry").remove();
			$.ajax({
					type: 'POST',
					url: 'register.php',
					data: {email: emailInput, password: passwdInput, login : loginInput},
					success: function(response){
						console.log(response);
						let entry = document.createElement("div");
						if (response == "1")
							entry.setAttribute("id", "good-entry");
						else
							entry.setAttribute("id", "bad-entry");
						let form = document.getElementById("form");
						let content;
						switch (response)
						{
							case "1": content = document.createTextNode("email sent"); break;
							case "-1": content = document.createTextNode("password must contain at least 8 characters and at most 63 and no spaces"); break;
							case "-2": content = document.createTextNode("login must contain at most 63 and no spaces"); break;
							case "-3": content = document.createTextNode("incorrect email"); break;
							case "-4": content = document.createTextNode("email already used"); break;
							case "-5": content = document.createTextNode("login already used"); break;
							case "-6": content = document.createTextNode("can't send verification email"); break;
						}
						entry.appendChild(content);
						form.prepend(entry);
					}
				});
		}
	</script>
	<form id="form" action="register.php" method="post">
		<span>email</span><br>
		<input id="email-input" class="text-input-form" type="text" name="email"><br><br>
		<span>login</span><br>
		<input id="login-input" class="text-input-form" type="text" name="login"><br><br>
		<span>password</span><br>
		<input id="passwd-input" class="text-input-form" type="text" name="password"><br><br>
		<input id="submit-button" type="button" value="register" onclick="sendForm()">
	</form>
</body>
</html>
