<?php
	require_once( dirname(__FILE__) . '/../core.php' );

	// Get parameters
	if (!isset($_GET['apikey'])) {
		die('API key missing');
	}


	$objApi = new Msh\Api;
	$apiToken = clean($_GET['apikey']);
	$userID = $objApi->apiToken2userId($apiToken); //Get user ID from API Token


	// DIE if user not found
	if (empty($userID)) {
		die('User not found');
	}


	$output = array();

	if (isset($_GET['output'])) {
		if ($_GET['output'] == 'json') {
			header('Content-Type: application/json');
			$output['json'] = true;
		}
		elseif ($_GET['output'] == 'xml') {
			header('Content-type: text/xml');
			$output['xml'] = true;
		}
		else {
			$output['text'] = true;
		}
	}

?>