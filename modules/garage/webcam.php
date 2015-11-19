<?php
	require_once( dirname(__FILE__) . '/../../core.php' );

	//echo date('d-m-Y H:i:s') . '<br />';
	
	/* Show camera image
	--------------------------------------------------------------------------- */
	$query = "SELECT * 
			  FROM msh_devices 
			  WHERE module='webcam'
			  	AND user_id='{$thisUser['user_id']}'
			  	AND device_int_id='618'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	while ($row = $result->fetch_array()) {

		//echo "{$row['url']} <br />";

		if ($row['type_desc'] == "url") {
			echo "<img style='width:100%;' src='{$row['url']}?time=".time()."'>";
		}

		elseif ($row['type_desc'] == "folder") {
			$matches = array();
			preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($row['url']), $matches);
			echo "<img style='width:100%;' src='" . $row['url'] . end($matches[2]) . "'>";
		}
	}


?>