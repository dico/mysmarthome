<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}


	echo "<h2>"._('Local Network Devices')."</h2>";
	
	$query = "SELECT * FROM msh_devices WHERE module LIKE 'network' ORDER BY device_name ASC";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	echo "<table class='table table-striped table-hover'>";

	echo "<thead>";
		echo "<tr>";
			echo "<th>"._('IP-adress')."</th>";
			echo "<th>"._('Hostname')."</th>";
			echo "<th>"._('Description')."</th>";
			echo "<th>"._('MAC adress')."</th>";
			echo "<th>"._('Last value')."</th>";
			echo "<th>"._('Last seen')."</th>";
		echo "</tr>";
	echo "</thead>";


	echo "<tbody>";

	while ($row = $result->fetch_array()) {

		echo "<tr>";

			$lastSeen_sec = (time() - $row['last_seen']);

			if ($lastSeen_sec < 240) $active = true;
			else $active = false;


			
			$lastValues = $objDevices->getDeviceLastValues($row['device_int_id']);

			/*
			echo "<pre>";
				print_r($lastValues);
			echo "</pre>";
			*/
			

			$timeDiff = (time() - $lastValues['time']);

			if ($timeDiff < 240 && $lastValues['value1'] == 1) {
				$active = true;
			} else {
				$active = false;
			}


			echo "<td>";

				if ($active) echo "<img style='width:18px; margin-right:8px;' src='core/images/icons/bullet-green.png' alt='active' />";
				else echo "<img style='width:18px; margin-right:8px;' src='core/images/icons/bullet-red.png' alt='inactive' />";

				echo "<a href='?m=network&page=chart&id={$row['device_int_id']}'>";
					echo "{$row['device_name']}";
				echo "</a>";
			echo "</td>";

			echo "<td>";
				echo "<a href='?m=network&page=chart&id={$row['device_int_id']}'>";
					echo "{$row['description']}";
				echo "</a>";
			echo "</td>";

			echo "<td>";
				echo "{$row['description2']}";
			echo "</td>";

			echo "<td>";
				echo "{$row['device_ext_id']}";
			echo "</td>";

			echo "<td>";
				echo "{$lastValues['value1']}";
			echo "</td>";

			echo "<td>";
				echo date('d-m-Y H:i', $lastValues['time']) . ' &nbsp; (' . ago($lastValues['time']) . ')';
			echo "</td>";

			/*
			echo "<td>";
				echo date('d-m-Y H:i', $row['last_synced']) . ' &nbsp; (' . ago($row['last_synced']) . ')';
			echo "</td>";
			*/

		echo "</tr>";
	}
	echo "</tbody>";

	echo "</table>";

?>