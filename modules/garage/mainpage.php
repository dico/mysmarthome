<script type="text/javascript" src="<?php echo MODULES_URL; ?>garage/js/garage.js"></script>

<h1><?php echo _('Garage'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><?php echo _('Garage'); ?></li>
</ol>

<?php
	$getDoors = $objModuleGarage->getGarageDoors();
	$numDoors = count($getDoors);	
?>







<div style="float:right; margin-top:-70px;">
	<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cog"></i> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li>
				<a href="<?php echo URL; ?>modules/garage/modal/garage_edit.php" data-toggle="modal" data-target="#modal">
					<i class="fa fa-fw fa-plus"></i> <?php echo _('Add new garagedoor'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo URL; ?>modules/garage/modal/help.php" data-toggle="modal" data-target="#modal">
					<i class="fa fa-fw fa-question-circle"></i> <?php echo _('Help'); ?>
				</a>
			</li>
			<?php if ($numDoors > 0): ?>
				<li class="divider"></li>
				<?php foreach ($getDoors as $doorID => $dData): ?>
					<li role="presentation" class="dropdown-header"><?php echo $dData['title']; ?></li>
					<li>
						<a href="<?php echo URL; ?>modules/garage/modal/garage_edit.php?id=<?php echo $doorID; ?>" data-toggle="modal" data-target="#modal">
							<i class="fa fa-fw fa-edit"></i> <?php echo _('Edit'); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo URL; ?>modules/garage/Garage_handler.php?action=deleteGarageDoor&id=<?php echo $doorID; ?>">
							<i class="fa fa-fw fa-trash"></i> <?php echo _('Delete'); ?>
						</a>
					</li>
					<?php if (!empty($dData['img_door_open'])): ?>
						<li>
							<a href="<?php echo URL; ?>modules/garage/Garage_handler.php?action=removeOpenDoorImage&id=<?php echo $doorID; ?>">
								<i class="fa fa-fw fa-close"></i> <?php echo _('Remove open door image'); ?>
							</a>
						</li>
					<?php endif ?>
					<?php if (!empty($dData['img_door_closed'])): ?>
						<li>
							<a href="<?php echo URL; ?>modules/garage/Garage_handler.php?action=removeClosedDoorImage&id=<?php echo $doorID; ?>">
								<i class="fa fa-fw fa-close"></i> <?php echo _('Remove closed door image'); ?>
							</a>
						</li>
					<?php endif ?>

				<?php endforeach; ?>
			<?php endif ?>
		</ul>
	</div>
</div>








<?php
	if ($numDoors > 0) {

		// DOORS
		echo '<div class="row">';
			foreach ($getDoors as $doorID => $dData) {


				$state = $dData['sensor']['state'];

				if ($state == 1) {
					$doorStyle[$dData['motor_int_id']] = 'none'; // none port = open
					$label = '<span style="margin-left:15px;" class="label label-success">'._('Open').'</span>';
				}
				elseif ($state == 0) {
					$doorStyle[$dData['motor_int_id']] = 'show'; // show port = closed
					$label = '';
				}


				if (!empty($dData['img_door_open'])) {
					$img_door_open = 'background:url(\''.URL . 'data/garage/images/' . $dData['img_door_open'] . '\') !important;';
				}
				if (!empty($dData['img_door_closed'])) {
					$img_door_closed = 'background:url(\''.URL . 'data/garage/images/' . $dData['img_door_closed'] . '\') !important;';

				}


				echo '<div class="col-xs-6 col-sm-6 col-md-6">';
					echo '<div class="garage-doors">';

						echo '<div>';
							echo '<h3 style="display:inline-block;">';
								echo '<span>'.$dData['title'].'</span>';
							echo '</h3>';
							echo $label;
						echo '</div>';

						echo '<div class="garage-door-close" style="display:'.$doorStyle[$dData['motor_int_id']].'; !important; '.$img_door_closed.' background-size: 100% 100% !important;" id="garage-door-close-'.$dData['motor']['deviceIntID'].'"></div>';
						echo '<div class="garage-door-open" style="'.$img_door_open.' background-size: 100% 100% !important;"></div>';

						echo '<div class="garage-doors-link">';
							echo '<a href="javascript:toggleGarageDoors(\''.$dData['motor']['deviceIntID'].'\', \''.$dData['motor']['deviceExtID'].'\');">';
								//echo $dData['title'];
							echo '</a>';
						echo '</div>';

						echo '<div class="clearfix"></div>';
					echo '</div>';
				echo '</div>';

			} //end-foreach-garage-doors

		echo '</div>';



		// WEBCAM
		echo '<div class="row">';
			foreach ($getDoors as $doorID => $dData) {
				if (!empty($dData['webcam'])) {
					echo '<div class="col-xs-6 col-sm-6 col-md-6">';
						$webcamURL = $objBindingWebcam->getWebcamURL($dData['webcam']['deviceIntID']);
						echo '<a href="'.URL.'core/includes/modal/device.php?id='.$dData['webcam']['deviceIntID'].'" data-toggle="modal" data-target="#modal">';
							echo '<img class="garage-webcam-refresh" style="width:100%;" data-url="'.$webcamURL.'" src="'.$webcamURL.'" />';
							//echo 'URL: ' .$webcamURL . '<br />';
							//echo 'ID: ' . $dData['webcam'] . '<br />';
						echo '</a>';
					echo '</div>';
				}
			}
		echo '</div>';
	} //end-if-num-garagedoors


	else {
		echo '<div style="text-align:center; color:#999999; font-size:40px; margin-top:5%;">';
			echo '<i style="font-size:100px;" class="fa fa-car"></i><br />';
			echo _('No garagedoors found');
		echo '</div>';

		echo '<div style="text-align:center; color:#999999; font-size:30px; margin-top:1%;">';
			echo _('You can add a garagedoor on the settings-wheel at top right corner :-)');
		echo '</div>';
	}

?>

<div style="clear:both;" class="clearfix"></div>

<!-- <div id="garage-webcam"></div> -->

<br /><br />

