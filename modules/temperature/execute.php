<?php

	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);


	/*
	if ($action == "setMonitor") {
		$query = "UPDATE fu_data_devices SET 
					user='".$userID."'
					WHERE ticket_id='".$getID."'";
		$result = $mysqli->query($query);
	}
	*/

?>