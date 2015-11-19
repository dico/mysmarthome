<?php
	require_once( dirname(__FILE__) . '/core.php' );

	$debug = false;

	// Force remember
	$remember = 1;



	$inputLoginMail = clean($_POST['inputLoginMail']);
	$inputLoginPassword = clean($_POST['inputLoginPassword']);
	$inputLoginKey = clean($_POST['inputLoginKey']);

	if ($debug) {
		echo "inputLoginMail: $inputLoginMail <br />";
		echo "inputLoginPassword: $inputLoginPassword <br />";
		echo "inputLoginKey: $inputLoginKey <br />";
	}


	// Set error to false
	$error = false;
	$errorMsg .= "";


	if (empty($inputLoginMail)) {
		$error = true;
		$errorMsg .= "Mail input is missing...<br />";
	}

	if (empty($inputLoginPassword)) {
		$error = true;
		$errorMsg .= "Password input is missing...<br />";
	}

	if (empty($inputLoginKey)) {
		$error = true;
		$errorMsg .= "Login-secure-key is missing...<br />";
	}


	$hashSecureFormLogin = hash('sha256', $_SESSION['secure_fuTElldus_loginForm']);
	if ($inputLoginKey != $hashSecureFormLogin) {
		$error = true;
		$errorMsg .= "Login secure-key is incorrect...<br />";
	}





	if ($error) {
		$errorMsg .= "Input fields are missing or form-key is incorrect!<br />";

		if (!$debug) {
			header("Location: index.php?login=true&msg=02&mail=$inputLoginMail");
			exit();
		}
	}


	else {

		// Hash the password
		$passwordHashed = hash('sha256', $inputLoginPassword);



		// Check credentials
		$query = "SELECT 
					user_id,
					uniq_id 
				  FROM fu_users 
				  WHERE (mail LIKE '$inputLoginMail' AND password LIKE '$passwordHashed')
				  	AND deactive='0'";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		if ($debug) echo "QUERY: $query <br />";

		if ($numRows == 1) {
			$userLoginData = $result->fetch_array();


			// Regenerate session ID to prevent session fixation attacks
			//session_regenerate_id();

			$_SESSION['MYSMARTHOME_USER'] = $userLoginData['user_id'];
			//session_write_close();


			if ($debug) {
				echo "DB user_id: {$userLoginData['user_id']}<br />";
				echo "Session, MYSMARTHOME_USER: {$_SESSION['MYSMARTHOME_USER']}<br />";
			}


			// Set remember me
			if ($remember == 1) {
				$expire = time()+60*60*24*365;
				setcookie("MYSMARTHOME_USER_REMEMBER", $userLoginData['uniq_id'], $expire, "/");

				if ($debug) {
					echo "Cookie set...<br />";
					echo "Cookie value: {$_COOKIE['MYSMARTHOME_USER_REMEMBER']}<br />";
				}
			}

			$errorMsg .= "Login success !<br />";

			if (!$debug) {
				header("Location: index.php");
				exit();	
			}

		} else {
			$errorMsg .= "Wrong username or password<br />";

			if (!$debug) {
				header("Location: index.php?login=true&msg=01&mail=$inputLoginMail");
				exit();
			}
		}


	}


	if ($debug) echo "$errorMsg";
	else {
		header("Location: index.php?login=true&msg=03&mail=$inputLoginMail");
		exit();
	}
	
?>