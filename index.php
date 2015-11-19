<?php
	require_once( dirname(__FILE__) . '/core.php' );

	$error = false;


	if (!checkAuth()) // Check if login session exist
	{
		// Check if remember me cookie exist
		if (isset($_COOKIE['MSH_USER_REMEMBER'])) {
			$result = $objAuth->doCookieLogin(); // Do login with cookie

			// Refresh page if cookie auth success
			if ($result['status'] == 'success') {
				//echo '<meta http-equiv="refresh" content="0">';
				header('Location: index.php');
				exit();
			}

			// Set error flag if not reponse is 'success'
			else {
				$error = true;
			}
		}

		// Set error flag if cookie does not exist
		else {
			$error = true;
		}
	}




	// If error-flag == true -> Send to login page
	if ($error == true) {
		header('Location: '.URL.'login/');
		exit();
	}
	

	

	// If auth OK, fetch theme
	if (checkAuth()) {
		include(ABSPATH . "themes/{$config['theme']}/index.php");
	}

?>