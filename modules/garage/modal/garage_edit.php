<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );


	// Get category Garage doors devices
	// Devices parameters
	$p = array (
		'categories' => array(7),
	);
	$getDevices = $objDevices->getDevices($p);


	// Get category Camera devices
	// Devices parameters
	$p = array (
		'categories' => array(12),
	);
	$getWebcams = $objDevices->getDevices($p);
	$numWebcams = count($getWebcams);




	// Check if new or edit
	if (isset($_GET['id'])) {
		$getID = clean($_GET['id']);

		$getDoors = $objModuleGarage->getGarageDoors();
		$door = $getDoors[$getID];

		$headline = _('Edit garagedoor');
		$formAction = URL . "modules/garage/Garage_handler.php?action=editGarageDoor&id=$getID";
	}

	else {
		$headline = _('Add garagedoor');
		$formAction = URL . "modules/garage/Garage_handler.php?action=addGarageDoor";
	}
?>



<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $headline; ?></h4>
</div>


<form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data">
	<div class="modal-body">


		<div class="form-group">
			<label for="inputTitle"><?php echo _('Title'); ?></label>
			<input type="text" class="form-control" id="inputTitle" name="inputTitle" placeholder="<?php echo _('Title'); ?>" value="<?php echo $door['title']; ?>" />
		</div>

		<div class="form-group">
			<label for="selectMotorID"><?php echo _('Select motor device'); ?></label>
			
			<select class="form-control" name="selectMotorID" id="selectMotorID">
				<option value="">-- <?php echo _('Select motor device'); ?></option>
				<?php foreach ($getDevices as $intID => $dData): ?>
					<?php if ($door['motor']['deviceIntID'] == $intID): ?>
						<option value="<?php echo $intID; ?>" selected="selected"><?php echo $dData['name']; ?></option>
					<?php else: ?>
						<option value="<?php echo $intID; ?>"><?php echo $dData['name']; ?></option>
					<?php endif ?>
				<?php endforeach ?>
			</select>

		</div>

		<div class="form-group">
			<label for="selectStatusID"><?php echo _('Select status device'); ?></label>
			
			<select class="form-control" name="selectStatusID" id="selectStatusID">
				<option value="">-- <?php echo _('Select status device'); ?></option>
				<?php foreach ($getDevices as $intID => $dData): ?>
					<?php if ($door['sensor']['deviceIntID'] == $intID): ?>
						<option value="<?php echo $intID; ?>" selected="selected"><?php echo $dData['name']; ?></option>
					<?php else: ?>
						<option value="<?php echo $intID; ?>"><?php echo $dData['name']; ?></option>
					<?php endif ?>
				<?php endforeach ?>
			</select>
		</div>

		<div class="form-group">
			<label for="inputDoorOpenValue"><?php echo _('Garagedoor open value'); ?></label>
			<input type="text" class="form-control" id="inputDoorOpenValue" name="inputDoorOpenValue" placeholder="0/1" value="<?php echo $door['sensor_value_open']; ?>" />
		</div>

		<div class="form-group">
			<label for="inputDoorClosedValue"><?php echo _('Garagedoor closed value'); ?></label>
			<input type="text" class="form-control" id="inputDoorClosedValue" name="inputDoorClosedValue" placeholder="0/1" value="<?php echo $door['sensor_value_closed']; ?>" />
		</div>

		<?php
			$uploadPath = ABSPATH . 'data/garage/images/';
			if (!file_exists($uploadPath)) {
				$result = @mkdir($uploadPath, 0755, true);

				if (!$result) {
					echo '<div style="color:red;">';
						echo _('Could not write to ./data folder!') . '<br />';
						echo _('To upload files, you need to set writable permissions the ./data folder.') . '<br />';
					echo '</div>';
				}
			}
		?>

		<div class="form-group">
			<label for="fileImgDoorClosed"><?php echo _('Image for closed door'); ?></label>
			<input type="file" class="form-control" name="fileImgDoorClosed" id="fileImgDoorClosed">
			<p class="info">
				<?php echo _('Not required. If empty, previous image or default will be used.'); ?><br />
				<?php echo _('Please make sure your images does not exeed the max filesize in php.ini. Keep them small for faster page load'); ?> :-)<br />
			</p>
		</div>

		<div class="form-group">
			<label for="fileImgDoorOpen"><?php echo _('Image for open door'); ?></label>
			<input type="file" class="form-control" name="fileImgDoorOpen" id="fileImgDoorOpen">
			<p class="info">
				<?php echo _('Not required. If empty, previous image or default will be used.'); ?><br />
				<?php echo _('Please make sure your images does not exeed the max filesize in php.ini. Keep them small for faster page load'); ?> :-)<br />
			</p>
		</div>


		<?php if ($numWebcams > 0): ?>	
			<div class="form-group">
				<label for="selectWebcam"><?php echo _('Webcam'); ?></label>
				
				<select class="form-control" name="selectWebcam" id="selectWebcam">
					<option value="">-- <?php echo _('Select webcam'); ?></option>
					<?php foreach ($getWebcams as $intID => $dData): ?>
						<?php if ($door['webcam']['deviceIntID'] == $intID): ?>
							<option value="<?php echo $intID; ?>" selected="selected"><?php echo $dData['name']; ?></option>
						<?php else: ?>
							<option value="<?php echo $intID; ?>"><?php echo $dData['name']; ?></option>
						<?php endif ?>
					<?php endforeach ?>
				</select>
			</div>
		<?php endif ?>



		


	</div> <!-- end modal-body -->


	<div class="modal-footer">
		<button type="submit" class="btn btn-primary"><?php echo _('Save'); ?></button>
	</div>
</form>