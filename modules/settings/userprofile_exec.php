<?php

	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);



	// Check access
	if ($user['system_admin'] != 1 && ($getID != $user['user_id'])) {
		header("Location: ?m=settings&p=user_profile&id={$user['user_id']}");
		exit();
	}







	/* Save profile
	--------------------------------------------------------------------------- */
	if ($action == "saveProfile") {


		$inputMail = clean($_POST['inputMail']);
		$inputPageTitle = clean($_POST['inputPageTitle']);
		$inputLanguage = clean($_POST['inputLanguage']);
		//$inputTheme = clean($_POST['inputTheme']);

		/*
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
		*/
		


		// Update userdata
		$query = "UPDATE msh_users SET 
					mail='".$inputMail."', 
					language='".$inputLanguage."'
					WHERE user_id='".$getID."'";
		$result = $mysqli->query($query);




		// Insert/Update language
		$query = "REPLACE INTO msh_users_conf SET 
					user_id='".$getID."', 
					config_name='language', 
					config_value='".$inputLanguage."'";
		$result = $mysqli->query($query);

		// Insert/Update page title
		$query = "REPLACE INTO msh_users_conf SET 
					user_id='".$getID."', 
					config_name='page_title', 
					config_value='".$inputPageTitle."'";
		$result = $mysqli->query($query);

		// Insert/Update theme desktop
		/*
		$query = "REPLACE INTO fu_users_conf SET 
					user_id='".$getID."', 
					config_name='theme_desktop', 
					config_value='".$inputTheme."'";
		$result = $mysqli->query($query);
		*/



		// Redirect
		header ("Location: ".$_SERVER['HTTP_REFERER']."");
		exit();

	}




	/* Select theme
	--------------------------------------------------------------------------- */
	if ($action == "selectTheme") {

		$inputTheme = clean($_GET['theme']);

		// Update userdata
		$query = "UPDATE msh_users SET 
					theme='".$inputTheme."'
					WHERE user_id='".$user['user_id']."'";
		$result = $mysqli->query($query);

		// Redirect
		header ("Location: ".$_SERVER['HTTP_REFERER']."");
		exit();
	}

?>