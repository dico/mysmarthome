<?php

	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);
	if (isset($_GET['view'])) $view = clean($_GET['view']);



	/* Device data
	--------------------------------------------------------------------------- */
	$result = $mysqli->query("SELECT * FROM msh_devices WHERE device_int_id='$getID'");
	$numRows = $result->num_rows;
	$deviceData = $result->fetch_array();
?>


<h1><?php echo _('Add webcam'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=webcam"><i class="fa fa-video-camera"></i> <?php echo _('Webcam'); ?></a></li>
	<li class="active"><?php echo _('Add webcam'); ?></li>
</ol>


<form action="?m=webcam&page=execute&action=add" method="POST">
	<div class="form-group">
		<label for="exampleInputEmail1"><?php echo _('Name'); ?></label>
		<input type="text" class="form-control" id="inputDeviceName" name="inputDeviceName" placeholder="" value="<?php echo $deviceData['device_name']; ?>" />
	</div>

	<div class="form-group">
		<label for="exampleInputPassword1"><?php echo _('URL'); ?></label>
		<input type="text" class="form-control" id="inputDeviceURL" name="inputDeviceURL" placeholder="http://mywebcam.no/webcamimage.jpg" value="<?php echo $deviceData['device_name']; ?>" />
	</div>

	<div class="checkbox">
		<label style='margin-right:35px;'>
			<input type='radio' name='inputDeviceTypeDesc' id='inputDeviceTypeDescURL' value='url' checked='checked' /> <?php echo _('URL'); ?> (<?php echo _('Web-url'); ?>)
		</label>
		<label>
			<input type='radio' name='inputDeviceTypeDesc' id='inputDeviceTypeDescFolder' value='folder' /> <?php echo _('Folder'); ?> (<?php echo _('Grab the newest file in folder'); ?>)
		</label>
	</div>

	<button type="submit" class="btn btn-primary"><?php echo _('Save webcam'); ?></button>
</form>