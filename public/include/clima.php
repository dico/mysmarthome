<?php

	$query = "SELECT * 
			  FROM fu_data_devices 
			  WHERE user_id='{$userData['user_id']}'
			  	AND public='1'
			  	AND type LIKE 'sensor'
			 ";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	while ($row = $result->fetch_array()) {

		$deviceData = device_getLastValue($row['device_int_id']);

		if ((time() - $deviceData['time']) > 1800) {
			$syncError = true;
		} else {
			$syncError = false;
		}

		if ($syncError)	$errorColor = "orange;";
		else $errorColor = "";





		echo "<div class='sensorContainer'>";


			echo "<div class='sensorName'>";
				echo $row['device_name'];
			echo "</div>";


			if (!empty($deviceData['value1'])) {
				echo "<div class='sensorValue01'>";
					echo "<img src='../core/images/units/temperature.png' alt='temperature' />";
					echo $deviceData['value1'] . "&deg;";
				echo "</div>";
			}


			if (!empty($deviceData['value2'])) {
				echo "<div class='sensorValue02'>";
					echo "<img src='../core/images/units/water02.png' alt='temperature' />";
					echo $deviceData['value2'] . "%";
				echo "</div>";
			}

			echo "<div class='clearfix'></div>";


			


			if (!empty($deviceData['value3'])) {
				echo "<div class='sensorValue03'>";
					echo $deviceData['value3'] . "";
				echo "</div>";
			}

			

			if ($syncError) {
				echo "<div class='timeUpdate'>";
					echo "<span style='color:$errorColor;'>" . ago($deviceData['time']) . "</span>";
				echo "</div>";
			}

		echo "</div>";
	}


?>