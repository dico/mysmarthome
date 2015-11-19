<?php
	
	/**
	* 
	*/
	class touch
	{
		
		public function fetchClimaValues($userID)
		{
			
			global $mysqli;

			$query = "SELECT * 
					  FROM fu_data_devices 
					  WHERE user_id='$userID'
					  	AND (category LIKE 'clima')
						AND deactive='0'
					  ORDER BY device_name ASC
					  ";
			$result = $mysqli->query($query);
			$numRows = $result->num_rows;


			$r = array();

			while ($row = $result->fetch_array()) {
				$deviceData = device_getLastValue($row['device_int_id']);

				if (!empty($row['device_name'])) $deviceName = $row['device_name'];
				else $deviceName = "No name";

				$r[$row['device_int_id']]['deviceID'] = $row['device_int_id'];
				$r[$row['device_int_id']]['name'] = $deviceName;
				$r[$row['device_int_id']]['temp'] = $deviceData['value1'];
				if (!empty($deviceData['value2'])) $r[$row['device_int_id']]['humidity'] = $deviceData['value2'];
				$r[$row['device_int_id']]['last_update'] = $deviceData['time'];
				$r[$row['device_int_id']]['last_update_human'] = date('d-m-Y H:i', $deviceData['time']);
				$r[$row['device_int_id']]['last_update_iso'] = date('c', $deviceData['time']);
			}


			return $r;

		}



		public function fetchOnOffDevices($userID)
		{
			
			global $mysqli;

			$query = "SELECT * 
					  FROM fu_data_devices 
					  WHERE user_id='$userID'
					  	AND (category LIKE 'light')
						AND deactive='0'
					  ORDER BY device_name ASC
					  ";
			$result = $mysqli->query($query);
			$numRows = $result->num_rows;


			$r = array();

			while ($row = $result->fetch_array()) {
				$deviceData = device_getLastValue($row['device_int_id']);

				if (!empty($row['device_name'])) $deviceName = $row['device_name'];
				else $deviceName = "No name";

				if ($deviceData['value1'] == 1) $state = "on";
				else $state = "off";

				$r[$row['device_int_id']]['deviceID'] = $row['device_int_id'];
				$r[$row['device_int_id']]['name'] = $deviceName;
				$r[$row['device_int_id']]['state'] = $state;
			}


			return $r;

		}


	}

?>