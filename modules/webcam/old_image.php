<?php
	if (isset($_GET['id'])) $getID = clean($_GET['id']);

	$getDevice = $objDevices->getDevice($getID);
?>

<h1><i class="fa fa-video-camera"></i> <?php echo $getDevice['name']; ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=climate"><?php echo _('Webcam'); ?></a></li>
	<li class="active"><?php echo $getDevice['name']; ?></li>
</ol>


<a class="btn btn-default" href="javascript: history.go(-1)">
	<span class="glyphicon glyphicon-chevron-left" style="margin-right:6px;"></span>
	<?php echo _('Back'); ?>
</a>

<br />
<br />


<?php
	
	/* Show camera image
	--------------------------------------------------------------------------- */
	$query = "SELECT * 
			  FROM msh_devices 
			  WHERE module='webcam'
			  	AND user_id='{$thisUser['user_id']}'
			  	AND device_int_id='$getID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	while ($row = $result->fetch_array()) {

		if ($row['type_desc'] == "url") {
			echo "<img style='width:100%;' src='{$row['url']}'>";
		}

		elseif ($row['type_desc'] == "folder") {
			$matches = array();
			preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($row['url']), $matches);
			echo "<img style='width:100%;' src='" . $row['url'] . end($matches[2]) . "'>";
		}
	}


?>