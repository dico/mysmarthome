<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );
	$getTheme = clean($_GET['themeID']);
?>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Select theme option'); ?></h4>
</div>

<div class="modal-body">


	<a class="btn btn-default btn-block btn-lg" href="<?php echo URL; ?>core/handlers/Core_handler.php?action=changeTheme&themeID=<?php echo $getTheme; ?>">
		<i class="fa fa-user"></i>
		<?php echo _('Change theme for all devices'); ?>
	</a>

	<a class="btn btn-default btn-block btn-lg" href="<?php echo URL; ?>core/handlers/Core_handler.php?action=changeTheme&themeID=<?php echo $getTheme; ?>&cookie=true">
		<i class="fa fa-desktop"></i>
		<?php echo _('Change theme for this device'); ?>
	</a>


</div> <!-- end modal-body -->


<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
</div>