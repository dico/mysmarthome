<?php
	$getUsers = $objUsers->getUsers();	
?>


<?php foreach ($getUsers as $userID => $uData): ?>
	<h3 style="margin-top:45px;"><?php echo $uData['displayname']; ?> (<?php echo $uData['mail']; ?>)</h3>


	<?php
		unset($getDevices);

		$p = array (
			'user' => $userID,
		);
		$getDevices = $objDevices->getDevices($p);

		/*echo "<pre>";
			print_r($getDevices);
		echo "</pre>";*/
		
	?>

	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th colspan="2"><?php echo _('Device name'); ?></th>
				<th style="width:350px;"><?php echo _('Values'); ?></th>
				<th style="text-align:center; width:80px;"><?php echo _('Monitor'); ?></th>
				<th style="text-align:center; width:80px;"><?php echo _('Public'); ?></th>
				<th style="text-align:center; width:80px;"><?php echo _('Deactive'); ?></th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($getDevices as $intID => $dData): ?>
				<tr>
					<td style="text-align:center; width:40px;">
						<?php echo $dData['icon']; ?>
					</td>

					<td style="width:300px;">
						<?php echo $dData['name']; ?>
					</td>

					<td>
						<table width="100%">
							<?php
								foreach ($dData['last_values'] as $key => $vData) {
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
					</td>

					<td style="text-align:center;">
						<?php if ($dData['monitor'] == 1): ?>
							<i class="fa fa-check"></i>
						<?php endif ?>
					</td>

					<td style="text-align:center;">
						<?php if ($dData['public'] == 1): ?>
							<i class="fa fa-check"></i>
						<?php endif ?>
					</td>

					<td style="text-align:center;">
						<?php if ($dData['deactive'] == 1): ?>
							<i class="fa fa-check"></i>
						<?php endif ?>
					</td>

					<td></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php
		/*echo "<pre>";
			print_r($getDevices);
		echo "</pre>";*/
	?>

<?php endforeach ?>