<?php
	require_once 'database.php';
	$pdo = new PDO(substr($DB_DSN, 0, strpos($DB_DSN, ";")), "root", "admin1");
	$requ = "CREATE DATABASE camagru; CREATE USER '$DB_USER'@'127.0.0.1' IDENTIFIED BY '$DB_PASSWORD'; GRANT ALL PRIVILEGES ON camagru.* TO '$DB_USER'@'127.0.0.1'; FLUSH PRIVILEGES";
	$pdo->exec($requ);
	$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
	$requ = "CREATE TABLE `users`
	(
		`user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`login` VARCHAR(64) NOT NULL,
		`passwd` VARCHAR(64) NOT NULL,
		`mail` VARCHAR(64) NOT NULL,
		`token` VARCHAR(64),
		`notif` BOOLEAN DEFAULT TRUE,
	);
	CREATE TABLE `images`
	(
		`user_id` INT NOT NULL,
		`image_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
	);
	CREATE TABLE `likes`
	(
		`user_id` INT NOT NULL,
		`image_id` INT NOT NULL
	);
	CREATE TABLE `comments`
	(
		`user_id` INT NOT NULL,
		`image_id` INT NOT NULL,
		`comment` VARCHAR(64) NOT NULL
	);";
	$pdo->exec($requ);
?>
