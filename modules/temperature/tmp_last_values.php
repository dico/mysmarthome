<?php
	
	$deviceIntID = 16;
	$lastvalues = $objDevices->getDeviceLastValues($deviceIntID);

	echo "<pre>";
		print_r($lastvalues);
	echo "</pre>";
	

?>