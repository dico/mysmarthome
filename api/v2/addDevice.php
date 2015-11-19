<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );


	if (!isset($_GET['name']) || empty($_GET['name'])) {
		die('Device name missing');
	}


	$p = array(
		'device_name' => clean($_GET['name']),
		'user_id' => $userID,
		'binding' => clean($_GET['binding']),
		'device_ext_id' => clean($_GET['extid']),
		'category' => clean($_GET['category']),
	);

	$o = $objDevices->addDevice($p);


	// Add method
	if ($o['status'] == 'success') {
		$query = "REPLACE INTO msh_devices_has_methods SET 
					d_id='". $o['device_id'] ."', 
					m_id='". clean($_GET['method']) ."'";
		$result = $mysqli->query($query);
	}



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