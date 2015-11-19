<script>
	$(document).ready(function() {
		$('#selectAll').click(function (e) {
			$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		});
	});
</script>

<?php
	$getBindings = $objCore->getBindings();
?>

<h1><?php echo _('Devicemanager'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Devices'); ?></li>
</ol>





<div style="margin:8px;">
	<p class="info">
		<i class="fa fa-info-circle"></i>
		<?php echo _('Here you can set different categories to your devices. Ex. if a On/off switch it\'s used for a radio, you can put it under category "Audio".'); ?><br />
		<?php echo _('Note that each binding can have own rules for managing devices on sync.'); ?>
	</p>
</div>





<div class="row" style="margin-top:30px;">
	<div class="col-md-3">

		<div class="list-group">
			<?php
				if (!isset($_GET['binding'])) $navClass = 'active';
				else $navClass = '';
			?>
			<a href="?m=settings&page=devices" class="list-group-item <?php echo $navClass; ?>">
				<i class="fa fa-cubes"></i> <?php echo _('All devices'); ?>
			</a>

			<?php foreach ($getBindings as $key => $bData): ?>

				<?php
					if (isset($_GET['binding']) && $_GET['binding'] == $key) $navClass = 'active';
					else $navClass = '';
				?>

				<a href="?m=settings&page=devices&binding=<?php echo $key; ?>" class="list-group-item <?php echo $navClass; ?>">
					<i class="fa fa-cubes"></i> <?php echo $bData['name']; ?>
				</a>
			<?php endforeach ?>
		</div>
		
	</div>



	<div class="col-md-9">

		<?php
			if (isset($_GET['msg'])) {
				if ($_GET['msg'] == 1) echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> &nbsp; '._('You have to select one or more devices').'</div>';
			}
		?>

		<?php
			$p = array();

			if (isset($_GET['binding'])) {
				$p = array (
					'binding' => $_GET['binding'],
				);
			}
			$devices = $objDevices->getDevices($p);
			$numDevices = count($devices);			
		?>

		<h3><?php echo _('Active devices'); ?></h3>

		<?php if ($numDevices > 0): ?>
			<table class="table table-striped table-hover">

				<thead>
					<tr>
						<th><?php echo _('Device name'); ?></th>
						<th><?php echo _('Categories'); ?></th>
						<th><?php echo _('Binding'); ?></th>
						<th></th>
					</tr>				
				</thead>

				<tbody>
					<?php foreach ($devices as $intID => $dData): ?>
						<tr>
							<td>
								<a href="<?php echo URL; ?>modules/settings/device_edit.php?id=<?php echo $intID; ?>" data-toggle="modal" data-target="#myModal">
								<?php echo '#' . $intID . ': ' . $dData['name']; ?>
								</a>
							</td>

							<td>
								<?php
									foreach ($dData['categories'] as $catID => $cData) {
										echo $cData['name'];
									}
								?>
							</td>

							<td>
								<?php echo $dData['binding']; ?>
							</td>

							<td style="width:80px;">
								<div class="dropdown">
									<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
										<i class="fa fa-cog"></i> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="<?php echo URL; ?>core/handlers/Devices_handler.php?action=deactivate&id=<?php echo $intID; ?>">
												<i class="fa fa-fw fa-trash"></i> <?php echo _('Deactivate device'); ?>
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p><?php echo _('No active devices found'); ?></p>
		<?php endif ?>






		<?php
			$p = array('deactive' => 1);

			if (isset($_GET['binding'])) {
				$p = array (
					'binding' => $_GET['binding'],
					'deactive' => 1,
				);
			}
			$devices = $objDevices->getDevices($p);
			$numDevices = count($devices);
		?>

		<h3><?php echo _('Deactive devices'); ?></h3>

		<?php if ($numDevices > 0): ?>
			<form action="" method="POST">
				<table class="table table-striped table-hover">

					<thead>
						<tr>
							<th style="text-align:center;"><input type="checkbox" id="selectAll" /></th>
							<th><?php echo _('Device name'); ?></th>
							<th><?php echo _('Categories'); ?></th>
							<th><?php echo _('Binding'); ?></th>
							<th></th>
						</tr>				
					</thead>

					<tbody>
						<?php foreach ($devices as $intID => $dData): ?>
							<tr>
								<td style="width:35px; text-align:center;">
									<input type="checkbox" name="deactivatedDevices[]" value="<?php echo $intID; ?>" />
								</td>

								<td>
									<a href="<?php echo URL; ?>modules/settings/device_edit.php?id=<?php echo $intID; ?>" data-toggle="modal" data-target="#myModal">
										<?php echo '#' . $intID . ': ' . $dData['name']; ?>
									</a>
								</td>

								<td>
									<?php
										foreach ($dData['categories'] as $catID => $cData) {
											echo $cData['name'];
										}
									?>
								</td>

								<td>
									<?php echo $dData['binding']; ?>
								</td>

								<td style="width:80px;">
									<div class="dropdown">
										<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
											<i class="fa fa-cog"></i> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu pull-right" role="menu">
											<li>
												<a href="<?php echo URL; ?>core/handlers/Devices_handler.php?action=activate&id=<?php echo $intID; ?>">
													<i class="fa fa-fw fa-check"></i> <?php echo _('Re-activate device'); ?>
												</a>
											</li>
											<li>
												<a href="<?php echo URL; ?>core/handlers/Devices_handler.php?action=delete&id=<?php echo $intID; ?>" onclick="return confirm('<?php echo _('Are you sure'); ?>?')">
													<i class="fa fa-fw fa-trash"></i> <?php echo _('Delete device'); ?>
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<b><?php echo _('Mass action'); ?></b><br />
				<button class="btn btn-default btn-xs" type="submit" formaction="<?php echo URL; ?>core/handlers/Devices_handler.php?action=massDelete"><?php echo _('Delete'); ?></button>

			</form>
		<?php else: ?>
			<p><?php echo _('No active devices found'); ?></p>
		<?php endif ?>




	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>