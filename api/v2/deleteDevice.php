<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );


	if (!isset($_GET['id']) || empty($_GET['id'])) {
		die('Device name missing');
	}

	$o = $objDevices->deleteDevice(clean($_GET['id']), $userID);





	// OUTPUT
	if ($output['json']) {
		echo json_encode($o);
	}

	elseif ($output['xml']) {
		echo "XML not avalible atm";
	}

	elseif ($output['text']) {
		echo "<pre>";
			print_r($o);
		echo "</pre>";
	}
	
?>