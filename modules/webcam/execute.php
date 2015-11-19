<?php

	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);
	if (isset($_GET['view'])) $view = clean($_GET['view']);


	/* Add webcam
	--------------------------------------------------------------------------- */
	if ($action == "add") {

		$input['inputDeviceName'] = clean($_POST['inputDeviceName']);
		$input['inputDeviceTypeDesc'] = clean($_POST['inputDeviceTypeDesc']);
		$input['inputDeviceURL'] = clean($_POST['inputDeviceURL']);


		$query = "UPDATE msh_devices SET 
					device_name='".$input['inputDeviceName']."', 
					value_unit='".$input['inputDeviceTypeDesc']."'
					WHERE (device_int_id='".$getID."' AND user_id='{$thisUser['user_id']}')";
		$result = $mysqli->query($query);


		$query = "INSERT INTO msh_devices SET 
					user_id='".$thisUser['user_id']."', 
					module='webcam', 
					device_name='". $input['inputDeviceName'] ."', 
					type='webcam', 
					type_desc='". $input['inputDeviceTypeDesc'] ."', 
					url='". $input['inputDeviceURL'] ."'";
		$result = $mysqli->query($query);


		if ($result) {

			$device_int_id = $mysqli->insert_id;

			// Add category to device
			$query = "INSERT INTO msh_devices_has_category SET 
						device_int_id='".$device_int_id."', 
						category_id='12'";
			$result = $mysqli->query($query);

			$msg = "&msg=01&feedback=success";
		} else {
			$msg = "&msg=02&feedback=error_insert_sql";
		}

		header("Location: ?m=webcam" . $msg);
		exit();
		
	}


?>