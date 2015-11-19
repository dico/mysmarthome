<h1><?php echo _('Temperature'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><?php echo _('Temperature'); ?></li>
</ol>

<?php

	// Devices parameters
	$p = array (
		'categories' => array(2,3),
	);


	// Get device list
	include(ABSPATH . 'core/includes/devices.php');

?>