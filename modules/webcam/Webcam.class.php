<?php
namespace Webcam;


/**
* 
*/
class Webcam
{
	
	function __construct()
	{
		
	}


	function getWebcamURL($intID)
	{
		global $mysqli;
		global $thisUser;

		$url = '';

		$query = "SELECT * 
				  FROM msh_devices 
				  WHERE module='webcam'
				  	AND user_id='{$thisUser['user_id']}'
				  	AND device_int_id='$intID'";
		//echo $query;
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {

			if ($row['type_desc'] == "url") {
				$url = $row['url'];
			}

			elseif ($row['type_desc'] == "folder") {
				$matches = array();
				preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($row['url']), $matches);
				$url = $row['url'] . end($matches[2]);
			}
		}

		return $url;
	}
}


?>