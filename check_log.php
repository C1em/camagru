<?php
	function is_logged() : BOOL
	{
		require 'config/database.php';
		session_start();
		if (isset($_SESSION['user_id']))
		{
			$pdo = new PDO($DB_DSN, "$DB_USER", $DB_PASSWORD);
			$pdo_p = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
			$pdo_p->execute(array(':user_id' => $_SESSION['user_id']));
			$ret = $pdo_p->fetchAll();
			if (count($ret) === 1)
				return TRUE;
		}
		session_destroy();
		return FALSE;
	}
?>
