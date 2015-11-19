<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );
?>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Add') . ' ' . _('Event'); ?></h4>
</div>



<form class="form-horizontal" action="<?php echo URL; ?>core/handlers/Events_handler.php?action=addEvent" method="POST">
	<div class="modal-body">

		<div class="form-group">
			<label for="inputTitle" class="col-sm-2 control-label"><?php echo _('Title'); ?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="<?php echo _('Title'); ?>" />
			</div>
		</div>

	</div>



	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo _('Save'); ?></button>
	</div>

</form>