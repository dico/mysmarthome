<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );	
?>



<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Change home status'); ?></h4>
</div>

<div class="modal-body">

	<?php

		if ($thisUser['home_status'] == 'home') {
			echo _('You are home!');
			echo '<a class="btn btn-block btn-default btn-lg" href="'.URL.'core/handlers/Users_handler.php?action=setHomeStatus&status=away"> <i class="fa fa-sign-out"></i> '. _('I\'m Away') .'</a>';
		}

		if ($thisUser['home_status'] == 'away') {
			echo _('You are away!');
			echo '<a class="btn btn-block btn-default btn-lg" href="'.URL.'core/handlers/Users_handler.php?action=setHomeStatus&status=home"> <i class="fa fa-home"></i> '. _('I\'m Home') .'</a>';
		}

	?>


</div> <!-- end modal-body -->


<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
</div>