<?php
	header('Content-Type: application/json');
	require_once( dirname(__FILE__) . '/../../core.php' );


	$timeStart = ( time() - (86400 * 1) );
	$timeEnd = time();


	$deviceID = 16;

	if (isset($_GET['unitID']))
		$unitID = $_GET['unitID'];
	else
		$unitID = 1;





	$query = "SELECT * 
			  FROM msh_devices_log 
			  WHERE device_int_id='$deviceID'
			  	AND (time BETWEEN '$timeStart' AND '$timeEnd')
			  	AND unit_id='$unitID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$data = array();
	while ($row = $result->fetch_array()) {

		//$time = date('Y-m-d', $row['time']);

		$time = ($row['time'] * 1000);
		$value = $row['value'];

		//$data[] = "[$time, {$row['value']}]";

		$data[] = "[$datetime, $value]";


	}

	//$data = array(7,4,2,8,4,1,9,3,2,16,7,12);

	$data = join($data, ',');


	echo $_GET['callback']. '('. json_encode($data) . ')';  
?>