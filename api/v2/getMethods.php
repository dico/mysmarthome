<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );



	$query = "SELECT * FROM msh_methods";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$o = array();
	while ($row = $result->fetch_array()) {
		$o[$row['m_id']]['id'] = $row['m_id'];
		$o[$row['m_id']]['title'] = $row['title'];
		$o[$row['m_id']]['cmd'] = $row['cmd'];
		$o[$row['m_id']]['supported_value'] = $row['supported_value'];
	}





	// OUTPUT
	if ($output['json']) {
		echo json_encode($o);
	}

	elseif ($output['xml']) {
		echo "XML not avalible atm";
	}

	elseif ($output['text']) {
		echo "<pre>";
			print_r($o);
		echo "</pre>";
	}
	
?>