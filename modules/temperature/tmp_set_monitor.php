<?php
	$unitID = 1;
	$getDevices = $objDevices->getDevicesByUnit($unitID);
	
	echo "<pre>";
		print_r($getDevices);
	echo "</pre>";
	
	foreach ($getDevices as $key => $intID) {
		$query = "UPDATE msh_devices SET monitor='1' WHERE device_int_id='".$intID."'";
		$result = $mysqli->query($query);
	}
	



	$getUnits = $objDevices->getUnits();

	echo "<pre>";
		print_r($getUnits);
	echo "</pre>";
	

?>