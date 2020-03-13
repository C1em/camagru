<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
</head>
<body style="margin:0px;">
	<div id="top-bar">
	<?php
		require_once 'check_log.php';
		if (is_logged() === TRUE)
			require_once 'logged_top_bar.html';
		else
			header('Location: index.php:');
	?>
	</div>
	<div>
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
		?>
		<span>notifications on comment</span>
		<input type="checkbox"/>
	</div>
</body>
</html>
