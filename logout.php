<?php
	ob_start();
	session_start();

	unset($_SESSION['MYSMARTHOME_USER']);

	if (isset($_COOKIE['MYSMARTHOME_USER_REMEMBER'])) {
		setcookie("MYSMARTHOME_USER_REMEMBER", "", time()-3600, "/");
	}


	header("Location: public/");
	exit();
?>