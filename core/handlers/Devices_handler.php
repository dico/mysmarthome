<?php
	require_once( dirname(__FILE__) . '/../../core.php' );

	if (isset($_GET['ajax']) || isset($_GET['json'])) {
		header('Content-Type: application/json');
		$json = true;
	} else {
		$json = false;
	}

	

	if ($_GET['action'] == 'setMonitor')
	{
		$p = array (
			'device_int_id' => $_GET['id'],
			'monitor' => $_GET['value'],
		);

		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}


	if ($_GET['action'] == 'setPublic')
	{
		$p = array (
			'device_int_id' => $_GET['id'],
			'public' => $_GET['value'],
		);

		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}



	if ($_GET['action'] == 'setDashboard')
	{
		$p = array (
			'device_int_id' => $_GET['id'],
			'dashboard' => $_GET['value'],
		);

		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}



	if ($_GET['action'] == 'activate')
	{
		$p = array (
			'device_int_id' => $_GET['id'],
			'deactive' => 0,
		);

		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}



	if ($_GET['action'] == 'deactivate')
	{
		$p = array (
			'device_int_id' => $_GET['id'],
			'deactive' => 1,
		);

		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}




	if ($_GET['action'] == 'delete')
	{

		$deviceIntID = clean($_GET['id']);
		$result = $objDevices->deleteDevice($deviceIntID);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}




	if ($_GET['action'] == 'saveDeviceSettings')
	{

		$p['device_int_id'] = clean($_GET['id']);
		$p['device_name'] = $_POST['device_name'];
		$p['methods'] = $_POST['methods'];
		$p['categories'] = $_POST['categories'];


		$result = $objDevices->updateDevice($p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

		echo '<pre>';
			print_r($result);
		echo '</pre>';

		exit();
	}




	if ($_GET['action'] == 'massDelete')
	{

		$getDevices = $_POST['deactivatedDevices'];
		$numSelected = count($getDevices);

		if ($numSelected == 0) {
			header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=1');
			exit();
		}

		
		foreach ($getDevices as $key => $dID) {
			if (!empty($dID)) {
				$result = $objDevices->deleteDevice($dID);
			}
		}

		if ($result['status'] == 'success') {
			header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']));
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}







	if ($_GET['action'] == 'getDevice')
	{
		$result = $objDevices->getDevice(clean($_GET['id']));

		if($json) {
			echo json_encode($result);
		}
	}


	

?>