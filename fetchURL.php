 <?php
	require_once('core.php');

	/**
	 * Example URL: http://domain.no/fetchURL.php?key=<apikey>&deviceID=313&id=R4sdf423G
	 *
	*/


	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['key'])) $api_key = clean($_GET['key']);
	else die('Key missing');

	/*if (isset($_GET['deviceID'])) $deviceID = clean($_GET['deviceID']);
	else die('Device ID missing');*/

	if (isset($_GET['id'])) $urlID = clean($_GET['id']);
	else die('URL trigger ID missing');
	


	// Check API key
	$query = "SELECT * FROM msh_users WHERE apikey LIKE '$api_key'";
	$result = $mysqli->query($query);
	$numUsers = $result->num_rows;

	if ($numUsers > 0) {
		$userData = $result->fetch_array();


		// Fetch URL data
		$query = "SELECT * FROM msh_url_triggers WHERE url_id LIKE '$urlID'";
		$result = $mysqli->query($query);
		$numURLTriggers = $result->num_rows;

		if ($numURLTriggers == 1) {
			$urlTriggerData = $result->fetch_array();
			

			// Add alert
			$alertParams = array(
				'user_id' => $userData['user_id'],
				'device_int_id' => $urlTriggerData['device_int_id'],
				'level' => $urlTriggerData['alert_level'],
				'title' => $urlTriggerData['title'],
			);
			$result = $objCore->alerts_add($alertParams);


			// Add log
			$p = array(
				'device_int_id' => $urlTriggerData['device_int_id'],
				'unit_id' => 4,
				'value' => $urlTriggerData['set_value'],
				'state' => $urlTriggerData['set_value'],
			);
			$result = $objDevices->addLog($p);


			// Run event
			$disableLastRun = true;
			$result = $objEvents->runEvent($urlTriggerData['trigger_event'], $disableLastRun);

			echo "<pre>";
				print_r($result);
			echo "</pre>";
			


		} else die('No trigger found');
	} else die('No user found');





?>