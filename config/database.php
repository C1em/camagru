<?php
	$localIP = getHostByName(getHostName());
	$DB_DSN = "mysql:host=localhost:3306;dbname=camagru";
	$DB_USER = "admin";
	$DB_PASSWORD = "admin";
	$DB_OPTIONS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
?>
