<!-- use more than just cookie to log -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/top_bar.css">
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
	<div id="photo-list">
		<?php include 'photo_list.php' ?>
	</div>
</body>
</html>
