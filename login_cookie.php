<?php
	/*require_once( dirname(__FILE__) . '/core.php' );

	// Delete the cookie... 
	//setcookie("MYSMARTHOME_USER_REMEMBER", "", time()-3600, "/");
	//unset($_COOKIE['MYSMARTHOME_USER_REMEMBER']);
	//unset($_SESSION['MYSMARTHOME_USER']);



	$query = "SELECT * FROM msh_users WHERE uniq_id LIKE '{$_COOKIE['MSH_USER_REMEMBER']}'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;


	// User found...
	if ($numRows == 1) {
		$cookieUser = $result->fetch_array();

		echo "Login success... <br />";



	

		session_regenerate_id();									// Regenerate and set session ID
		$_SESSION['MSH_USER_AUTH'] = $cookieUser['user_id'];		// Create session




		

		// Regenerate uniq ID
		$randomID = $objAuth->randomID();


		// Update uniq ID in DB
		$query = "UPDATE msh_users SET 
					uniq_id='".$randomID."'
					WHERE user_id='".$cookieUser['user_id']."'";
		$result = $mysqli->query($query);


		// Expand cookie session with new uniq ID
		$expire = time()+60*60*24*365;
		setcookie("MSH_USER_REMEMBER", $randomID, $expire, "/");


	}






	// User NOT found
	else {

		// Delete the cookie... 
		echo "Login failed... <br />";
		setcookie("MSH_USER_REMEMBER", "", time()-3600, "/");
	}


	header('Location: '.URL.'.index.php');
	exit();*/
?>