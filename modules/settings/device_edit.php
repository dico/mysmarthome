<?php
	require_once( dirname(__FILE__) . '/../../core.php' );

	$getID = clean($_GET['id']);
	$d = $objDevices->getDevice($getID);




	$catArr = array();
	$numCategories = count($d['categories']);
	if ($numCategories) {
		foreach ($d['categories'] as $catID => $catData) {
			$catArr[] = $catID;
		}
	}


	$dUnits = $objDevices->getDeviceUnits($getID);
	$numUnits = count($dUnits);


	/*echo "<pre>";
		print_r($d);
	echo "</pre>";*/
	
?>


<form class="form-horizontal" action="<?php echo URL; ?>core/handlers/Devices_handler.php?action=saveDeviceSettings&id=<?php echo $getID; ?>" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><?php echo $d['name'] ?></h4>
		</div>

		<div class="modal-body">


				<div class="form-group">
					<label for="units" class="col-sm-3 control-label"><?php echo _('Device alias'); ?></label>
					<div class="col-sm-9">
						<?php
							if ($d['monitor'] == 0) {
								$monitor_class = 'btn-default';
								$monitor_new_value = 1;
							}
							else {
								$monitor_class = 'btn-success';
								$monitor_new_value = 0;
							}

							if ($d['public'] == 0) {
								$public_class = 'btn-default';
								$public_new_value = 1;
							}
							else {
								$public_class = 'btn-success';
								$public_new_value = 0;
							}

							if ($d['dashboard'] == 0) {
								$dashboard_class = 'btn-default';
								$dashboard_new_value = 1;
							}
							else {
								$dashboard_class = 'btn-success';
								$dashboard_new_value = 0;
							}

							echo '<a href="core/handlers/Devices_handler.php?action=setMonitor&id='.$d['deviceIntID'].'&value='.$monitor_new_value.'" style="margin-right:5px;" class="btn '.$monitor_class.' toolTip" title="'._('Monitor. Green = ON').'"><i class="fa fa-play"></i></a>';
							echo '<a href="core/handlers/Devices_handler.php?action=setPublic&id='.$d['deviceIntID'].'&value='.$public_new_value.'" style="margin-right:5px;" class="btn '.$public_class.' toolTip" title="'._('Show on public page. Green = ON').'""><i class="fa fa-group"></i></a>';
							echo '<a href="core/handlers/Devices_handler.php?action=setDashboard&id='.$d['deviceIntID'].'&value='.$dashboard_new_value.'" style="margin-right:5px;" class="btn '.$dashboard_class.' toolTip" title="'._('Show on dashboard. Green = ON').'""><i class="fa fa-desktop"></i></a>';
						?>
					</div>
				</div>


				<div class="form-group">
					<label for="units" class="col-sm-3 control-label"><?php echo _('Device alias'); ?></label>
					<div class="col-sm-9">
						<input class="form-control" name="device_name" value="<?php echo $d['name']; ?>" />
					</div>
				</div>


				<div class="form-group">
					<label for="units" class="col-sm-3 control-label"><?php echo _('Device units'); ?></label>
					<div class="col-sm-9">
						<?php
							if ($numUnits > 0) {
								foreach ($dUnits as $unitID => $uData) {
									echo "{$uData['title']} ({$uData['tag']}) <br />";
								}
							} else {
								echo _('This device has no units');
							}
							
						?>
					</div>
				</div>

				<div class="form-group">
					<label for="units" class="col-sm-3 control-label"><?php echo _('Last values'); ?></label>
					<div class="col-sm-9">
						<table class="table table-striped">
							<?php
								foreach ($d['last_values'] as $key => $vData) {
									echo '<tr>';

										echo '<td style="width:120px">';
											echo $vData['unit']['title'] . '<br />';
										echo '</td>';

										echo '<td style="text-align:right; width:50px;">';
											echo $vData['value'] . '' . $vData['unit']['tag'] . '<br />';
										echo '</td>';

										if ( (time() - $vData['time']['timestamp']) > 1800 ) $color = 'red';
										else $color = 'green';

										echo '<td style="text-align:right;">';
											echo '<span style="color:'.$color.';" class="toolTip" title="'.$vData['time']['time_human'].'">' . ago($vData['time']['timestamp']) . '</span>';
										echo '</td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
				</div>


				<div class="form-group">
					<label for="units" class="col-sm-3 control-label"><?php echo _('Supported methods'); ?></label>
					<div class="col-sm-9">
						<?php
							$selMethods = array();
							foreach ($d['methods'] as $mID => $mData) {
								$selMethods[] = $mID;
							}
							
							$getMethods = $objDevices->getMethods();

							foreach ($getMethods as $mID => $mData) {
								echo '<div class="checkbox">';
									echo '<label>';

										if (in_array($mID, $selMethods)) $thisChecked = 'checked="checked"';
										else $thisChecked = '';

										echo '<input type="checkbox" name="methods[]" value="'.$mID.'" '.$thisChecked.'> ' . $mData['title'];
									echo '</label>';
								echo '</div>';
							}
						?>

					</div>
				</div>


				
			
				<div class="form-group">
					<label for="categories" class="col-sm-3 control-label"><?php echo _('Category'); ?></label>
					<div class="col-sm-9">
						<?php
							$c = $objCore->getCategories();

							foreach ($c as $catID => $cData) {
								echo '<div class="checkbox">';
									echo '<label>';

										if (in_array($catID, $catArr)) $thisChecked = 'checked="checked"';
										else $thisChecked = '';

										echo '<input type="checkbox" name="categories[]" value="'.$catID.'" '.$thisChecked.'> ' . $cData['name'];
									echo '</label>';
								echo '</div>';
							}
							
						?>
					</div>
				</div>


			<?php
				/*echo "<pre>";
					print_r($d);
				echo "</pre>";*/
				
			?>
		</div>

		<div class="modal-footer">
			<div style="float:left;">
				<a class="btn btn-danger" href="<?php echo URL; ?>core/handlers/Devices_handler.php?action=delete&id=<?php echo $getID; ?>" onclick="return confirm('<?php echo _("Are you sure you want to delete this and all connected data?") . " " . _("If this device belongs to a binding, it\'s possible it would be synced again!"); ?>')">
					<i class="fa fa-trash"></i> <?php echo _('Delete'); ?>
				</a>
			</div>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
			<button type="submit" class="btn btn-primary"><?php echo _('Save settings'); ?></button>
		</div>
	</div>

</form>