<?php
namespace Msh;
/**
* 
*/
class Devices extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();

		$this->Auth = new Auth;
		$this->Users = new Users;

		$this->thisUser = $this->Auth->getAuthUser();
	}



	/*
		First class, without userparameter...
	*/

	function getDevices($p = array()) {
		$r = array();

		// Set default values
		if (!isset($p['user'])) 		$p['user'] = $this->thisUser['user_id'];
		if (!isset($p['deactive'])) 	$p['deactive'] = 0;
		if (!isset($p['order'])) 		$orderBy = 'device_name';
		if (!isset($p['sort'])) 		$sort = 'ASC';
		if (!isset($p['limit'])) 		$limit = 1000;

		// Stop if user ID missing
		if (empty($p['user'])) {
			$r['status'] = 'error';
			$r['message'] = 'UserID missing';
			return $r;
			exit();
		}


		// Query builder
		if (isset($p['user'])) {
			$q[] = "user_id='{$p['user']}'";
		}

		if (isset($p['module'])) {
			$q[] = "module='{$p['module']}'";
		}

		if (isset($p['binding'])) {
			$q[] = "binding='{$p['binding']}'";
		}

		if (isset($p['deactive'])) {
			$q[] = "deactive='{$p['deactive']}'";
		}

		if (isset($p['dashboard'])) {
			$q[] = "dashboard='{$p['dashboard']}'";
		}

		if (isset($p['device_ext_id'])) {
			$q[] = "device_ext_id='{$p['device_ext_id']}'";
		}


		// Categories
		if (isset($p['categories'])) {
			$numCat = count($p['categories']);
			$c = 0;

			if ($numCat > 0) {
				$qWhereCat = "(";
				foreach ($p['categories'] as $key => $catID) {
					$qWhereCat .= "category_id='$catID'";

					$c++;
					if ($c < $numCat) $qWhereCat .= ' OR ';
				}
				$qWhereCat .= ")";
			}

			$q[] = $qWhereCat;
		}




		$numQ = count($q);
		$c = 0;

		if ($numQ > 0) {
			$qWhere = ' WHERE (';

			foreach ($q as $key => $qData) {
				$qWhere .= $qData;

				$c++;
				if ($c < $numQ) $qWhere .= ' AND ';
			}

			$qWhere .= ')';
		}

		//echo "qWhere: $qWhere <br />";



		if (isset($p['order'])) $orderBy = $p['order'];
		if (isset($p['sort'])) 	$sort = $p['sort'];
		if (isset($p['limit'])) $limit = $p['limit'];


		$query = "SELECT *,
					d.device_int_id AS intID
				  FROM msh_devices AS d
				  LEFT JOIN msh_devices_has_category AS hasCat ON hasCat.device_int_id = d.device_int_id
				    $qWhere
				  ORDER BY $orderBy $sort LIMIT $limit";
		//echo "query: $query <br />";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$lastValues = $this->getDeviceLastValues($row['device_int_id']);

			if (!empty($row['device_alias'])) $deviceName = $row['device_alias'];
			elseif (!empty($row['device_name'])) $deviceName = $row['device_name'];
			else $deviceName = _('No name');

			$r[$row['intID']]['name'] = $deviceName;
			$r[$row['intID']]['deviceIntID'] = $row['intID'];
			$r[$row['intID']]['deviceExtID'] = $row['device_ext_id'];
			$r[$row['intID']]['user'] = $this->Users->getUser($row['user_id']);
			$r[$row['intID']]['module'] = $row['module'];
			$r[$row['intID']]['binding'] = $row['binding'];
			$r[$row['intID']]['type'] = $row['type'];
			$r[$row['intID']]['type_desc'] = $row['type_desc'];
			$r[$row['intID']]['category'] = $row['category'];
			$r[$row['intID']]['description'] = $row['description'];
			$r[$row['intID']]['icon'] = $row['icon'];
			$r[$row['intID']]['url'] = $row['url'];
			$r[$row['intID']]['latitude'] = $row['latitude'];
			$r[$row['intID']]['longitude'] = $row['longitude'];
			$r[$row['intID']]['state'] = $row['state'];
			$r[$row['intID']]['dashboard'] = $row['dashboard'];
			$r[$row['intID']]['dashboard_size'] = $row['dashboard_size'];
			$r[$row['intID']]['monitor'] = $row['monitor'];
			$r[$row['intID']]['public'] = $row['public'];
			$r[$row['intID']]['deactive'] = $row['deactive'];
			//$r[$row['intID']]['time_last_update'] = $deviceData['time'];
			//$r[$row['intID']]['time_last_update_human'] = date('d-m-Y H:i', $deviceData['time']);
			//$r[$row['intID']]['time_last_update_iso'] = date('c', $deviceData['time']);
			$r[$row['intID']]['last_values'] = $lastValues;
			$r[$row['intID']]['categories'] = $this->getDeviceCategories($row['intID']);
			$r[$row['intID']]['methods'] = $this->getSupportedMethods($row['intID']);
			$r[$row['intID']]['units'] = $this->getDeviceUnits($row['device_int_id']);
		}

		return $r;
	}





	function getDevice($intID)
	{
		$r = array();

		$query = "SELECT * 
				  FROM msh_devices 
				  WHERE device_int_id='$intID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$lastValues = $this->getDeviceLastValues($row['device_int_id']);

			if (!empty($row['device_alias'])) $deviceName = $row['device_alias'];
			elseif (!empty($row['device_name'])) $deviceName = $row['device_name'];
			else $deviceName = "No name";

			$r['name'] = $deviceName;
			$r['deviceIntID'] = $row['device_int_id'];
			$r['deviceExtID'] = $row['device_ext_id'];
			$r['user'] = $this->Users->getUser($row['user_id']);
			$r['module'] = $row['module'];
			$r['binding'] = $row['binding'];
			$r['type'] = $row['type'];
			$r['type_desc'] = $row['type_desc'];
			$r['category'] = $row['category'];
			$r['description'] = $row['description'];
			$r['icon'] = $row['icon'];
			$r['url'] = $row['url'];
			$r['latitude'] = $row['latitude'];
			$r['longitude'] = $row['longitude'];
			$r['state'] = $row['state'];
			$r['dashboard'] = $row['dashboard'];
			$r['dashboard_size'] = $row['dashboard_size'];
			$r['monitor'] = $row['monitor'];
			$r['public'] = $row['public'];
			$r['deactive'] = $row['deactive'];
			//$r['time_last_update'] = $deviceData['time'];
			//$r['time_last_update_human'] = date('d-m-Y H:i', $deviceData['time']);
			//$r['time_last_update_iso'] = date('c', $deviceData['time']);
			$r['last_values'] = $lastValues;
			$r['categories'] = $this->getDeviceCategories($row['device_int_id']);
			$r['methods'] = $this->getSupportedMethods($row['device_int_id']);
			$r['units'] = $this->getDeviceUnits($row['device_int_id']);
		}

		return $r;
	}









	




	function getDeviceLastValues($deviceIntID) {

		$r = array();


		if (USE_MEMCACHE == true) {
			$key = md5('deviceLastValues'.$deviceIntID); // Unique Words
			$r = $this->memcache->get($key); // Memcached object
		}
		//unset($r);

		if(!$r) // If no cached data avalible, get fresh data
		{

			$getUnits = $this->getUnits();
			$getDeviceUnits = $this->getDeviceUnits($deviceIntID);

			$query = "SELECT *
					  FROM msh_devices_current_values
					  WHERE device_int_id='$deviceIntID'";
			$result = $this->mysqli->query($query);

			while ($row = $result->fetch_array()) {
				$r[$row['unit_id']]['unit']['id'] = $row['unit_id'];
				$r[$row['unit_id']]['unit']['title'] = $getUnits[$row['unit_id']]['title'];
				$r[$row['unit_id']]['unit']['tag'] = $getUnits[$row['unit_id']]['tag'];
				$r[$row['unit_id']]['time']['timestamp'] = strtotime($row['timestamp']);
				$r[$row['unit_id']]['time']['time_human'] = date('d-m-Y H:i', strtotime($row['timestamp']));
				$r[$row['unit_id']]['time']['time_iso'] = $row['timestamp'];
				$r[$row['unit_id']]['value'] = $row['value'];
				$r[$row['unit_id']]['history']['max'] = $row['max'];
				$r[$row['unit_id']]['history']['min'] = $row['min'];
				$r[$row['unit_id']]['history']['avg'] = $row['avg'];
			}

			if (USE_MEMCACHE == true)
				$this->memcache->set($key, $r, MEMCACHE_COMPRESSED, (60*5*1*1)); // sec*min*hours*days
		}

		return $r;
	}






	function setCurrentValue($deviceIntID, $unitID, $value, $time = 0)
	{
		$r = array();

		if (USE_MEMCACHE == true) {
			$key = md5('checkDeviceCurrentValueExists'.$deviceIntID.$unitID); // Unique Words
			$numRows = $this->memcache->get($key); // Memcached object
		}


		if(!$r) // If no cached data avalible, get fresh data
		{
			$query = "SELECT * FROM msh_devices_current_values WHERE device_int_id='$deviceIntID' AND unit_id='$unitID'";
			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			if (USE_MEMCACHE == true)
				$this->memcache->set($key, $r, MEMCACHE_COMPRESSED, (60*5*24*365)); // sec*min*hours*days
		}


		if ($time == 0) {
			$time = date('Y-m-d H:i:s');
		}


		// Insert if not exist
		if ($numRows == 0) {
			$query2 = "INSERT INTO msh_devices_current_values SET 
						device_int_id='$deviceIntID', 
						unit_id='$unitID', 
						timestamp='$time', 
						value='$value'";
			$result2 = $this->mysqli->query($query2);

			$r['query'] = $query2;

			if ($result2) $r['message'] = 'Device current value inserted';
			else {
				$r['message'] = 'Error inserting device current values';
				$r['query'] = $query2;
			}

			
		} 


		// Update value if exist
		else {
			$query2 = "UPDATE msh_devices_current_values AS D SET 
						timestamp='$time', 
						value='$value'
					  WHERE device_int_id='$deviceIntID' AND unit_id='$unitID'";
			$result2 = $this->mysqli->query($query2);

			$r['query'] = $query2;

			if ($result2) $r['message'] = 'Device current value updated';
			else {
				$r['message'] = 'Error updating device current values';
				$r['query'] = $query2;
			}
		}

		return $r;
	}




	/*function updateDeviceCurrentValues($deviceIntID) {

		$r = array();


		$getUnits = $this->getUnits();
		$getDeviceUnits = $this->getDeviceUnits($deviceIntID);



		$query = "SELECT *
				  FROM (
				  	SELECT *
					FROM msh_devices_log 
					WHERE device_int_id = '$deviceIntID' 
					ORDER BY time DESC
				  ) as whatever
				  GROUP BY unit_id";
		$result = $this->mysqli->query($query);

		while ($row = $result->fetch_array()) {
			$r[$row['unit_id']]['unit']['id'] = $row['unit_id'];
			$r[$row['unit_id']]['unit']['title'] = $getUnits[$row['unit_id']]['title'];
			$r[$row['unit_id']]['unit']['tag'] = $getUnits[$row['unit_id']]['tag'];
			$r[$row['unit_id']]['time']['timestamp'] = $row['time'];
			$r[$row['unit_id']]['time']['time_human'] = date('d-m-Y H:i', $row['time']);
			$r[$row['unit_id']]['time']['time_iso'] = date('c', $row['time']);
			$r[$row['unit_id']]['value'] = $row['value'];


			$time = date('Y-m-d H:i:s', $row['time']);
			$this->setCurrentValue($deviceIntID, $row['unit_id'], $row['value'], $time);
		}


		return $r;
	}*/




	function updateDeviceCurrentValues($deviceIntID) {

		$r = array();


		$getUnits = $this->getUnits();
		$getDeviceUnits = $this->getDeviceUnits($deviceIntID);


		foreach ($getDeviceUnits as $key => $unitData) {
			# code...

			$query = "SELECT *
						FROM msh_devices_log 
						WHERE device_int_id = '$deviceIntID' AND unit_id='{$unitData['id']}' 
						ORDER BY time DESC
						LIMIT 1";
			echo '<pre>';
				print_r($query);
			echo '</pre>';
			$result = $this->mysqli->query($query);

			while ($row = $result->fetch_array()) {
				$r[$row['unit_id']]['unit']['id'] = $row['unit_id'];
				$r[$row['unit_id']]['unit']['title'] = $getUnits[$row['unit_id']]['title'];
				$r[$row['unit_id']]['unit']['tag'] = $getUnits[$row['unit_id']]['tag'];
				$r[$row['unit_id']]['time']['timestamp'] = $row['time'];
				$r[$row['unit_id']]['time']['time_human'] = date('d-m-Y H:i', $row['time']);
				$r[$row['unit_id']]['time']['time_iso'] = date('c', $row['time']);
				$r[$row['unit_id']]['value'] = $row['value'];


				$time = date('Y-m-d H:i:s', $row['time']);
				$updateValue = $this->setCurrentValue($deviceIntID, $row['unit_id'], $row['value'], $time);
			}

		}

		return $r;
	}



	function updateDeviceHistoryValues($deviceIntID) {

		$r = array();
		$getDeviceUnits = $this->getDeviceUnits($deviceIntID);


		// Max, min, avg values
		foreach ($getDeviceUnits as $unitID => $uData) {
			$query = "SELECT 
						MAX(value), MIN(value), AVG(value)
					  FROM msh_devices_log 
					  WHERE device_int_id='$deviceIntID'
					  	AND unit_id='$unitID'";
			$result = $this->mysqli->query($query);

			while ($row = $result->fetch_array()) {
				$r[$unitID]['history']['max'] = $row['MAX(value)'];
				$r[$unitID]['history']['min'] = $row['MIN(value)'];
				$r[$unitID]['history']['avg'] = $row['AVG(value)'];


				// Insert to current value
				$query2 = "UPDATE msh_devices_current_values SET 
							max='{$row['MAX(value)']}',
							min='{$row['MIN(value)']}',
							avg='{$row['AVG(value)']}'
						  WHERE device_int_id='$deviceIntID' AND unit_id='$unitID'";
				$result2 = $this->mysqli->query($query2);

				if ($result2) {
					$r[$unitID]['message'] = 'History values updated';
				} else {
					$r[$unitID]['message'] = 'Error updating history values';
				}



				/*
					Cleanup
					If MAX AND MIN = 0, assume log entry for this unit on this device is an error
					Delete all this entries...
				*/
				if ($row['MAX(value)'] == 0 && $row['MIN(value)'] == 0) {
					$query2 = "DELETE FROM msh_devices_log WHERE device_int_id='$deviceIntID' AND unit_id='$unitID' AND value='0'";
					$result2 = $this->mysqli->query($query2);

					$query2 = "DELETE FROM msh_devices_has_units WHERE device_int_id='$deviceIntID' AND unit_id='$unitID'";
					$result2 = $this->mysqli->query($query2);
				}
			}
		}

		return $r;
	}










	function getDeviceUnits($device_int_id, $update = false)
	{
		$r = array();

		

		$query = "SELECT * 
				  FROM msh_devices_has_units 
				  WHERE device_int_id='$device_int_id'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;


		// Update device units
		if ($update || $numRows == 0) {
			$this->updateDeviceUnits($device_int_id);
		}


		$units = array();
		while ($row = $result->fetch_array()) {
			$units[] = $row['unit_id'];
		}

		$getUnits = $this->getUnits();

		foreach ($units as $key => $unitID) {
			$r[$unitID] = $getUnits[$unitID];
		}

		return $r;
	}



	function updateDeviceUnits($device_int_id)
	{

		$r = array();


		// Delete old units
		$query = "DELETE FROM msh_devices_has_units WHERE device_int_id='$device_int_id'";
		$result = $this->mysqli->query($query);


		// Fetch new units from log table
		$query = "SELECT * 
				  FROM msh_devices_log 
				  WHERE device_int_id='$device_int_id'
				  GROUP BY unit_id";
		$result = $this->mysqli->query($query);

		$units = array();
		while ($row = $result->fetch_array()) {
			$units[] = $row['unit_id'];

			$addUnit = $this->addUnit2device($device_int_id, $row['unit_id']);
		}


		// Build device units with unit-data
		$getUnits = $this->getUnits();

		foreach ($units as $key => $unitID) {
			$r[$unitID] = $getUnits[$unitID];
		}

		return $r;
	}



	/**
	 * Add new units to device
	*/
	function addUnit2device($device_int_id, $unitID)
	{
		$query = "INSERT INTO msh_devices_has_units SET device_int_id='$device_int_id', unit_id='$unitID'";
		$result = $this->mysqli->query($query);
	}





	function getUnits()
	{
		$r = array();

		if (USE_MEMCACHE == true) {
			$key = md5('deviceUnits'); // Unique Words
			$r = $this->memcache->get($key); // Memcached object
		}


		if(!$r) // If no cached data avalible, get fresh data
		{

			$query = "SELECT * FROM msh_units";
			$result = $this->mysqli->query($query);
			//$numRows = $result->num_rows;

			while ($row = $result->fetch_array()) {
				$r[$row['unit_id']]['id'] = $row['unit_id'];
				$r[$row['unit_id']]['title'] = $row['unit_title'];
				$r[$row['unit_id']]['tag'] = $row['unit_short_tag'];
				$r[$row['unit_id']]['type'] = $row['type'];
				$r[$row['unit_id']]['icon'] = $row['icon'];
			}

			if (USE_MEMCACHE == true) {
				$this->memcache->set($key, $r, MEMCACHE_COMPRESSED, (60*60*24*30)); // 2592000 = 1 month
			}
		}
	
		return $r;
	}




	function getDevicesByUnit($unitID, $fullDeviceData = false)
	{
		$r = array();

		// Go num days back in time in log
		// Set a short time-period to prevent large DB search
		$daysBackInTime = 1;


		$dateFrom = ( time() - (86400 * $daysBackInTime) );
		$dateTo = time();

		$query = "SELECT 
					L.device_int_id,
					D.device_name
				  FROM msh_devices_log AS L
				  INNER JOIN msh_devices AS D ON D.device_int_id = L.device_int_id
				  WHERE L.unit_id='$unitID' 
				  	AND (L.time BETWEEN '$dateFrom' AND '$dateTo')
				  	AND D.user_id='{$this->thisUser['user_id']}'
				  GROUP BY L.device_int_id";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			if ($fullDeviceData) {
				$r[$row['device_int_id']] = $this->getDevice($row['device_int_id']);
			}

			else {
				$r[$row['device_int_id']]['name'] = $row['device_name'];
			}
		}

		return $r;
	}



	function getDeviceCategories($deviceID)
	{
		$r = array();

		$query = "SELECT * 
				  FROM msh_devices_has_category as hasCat
				  INNER JOIN msh_categories as Cat ON Cat.cat_id = hasCat.category_id
				  WHERE hasCat.device_int_id='$deviceID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['category_id']]['name'] = $row['name'];
		}

		return $r;
	}




	function getMethods()
	{
		$r = array();

		$query = "SELECT *
				  FROM msh_methods";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['m_id']]['method_id'] = $row['m_id'];
			$r[$row['m_id']]['title'] = $row['title'];
			$r[$row['m_id']]['cmd'] = $row['cmd'];
			$r[$row['m_id']]['support_value'] = $row['support_value'];
		}

		return $r;
	}



	function getSupportedMethods($deviceID)
	{
		$r = array();

		$query = "SELECT 
					M.m_id, 
					M.title, 
					M.cmd, 
					M.support_value 
				  FROM msh_devices_has_methods as hasM
				  INNER JOIN msh_methods AS M ON hasM.m_id = M.m_id
				  WHERE hasM.d_id='$deviceID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['m_id']]['method_id'] = $row['m_id'];
			$r[$row['m_id']]['title'] = $row['title'];
			$r[$row['m_id']]['cmd'] = $row['cmd'];
			$r[$row['m_id']]['support_value'] = $row['support_value'];
		}

		return $r;
	}

	
	
	function getInternalID($deviceExtID)
	{
		global $mysqli;

		$result = $mysqli->query("SELECT device_int_id FROM msh_devices WHERE device_ext_id='$deviceExtID'");
		$row = $result->fetch_array();

		return $row['device_int_id'];
	}



	function getExternalID($deviceIntID)
	{
		global $mysqli;

		$result = $mysqli->query("SELECT device_ext_id FROM msh_devices WHERE device_int_id='$deviceIntID'");
		$row = $result->fetch_array();

		return $row['device_ext_id'];
	}




	function addDevice($params) {
		global $mysqli;

		$r = array();
		$error = false;

		if (empty($params['user_id'])) {
			$error = true;
		}
		if (empty($params['module'])) {}
		if (empty($params['binding'])) {}
		if (empty($params['device_ext_id'])) {}
		if (empty($params['device_name'])) {}
		if (empty($params['device_alias'])) {}
		if (empty($params['type'])) {}
		if (empty($params['type_desc'])) {}
		if (empty($params['category'])) {}
		if (empty($params['description'])) {}
		if (empty($params['url'])) {}
		if (empty($params['icon'])) {}
		if (empty($params['latitude'])) {}
		if (empty($params['longitude'])) {}
		if (empty($params['value_unit'])) {}
		if (empty($params['value2_unit'])) {}
		if (empty($params['value3_unit'])) {}
		if (empty($params['battery'])) {}
		if (empty($params['dashboard'])) {}
		if (empty($params['dashboard_size'])) {}
		if (empty($params['monitor'])) {}
		if (empty($params['public'])) {}
		if (empty($params['deactive'])) {}

		/*echo "<pre>";
			print_r($params);
		echo "</pre>";*/

		if (!empty($params['latitude'])) $params['latitude'] = str_replace(',', '.', $params['latitude']);
		if (!empty($params['longitude'])) $params['longitude'] = str_replace(',', '.', $params['longitude']);

		//echo "Error: $error <br />";

		if (!$error) {
			$query = "INSERT INTO msh_devices SET 
						user_id='".$params['user_id']."', 
						module='".$params['module']."', 
						binding='".$params['binding']."', 
						device_ext_id='".$params['device_ext_id']."', 
						device_name='".$params['device_name']."', 
						device_alias='".$params['device_alias']."', 
						type='".$params['type']."', 
						type_desc='".$params['type_desc']."', 
						category='".$params['category']."', 
						description='".$params['description']."', 
						description2='".$params['description2']."', 
						icon='".$params['icon']."', 
						url='".$params['url']."', 
						latitude='".$params['latitude']."', 
						longitude='".$params['longitude']."', 
						value_unit='".$params['value_unit']."', 
						value2_unit='".$params['value2_unit']."', 
						value3_unit='".$params['value3_unit']."', 
						dashboard='".$params['dashboard']."', 
						dashboard_size='".$params['dashboard_size']."', 
						monitor='".$params['monitor']."', 
						public='".$params['public']."', 
						deactive='".$params['deactive']."'";
			//echo "query: $query <br />";
			$result = $mysqli->query($query);

			if ($result) {
				$deviceID = $mysqli->insert_id;

				$r['status'] = 'success';
				$r['device_id'] = $deviceID;
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could not write to database';
			}

		}

		else {
			$r['status'] = 'error';
			$r['message'] = 'Required parameters are missing';
		}

		return $r;
	}





	function updateDevice($p) {
		$r = array();

		if (!empty($p['device_int_id'])) // Only continue if device int id is not empty
		{

			//if (isset($p['device_int_id'])) 	$q[] = "device_int_id='{$p['device_int_id']}'";
			if (isset($p['user_id'])) 			$q[] = "user_id='{$p['user_id']}'";
			if (isset($p['module'])) 			$q[] = "module='{$p['module']}'";
			if (isset($p['binding'])) 			$q[] = "binding='{$p['binding']}'";
			if (isset($p['device_ext_id'])) 	$q[] = "device_ext_id='{$p['device_ext_id']}'";
			if (isset($p['device_name'])) 		$q[] = "device_alias='{$p['device_name']}'";
			if (isset($p['device_alias'])) 		$q[] = "device_alias='{$p['device_alias']}'";
			if (isset($p['type'])) 				$q[] = "type='{$p['type']}'";
			if (isset($p['type_desc'])) 		$q[] = "type_desc='{$p['type_desc']}'";
			if (isset($p['category'])) 			$q[] = "category='{$p['category']}'";
			if (isset($p['description'])) 		$q[] = "description='{$p['description']}'";
			if (isset($p['url'])) 				$q[] = "url='{$p['url']}'";
			if (isset($p['latitude'])) 			$q[] = "latitude='{$p['latitude']}'";
			if (isset($p['longitude'])) 		$q[] = "longitude='{$p['longitude']}'";
			if (isset($p['value_unit'])) 		$q[] = "value_unit='{$p['value_unit']}'";
			if (isset($p['value2_unit'])) 		$q[] = "value2_unit='{$p['value2_unit']}'";
			if (isset($p['value3_unit'])) 		$q[] = "value3_unit='{$p['value3_unit']}'";
			if (isset($p['battery'])) 			$q[] = "battery='{$p['battery']}'";
			if (isset($p['state'])) 			$q[] = "state='{$p['state']}'";
			if (isset($p['dashboard'])) 		$q[] = "dashboard='{$p['dashboard']}'";
			if (isset($p['dashboard_size'])) 	$q[] = "dashboard_size='{$p['dashboard_size']}'";
			if (isset($p['monitor'])) 			$q[] = "monitor='{$p['monitor']}'";
			if (isset($p['public'])) 			$q[] = "public='{$p['public']}'";
			if (isset($p['deactive'])) 			$q[] = "deactive='{$p['deactive']}'";


			// Build query
			$numQ = count($q);
			if ($numQ > 0) {
				$c = 0;
				$qSet = "";
				foreach ($q as $key => $qData) {
					$qSet .= $qData;

					$c++;
					if ($c < $numQ) $qSet .= ", "; // Add comma if multiple query-parameters
				}

				// Execute query
				$query = "UPDATE msh_devices SET $qSet WHERE device_int_id='{$p['device_int_id']}'";
				$result = $this->mysqli->query($query);

				if ($result) {
					$r['status'] = 'success';
				} else {
					$r['status'] = 'error';
					$r['message'] = 'DB error. Could not execute query in database.';
				}
			} //end-if-numQ

			else {
				//$r['status'] = 'error';
				//$r['message'] = 'No parameters sent to update database';
				//$r['parameters'] = $p;

				$err01 = true;
			}


			// Add/update methods
			if (isset($p['methods'])) {

				// Delete old categories
				$query = "DELETE FROM msh_devices_has_methods WHERE d_id='".$p['device_int_id']."'";
				$result = $this->mysqli->query($query);


				// Insert new categories
				foreach ($p['methods'] as $key => $methodID) {
					$query = "INSERT INTO msh_devices_has_methods SET 
								d_id='".$p['device_int_id']."', 
								m_id='".$methodID."'";
					$result = $this->mysqli->query($query);

					$r['methods_added'][$methodID] = $result;
				}
			} else {
				//$err02 = true;
			}



			// Add/update categories
			if (isset($p['categories'])) {

				// Delete old categories
				$query = "DELETE FROM msh_devices_has_category WHERE device_int_id='".$p['device_int_id']."'";
				$result = $this->mysqli->query($query);


				// Insert new categories
				foreach ($p['categories'] as $key => $catID) {
					$query = "INSERT INTO msh_devices_has_category SET 
								device_int_id='".$p['device_int_id']."', 
								category_id='".$catID."'";
					$result = $this->mysqli->query($query);

					$r['categories_added'][$catID] = $result;
				}
			} else {
				$err02 = true;
			}


			// Status return
			if ($err01 && $err02) {
				$r['status'] = 'error';
				$r['message'] = 'No parameters sent to update database';
				$r['parameters'] = $p;
			} else {
				$r['status'] = 'success';
			}



		} //end-if-deviceID-not-empty

		else {
			$r['status'] = 'error';
			$r['message'] = 'Device Internal ID not included or is empty in the parameters';
			$r['parameters'] = $p;
		}

		return $r;

	} //end-function




	/*function addLog($params) {
		//global $mysqli;


		$error = false;

		if (empty($params['device_int_id'])) {}
		if (empty($params['user_id'])) {}
		if (empty($params['time'])) {
			$params['time'] = time();
		}
		if (empty($params['type'])) {}
		if (empty($params['value'])) {}
		if (empty($params['value2'])) {}
		if (empty($params['value3'])) {}
		if (empty($params['ref'])) {}


		$query = "INSERT INTO msh_data_log SET 
			device_int_id='".$params['device_int_id']."', 
			user_id='".$params['user_id']."', 
			time='".$params['time']."', 
			type='".$params['type']."', 
			value='".$params['value']."', 
			value2='".$params['value2']."', 
			value3='".$params['value3']."', 
			ref='".$params['ref']."'";
		$result = $this->mysqli->query($query);

		return $result;
	}*/




	function addLog($params) {
		//global $mysqli;

		$r = array();

		if (empty($params['device_int_id'])) {
			$r['status'] = 'error';
			$r['message'] = 'Device INT ID missing';
			return $r;
		}

		if (empty($params['time'])) {
			$params['time'] = time();
		}

		//if (empty($params['unit_id'])) {}
		//if (empty($params['value'])) {}


	
		$query = "INSERT INTO msh_devices_log SET 
			device_int_id='".$params['device_int_id']."', 
			time='".$params['time']."', 
			unit_id='".$params['unit_id']."', 
			value='".$params['value']."'";
		$result = $this->mysqli->query($query);

		//$r['query'] = $query;

		if ($result) {
			$r['status'] = 'success';

			// Set device current state
			if (isset($params['state'])) {
				$query = "UPDATE msh_devices SET state='".$params['state']."' WHERE device_int_id='".$params['device_int_id']."'";
				$result = $this->mysqli->query($query);
			}

		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB Error';
			$r['db_error'] = $this->mysqli->error;
		}

		//$result->close();

		return $r;
	}




	function deleteDevice($intID, $userID = 0)
	{
		$r = array();

		if (empty($intID)) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}

		// Check user/owner and access
		$getDevice = $this->getDevice($intID);

		if (empty($userID)) {
			$userID = $this->thisUser['user_id'];
		}

		if ($getDevice['user']['user_id'] != $userID || $this->thisUser['system_admin'] != 1) {
			$r['status'] = 'error';
			$r['message'] = 'Device does not exist or no acces to the device';
			return $r;
		}


		// Delete the device
		$query = "DELETE FROM msh_devices WHERE device_int_id='$intID'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';

			// Delete log
			$query = "DELETE FROM msh_data_log WHERE device_int_id='$intID'";
			$result = $this->mysqli->query($query);

			// Delete log
			$query = "DELETE FROM msh_devices_log WHERE device_int_id='$intID'";
			$result = $this->mysqli->query($query);

			// Delete has categories
			$query = "DELETE FROM msh_devices_has_category WHERE device_int_id='$intID'";
			$result = $this->mysqli->query($query);

			// Delete has methods
			$query = "DELETE FROM msh_devices_methods WHERE device_int_id='$intID'";
			$result = $this->mysqli->query($query);


		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error. Could not execute query in database.';
		}

		return $r;
	}

}

?>