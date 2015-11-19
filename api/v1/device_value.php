<?php
	require_once( dirname(__FILE__) . '/../../core.php' );



	// Get parameters
	$apiToken = clean($_GET['apikey']);
	$getUserID = $objApi->apiToken2userId($apiToken); //Get user ID from API Token


	// DIE if user not found
	if (empty($getUserID)) {
		die('User not found...');
	}


	$p = array();
	$p['user'] = $getUserID;


	// Explode categories to array
	if (isset($_GET['id'])) {
		$getID = clean($_GET['id']);
	} 

	// DIE if id missing
	else {
		die('Category must be set...');
	}






	$device = $objDevices->getDevice($getID);

	if ($device['user']['user_id'] != $getUserID) {
		die('User not found...');
	}



	if (isset($_GET['value'])) $getValue = clean($_GET['value']);
	else $getValue = 1;

	
	if ($getValue == 1) {
		echo $device['last_values']['value1'] . ' ' . $device['last_values']['value1_unit'][1];
	}

	elseif ($getValue == 2) {
		echo $device['last_values']['value2'] . ' ' . $device['last_values']['value2_unit'][1];
	}

	elseif ($getValue == 3) {
		echo $device['last_values']['value3'] . ' ' . $device['last_values']['value3_unit'][1];
	}
	


?>