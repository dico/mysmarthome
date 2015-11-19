<script type="text/javascript" src="<?php echo MODULES_URL; ?>lights/js/lights.js"></script>

<script type="text/javascript">
	//$("input[type='checkbox']").val();

	


	/*function dimLight(intID, extID, action)
	{
		lights('telldus.live', intID, extID, action);
	}*/
</script>

<h1><?php echo _('Lights'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><?php echo _('Lights'); ?></li>
</ol>

<?php
	$p = array (
		'categories' => array(1),
	);

	$getDevices = $objDevices->getDevices($p);
?>

<table class="table table-hover table-striped">

	<tbody>
		<?php foreach ($getDevices as $intID => $dData): ?>

			<?php
				// State 1 = on
				// State 2 = off

				// Off
				if ($dData['last_values'][4]['value'] == 0) {
					$checked_onoff = '';
				} 

				// On
				else {
					$checked_onoff = 'checked';
				}
			?>

			<tr>
				<td style="width:120px;">
					<input 
						type="checkbox" 
						class="toggleLightSwitch" 
						data-size="small" 
						data-inverse="false" 
						data-int-id="<?php echo $dData['deviceIntID']; ?>" 
						data-ext-id="<?php echo $dData['deviceExtID']; ?>" 
						name="onOff" 
						<?php echo $checked_onoff; ?> 
					/>	
				</td>

				<td>
					<!-- <a href="<?php echo URL; ?>modules/lights/modal/light_settings.php?id=<?php echo $intID; ?>" data-toggle="modal" data-target="#lightSettings"> -->
					<a href="<?php echo URL; ?>core/includes/modal/device.php?id=<?php echo $intID; ?>" data-toggle="modal" data-target="#modal">
						<?php echo $dData['name']; ?>
					</a>
					<span id="light_loader-<?php echo $dData['deviceIntID']; ?>" style="display:none;"><img src="core/images/ajax-loader-arrows.gif" /></span>
				</td>

				<!-- <td>
					VAL: <?php echo $dData['last_values']['value1']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="bindings/telldus.live/execute.php?action=turnOn&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
						Turn ON
					</a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="bindings/telldus.live/execute.php?action=turnOff&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
						Turn OFF
					</a>
				</td> -->
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>


<?php
	/*echo "<pre>";
		print_r($getDevices);
	echo "</pre>";*/	
?>



<!-- Modal -->
<div class="modal fade" id="lightSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		</div>
	</div>
</div>