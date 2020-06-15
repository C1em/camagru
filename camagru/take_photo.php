<?php
	session_start();
	require 'config/database.php';
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("SELECT notif FROM users WHERE user_id = :user_id AND active = 1");
	$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
	$ret = $pdo_p->fetchAll();
	if (count($ret) !== 1)
	{
		header('Location: index.php:');
		exit ;
	}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
	<link rel="stylesheet" type="text/css" href="styles/take_photo.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="take_photo.js"></script>
</head>
<body style="margin:0px;">
	<div id="top-bar">
		<?php
			include 'logged_top_bar.html';
		?>
	</div>
	<div id="main">
	<script>
		getMedia({video: true});
	</script>
		<div id="sticker-container">
			<img id="stick-1" class="sticker" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Noto_Emoji_KitKat_263a.svg/1200px-Noto_Emoji_KitKat_263a.svg.png" crossorigin>
			<img id="stick-2" class="sticker" src="https://images-eu.ssl-images-amazon.com/images/I/81GhJuX-uRL.png" crossorigin>
			<img id="stick-3" class="sticker" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Twemoji2_1f602.svg/1024px-Twemoji2_1f602.svg.png" crossorigin>
		</div>
		<script src="drag_sticker.js"></script>
		<input id="photo-button" type="button" value="take photo" onclick="take_photo()">
		<form id="upload-form" action="save_photo.php" method="post">
			<input id="file" type="file" name="image">
			<label id="choose-file" for="file">choose a file</label>
			<input id="upload-image" type="button" onclick="upload_photo()" value="upload image">
		</form>
		<video id="video"></video>
		<canvas id="canvas"></canvas>
	</div>
	<div id="side">
	<script>
		print_side_photos();
	</script>
	</div>
</body>
</html>
