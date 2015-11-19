<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	$d = $objDevices->getDevices();

	/*echo '<pre>';
		print_r($d);
	echo '</pre>';*/


	foreach ($d as $key => $dData) {
		# code...
	

		$update = $objDevices->updateDeviceCurrentValues($dData['deviceIntID']);

		echo '<pre>';
			print_r($update);
		echo '</pre>';

		$update = $objDevices->updateDeviceHistoryValues($dData['deviceIntID']);


		/*echo '<pre>';
			print_r($update);
		echo '</pre>';*/


	}

?>