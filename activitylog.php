<?php
	header('Content-Type: application/json');


	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/core.php' );
	}



	//$query = "SELECT * FROM msh_activity_log WHERE user_id='{$user['user_id']}' ORDER BY time DESC LIMIT 5";
	$query = "SELECT * FROM msh_activity_log WHERE user_id='1' ORDER BY time DESC LIMIT 5";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$r = array();

	if ($numRows > 0) {
		while ($row = $result->fetch_array()) {
			$r[$row['log_id']]['log_id'] = $row['log_id'];
			$r[$row['log_id']]['time'] = $row['time'];
			$r[$row['log_id']]['time_human'] = date('d-m-Y H:i', $row['time']);
			$r[$row['log_id']]['time_ago'] = ago($row['time']);
			$r[$row['log_id']]['user_id'] = $row['user_id'];
			$r[$row['log_id']]['device_int_id'] = $row['device_int_id'];
			$r[$row['log_id']]['message'] = $row['message'];

			/*
			$r = array (
							'log_id' => "{$row['log_id']}"
					   );
			*/
			//array_push($r, $s);
		}
	}


	$r = array_reverse($r);
	echo json_encode($r);
?>