<?php
	
	// Fetch POST variables
	$selectTimezone 	= clean($_POST['selectTimezone']);
	$inputURL 			= clean($_POST['inputURL']);
	$inputAbsPath 		= clean($_POST['inputAbsPath']);

	// Put them in session to remember them
	$_SESSION['install']['timezone'] 	= $selectTimezone;
	$_SESSION['install']['url'] 		= $inputURL;
	$_SESSION['install']['abspath'] 	= $inputAbsPath;


	header('Location: ?page=step03');
	exit();

?>