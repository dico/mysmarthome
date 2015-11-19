<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Delete webcam'); ?></h4>
</div>

<div class="modal-body">
	<?php echo _('Are you sure you want to delete?'); ?>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<a class="btn btn-danger" href="core/handlers/Devices_handler.php?action=delete&id=<?php echo $_GET["id"]; ?>"><?php echo _('Delete'); ?></a>
</div>