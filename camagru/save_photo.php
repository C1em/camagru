<?php
	function get_image_from_b64($b64)
	{
		$img = str_replace('data:image/png;base64,', '', $b64);
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$img = base64_decode($img);
		return ($img);
	}
	if (!isset($_POST['image']))
	{
		header('Location: take_photo.php');
		exit;
	}
	if (($size = getimagesize($_POST['image'])) === FALSE)
	{
		header('Location: take_photo.php');
		exit;
	}
	mkdir("images");
	session_start();
	require 'config/database.php';
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$pdo_p = $pdo->prepare("INSERT INTO images (user_id) VALUES (:user_id)");
	$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
	$pdo_p = $pdo->prepare("SELECT image_id FROM images WHERE user_id = :user_id");
	$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
	$image_id = $pdo_p->fetchAll();
	$img = get_image_from_b64($_POST['image']);
	$stickers = json_decode($_POST['stickers_pos']);

	$dest = imagecreatefromstring($img);
	for ($i = 4; $i < count($stickers); $i += 5)
	{
		$sticker = get_image_from_b64($stickers[$i]);
		$src = imagecreatefromstring($sticker);
		imagecopy($dest, $src, $stickers[$i - 4], $stickers[$i - 3], 0, 0, $stickers[$i - 2], $stickers[$i - 1]);
	}
	imagepng($dest, "images/" . $image_id[count($image_id) - 1]['image_id'] . ".png");
	exit;
?>
