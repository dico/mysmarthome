<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );



	$o = $objDevices->getUnits();




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