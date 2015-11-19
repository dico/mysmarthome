<?php
	$getProviders = $objSms->getProviders();
	$numProviders = count($getProviders);
?>



<h2><?php echo _('SMS Providers'); ?></h2>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('SMS Providers'); ?></li>
</ol>



<div style="margin:15px;">
	<a style="margin-right:15px;" class="btn btn-success" href="<?php echo URL; ?>modules/settings/modal/sms_provider_edit.php" data-toggle="modal" data-target="#modal">
		<i class="fa fa-plus"></i> <?php echo _('Add') . ' ' . _('SMS Provider'); ?>
	</a>

	<a style="margin-right:25px;" class="btn btn-default" href="<?php echo URL; ?>modules/settings/modal/sms_help.php" data-toggle="modal" data-target="#modal">
		<i class="fa fa-question-circle"></i> <?php echo _('Help'); ?>
	</a>
</div>


<?php
	$defaultIsSet = false;

	if ($numProviders > 0) {
		foreach ($getProviders as $id => $data) {
			if ($data['default'] == 1) $defaultIsSet = true;
		}

		if (!$defaultIsSet) {
			echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> '._('There are no default SMS gateway set').'</div>';
		}
	}

	else {
		echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> '._('There are not added any SMS providers. Please add one to start sending SMS').' :-)</div>';
	}
?>


<?php if ($numProviders > 0): ?>

	<table class="table table-striped table-hover">
			
		<thead>
			<tr>
				<th style="width:70px;"><?php echo _('Default'); ?></th>
				<th><?php echo _('Title'); ?></th>
				<th><?php echo _('URL'); ?></th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($getProviders as $id => $data): ?>
				<tr>
					<td style="text-align:center;">
						<?php
							if ($data['default'] == 0) {
								echo '<a href="?m=system&action=setDefaultProvider&id='.$id.'">';
									echo '<i class="fa fa-close"></i>';
								echo '</a>';
							} else {
								echo '<a href="?m=system&action=setDefaultProvider&id='.$id.'">';
									echo '<i class="fa fa-check"></i>';
								echo '</a>';
							}
						?>
					</td>

					<td>
						<?php echo $data['title']; ?>
					</td>

					<td>
						<span class="toolTip" title="<?php echo $data['url']; ?>">
							<?php echo shortenURL($data['url']); ?>
						</span>
					</td>

					<td style="text-align:right;">
						<a class="toolTip" style="margin-right:15px;" title="<?php echo _('This will send a test SMS to your phone'); ?>" href="<?php echo URL; ?>modules/settings/modal/sms_test.php?id=<?php echo $id; ?>" data-toggle="modal" data-target="#modal">
							<?php echo _('Test SMS'); ?>
						</a>

						<a style="margin-right:15px;" href="<?php echo URL; ?>modules/settings/modal/sms_provider_edit.php?id=<?php echo $id; ?>" data-toggle="modal" data-target="#modal">
							<i class="fa fa-edit"></i> <?php echo _('Edit'); ?>
						</a>

						<a class="toolTip" style="margin-right:15px;" title="<?php echo _('Delete this SMS provider'); ?>" href="<?php echo URL; ?>core/handlers/Sms_handler.php?action=deleteProvider&id=<?php echo $id; ?>">
							<i class="fa fa-trash"></i> <?php echo _('Delete'); ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>