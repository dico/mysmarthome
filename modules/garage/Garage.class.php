<?php
namespace Garage;


/**
* 
*/
class Garage
{
	
	function __construct()
	{
		
	}


	function getGarageDoors()
	{
		global $mysqli;
		global $thisUser;
		global $objDevices;
		$r = array();


		// Motor device
		$query = "SELECT * FROM msh_garage WHERE user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['garage_id']]['user_id'] = $row['user_id'];
			$r[$row['garage_id']]['title'] = $row['title'];
			$r[$row['garage_id']]['motor'] = $objDevices->getDevice($row['motor_int_id']);
			$r[$row['garage_id']]['sensor'] = $objDevices->getDevice($row['status_int_id']);
			$r[$row['garage_id']]['sensor_value_open'] = $row['status_value_open'];
			$r[$row['garage_id']]['sensor_value_closed'] = $row['status_value_closed'];
			$r[$row['garage_id']]['img_door_open'] = $row['img_door_open'];
			$r[$row['garage_id']]['img_door_closed'] = $row['img_door_closed'];
			$r[$row['garage_id']]['webcam'] = $objDevices->getDevice($row['webcam_device_int_id']);
		}

		return $r;

	}


	function addGarageDoor($p)
	{
		global $mysqli;
		global $thisUser;
		$r = array();

		if (!isset($p['user_id']) || empty($p['user_id']))
			$p['user_id'] = $thisUser['user_id'];


		// Upload file if any
		if (!empty($p['img_closed']['name']))
			$filenameClosed = $this->uploadImage($p['img_closed']);

		if (!empty($p['img_open']['name']))
			$filenameOpen 	= $this->uploadImage($p['img_open']);


		$r['parameters'] = $p;
		$r['parameters']['upload_status']['closed'] = $filenameClosed;
		$r['parameters']['upload_status']['open'] = $filenameOpen;

		$query = "INSERT INTO msh_garage SET 
					user_id='".$p['user_id']."', 
					title='".$p['title']."', 
					motor_int_id='".$p['motor_int_id']."', 
					status_int_id='".$p['status_int_id']."', 
					status_value_open='".$p['status_value_open']."', 
					status_value_closed='".$p['status_value_closed']."', 
					img_door_open='".$filenameOpen['filename']."', 
					img_door_closed='".$filenameClosed['filename']."', 
					webcam_device_int_id='".$p['webcam']."'";
		$result = $mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	function editGarageDoor($p)
	{
		global $mysqli;
		global $thisUser;
		$r = array();


		if (!isset($p['id']) || empty($p['id'])) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}


		// Upload file if any
		if (!empty($p['img_closed']['name']))
			$filenameClosed = $this->uploadImage($p['img_closed']);

		if (!empty($p['img_open']['name']))
			$filenameOpen 	= $this->uploadImage($p['img_open']);


		$r['parameters'] = $p;
		$r['parameters']['upload_status']['closed'] = $filenameClosed;
		$r['parameters']['upload_status']['open'] = $filenameOpen;

		$query = "UPDATE msh_garage SET 
					title='".$p['title']."', 
					motor_int_id='".$p['motor_int_id']."', 
					status_int_id='".$p['status_int_id']."', 
					status_value_open='".$p['status_value_open']."', 
					status_value_closed='".$p['status_value_closed']."', 
					webcam_device_int_id='".$p['webcam']."'
					WHERE garage_id='".$p['id']."' AND user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);

		if (!empty($filenameOpen['filename'])) {
			$query = "UPDATE msh_garage SET 
						img_door_open='".$filenameOpen['filename']."'
						WHERE garage_id='".$p['id']."' AND user_id='{$thisUser['user_id']}'";
			$result_img_open = $mysqli->query($query);
		} else {
			$r['status_img_open'] = 'Not provided';
		}

		if (!empty($filenameClosed['filename'])) {
			$query = "UPDATE msh_garage SET 
						img_door_closed='".$filenameClosed['filename']."'
						WHERE garage_id='".$p['id']."' AND user_id='{$thisUser['user_id']}'";
			$result_img_open = $mysqli->query($query);
		} else {
			$r['status_img_closed'] = 'Not provided';
		}

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error or not this users door';
		}

		return $r;
	}




	function uploadImage($file)
	{
		global $thisUser;

		// Settings
		$uploadPath = ABSPATH . 'data/garage/images/';
		$imgExtAllowed = array('jpg', 'jpeg', 'png');


		$r['file_array'] = $file;


		if (!empty($file['name'])) // Start upload if file exists
		{
			$r = array();

			// Get file extention
			$ext = pathinfo(basename($file["name"]), PATHINFO_EXTENSION);
			$r['file_extention'] = $ext;

			// Generate new filename
			$filename = 'garagedoor_' . $thisUser['user_id'].'_'.rand(1111,9999) . '.' . $ext;
			$r['filename'] = $filename;

			// Upload path with new filename
			$uploadPathFile = $uploadPath . $filename;
			$r['upload_path'] = $uploadPathFile;

			// Check if image
			$check = getimagesize($file["tmp_name"]);
			if($check !== false) // Image MIME OK
			{
				$uploadOk = 1;
				$r['check_image'] = 'success';
			} 
			else // Not an image
			{
				$uploadOk = 0;
				$r['check_image'] = 'error';
			}

			// Check if file has an allowed extention
			if (in_array($ext, $imgExtAllowed)) {
				$uploadOk = 1;
				$r['check_image_ext'] = 'success';
			} else {
				$uploadOk = 0;
				$r['check_image_ext'] = 'error';
			}

			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$r['status'] = 'error';
				$r['message'] = 'Some checks returned an error';
			} 

			// if everything is ok, try to upload file
			else {
				if (move_uploaded_file($file["tmp_name"], $uploadPathFile)) {
					$r['status'] = 'success';
				} else {
					$r['status'] = 'error';
					$r['message'] = 'Could not upload the file';
				}
			}

		} //end-if-!empty-filename

		else {
			$r['status'] = 'error';
			$r['message'] = 'Filename empty';
		}

		return $r;
	} //end-class-function







	function deleteGarageDoor($id)
	{
		global $mysqli;
		global $thisUser;
		$r = array();

		// Check if ID exists
		if (empty($id)) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}

		// Get current garage door
		$getGarageDoors = $this->getGarageDoors();
		$thisDoor = $getGarageDoors[$id];

		// Delete from DB
		$query = "DELETE FROM msh_garage WHERE garage_id='$id' AND user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);


		// Delete image files if exists
		$path = ABSPATH . 'data/garage/images/';
		if (!empty($thisDoor['img_door_open']) && file_exists($path . $thisDoor['img_door_open'])) {
			unlink($path . $thisDoor['img_door_open']);
		}
		if (!empty($thisDoor['img_door_closed']) && file_exists($path . $thisDoor['img_door_closed'])) {
			unlink($path . $thisDoor['img_door_closed']);
		}


		// Return status
		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}







	function removeOpenDoorImage($id)
	{
		global $mysqli;
		global $thisUser;
		$r = array();


		// Check if ID exists
		if (empty($id)) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}

		// Get current garage door
		$getGarageDoors = $this->getGarageDoors();
		$thisDoor = $getGarageDoors[$id];

		// Remove from DB
		$query = "UPDATE msh_garage SET img_door_open='' WHERE garage_id='$id' AND user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);

		// Delete file
		$path = ABSPATH . 'data/garage/images/';
		unlink($path . $thisDoor['img_door_open']);

		// Return status
		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error or not this users door';
		}

		return $r;
	}






	function removeClosedDoorImage($id)
	{
		global $mysqli;
		global $thisUser;
		$r = array();

		// Check if ID exists
		if (empty($id)) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}

		// Get current garage door
		$getGarageDoors = $this->getGarageDoors();
		$thisDoor = $getGarageDoors[$id];

		// Remove from DB
		$query = "UPDATE msh_garage SET img_door_closed='' WHERE garage_id='$id' AND user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);

		// Delete file
		$path = ABSPATH . 'data/garage/images/';
		unlink($path . $thisDoor['img_door_open']);

		// Return status
		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error or not this users door';
		}

		return $r;
	}


}


?>