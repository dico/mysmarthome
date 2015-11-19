<?php
	
	// Fetch POST variables
	$inputMail 			= clean($_POST['inputMail']);
	$inputPw 			= clean($_POST['inputPw']);
	$inputDisplayname 	= clean($_POST['inputDisplayname']);
	$inputHomeTitle 	= clean($_POST['inputHomeTitle']);

	// Put them in session to remember them
	$_SESSION['install']['user']['mail'] 		= $inputMail;
	$_SESSION['install']['user']['password'] 	= $inputPw;
	$_SESSION['install']['user']['displayname'] = $inputDisplayname;
	$_SESSION['install']['user']['page_title'] 	= $inputHomeTitle;



	// Try connecting to database
	$mysqli = new Mysqli($_SESSION['install']['db']['db_host'], $_SESSION['install']['db']['db_user'], $_SESSION['install']['db']['db_password'], $_SESSION['install']['db']['db_name']);

	/* check connection */
	if ($mysqli->connect_errno) {
		header('Location: ?page=step03&msg=01');
		exit();
	}




	/*
		CREATE USER IN DATABASE
	*/

	// Generate a API key
	$apiKey = md5('1'.rand(1111,9999).time().'read');
	$apiKey_write = md5('1'.rand(1111,9999).time().'write');

	// Hash password
	$password = hash('sha256', $_SESSION['install']['user']['password']);


	$query = "INSERT INTO msh_users SET 
				user_id='1', 
				mail='". $_SESSION['install']['user']['mail'] ."', 
				displayname='". $_SESSION['install']['user']['displayname'] ."', 
				password='". $password ."', 
				mobile='0', 
				home_status='home', 
				page_title='". $_SESSION['install']['user']['page_title'] ."', 
				public_name='', 
				language='en_GB', 
				apikey='".$apiKey."', 
				apikey_write='".$apiKey_write."', 
				role='admin', 
				public_allow='0', 
				theme='msh2015', 
				page_refresh_time='60', 
				deactive='0'";
	$result = $mysqli->query($query);


	if (!$result) {
		header('Location: ?page=step04&msg=01&error='.$mysqli->error);
		exit();
	}
	



	/*
		ADD SETTINGS TO CONFIG TABLE
	*/

	$query = "UPDATE msh_config SET config_value='".$_SESSION['install']['url']."' WHERE config_name='url'";
	$result = $mysqli->query($query);

	$query = "UPDATE msh_config SET config_value='".$_SESSION['install']['abspath']."' WHERE config_name='absolute_path'";
	$result = $mysqli->query($query);



	header('Location: ?page=step05');
	exit();

?>