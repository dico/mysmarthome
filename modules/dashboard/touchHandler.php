<?php
	header('Content-Type: application/json');
	
	require_once( dirname(__FILE__) . '/../../core.php' );
	require('touch.class.php');


	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);


	
	if ($action == "fetchClimaValues") {
		$obj = new touch;
		$result = $obj->fetchClimaValues($user['user_id']);

		echo json_encode($result);
	}
	

?>