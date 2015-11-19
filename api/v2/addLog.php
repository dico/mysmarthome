<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );


	if (!isset($_GET['id']) || empty($_GET['id'])) {
		die('Device ID missing');
	}

	if (!isset($_GET['unitid']) || empty($_GET['unitid'])) {
		die('Unit ID missing');
	}

	if (!isset($_GET['value']) || empty($_GET['value'])) {
		die('Value missing');
	}


	if (!isset($_GET['time']) || empty($_GET['time'])) {
		$_GET['time'] = time();
	}

	$p = array(
		'device_int_id' => clean($_GET['id']),
		'time' => clean($_GET['time']),
		'unit_id' => clean($_GET['unitid']),
		'value' => clean($_GET['value']),
	);

	$o = $objDevices->addLog($p);





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