<?php
	require_once( dirname(__FILE__) . '/../api_auth.php' );



	$query = "SELECT * FROM msh_categories";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$o = array();
	while ($row = $result->fetch_array()) {
		$o[$row['cat_id']]['id'] = $row['cat_id'];
		$o[$row['cat_id']]['name'] = $row['name'];
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