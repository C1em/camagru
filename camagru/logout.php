<?php
	include 'check_log.php';
	if (is_logged() === TRUE)
		session_destroy();
	header("Location: index.php");
?>
