<?php
	
	// Fetch POST variables
	$inputDBname 	= clean($_POST['inputDBname']);
	$inputDBuser 	= clean($_POST['inputDBuser']);
	$inputDBpw 		= clean($_POST['inputDBpw']);
	$inputDBhost 	= clean($_POST['inputDBhost']);


	// Put them in session to remember them
	$_SESSION['install']['db']['db_name'] 		= $inputDBname;
	$_SESSION['install']['db']['db_user'] 		= $inputDBuser;
	$_SESSION['install']['db']['db_password'] 	= $inputDBpw;
	$_SESSION['install']['db']['db_host'] 		= $inputDBhost;




	// Try connecting to database
	$mysqli = new Mysqli($_SESSION['install']['db']['db_host'], $_SESSION['install']['db']['db_user'], $_SESSION['install']['db']['db_password'], $_SESSION['install']['db']['db_name']);

	/* check connection */
	if ($mysqli->connect_errno) {
		header('Location: ?page=step03&msg=01');
		exit();
	}

	


	header('Location: ?page=step03_populate');
	exit();

?>