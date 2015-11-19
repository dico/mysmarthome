<?php if ($core['modules']['user_modules_count'] == 0): ?>
	<div class="alert alert-warning">
		<i class="fa fa-info-circle"></i> <?php echo _('You haven\'t selected any modules!'); ?>

		<a style="margin-left:20px;" href="<?php echo URL; ?>modules/settings/modal/user_module_select.php" data-toggle="modal" data-target="#modal">
			<?php echo _('Select modules'); ?>
		</a>

	</div>
<?php endif ?>


<?php

	// Devices parameters
	$p = array (
		'categories' => array(1,2,7,10,12),
		'dashboard' => 1,
	);

	// Get device list
	include(ABSPATH . 'core/includes/devices.php');

?>