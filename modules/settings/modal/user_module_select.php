<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );
	
	// Get users modules
	$userModules = $core['modules']['user_modules'];
	$c = count($userModules);


	// Build new array for in_array check
	if ($c > 0) {
		foreach ($userModules as $mID => $mData) {
			$userModulesArr[] = $mID;
		}
	}	
	
?>



<form action="<?php echo URL; ?>core/handlers/Core_handler.php?action=saveUserModule" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel"><?php echo _('Select modules'); ?></h4>
	</div>

	<div class="modal-body">

		<p>
			<?php echo _('Devices listed in the different modules will depend on the device-sync from the bindings.'); ?>
		</p>

	
		<?php foreach ($core['modules']['all'] as $key => $thisModule): ?>

			<?php
				// Check if user selected this module
				if ($c > 0) {
					if (in_array($thisModule['id'], $userModulesArr)) $thisChecked = 'checked="checked"';
					else $thisChecked = '';
				}
			?>

			<label style="display:block">
				<input type="checkbox" name="modules[]" value="<?php echo $thisModule['id']; ?>" <?php echo $thisChecked; ?> /> 
					<?php echo $thisModule['name']; ?>
			</label>
		<?php endforeach ?>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo _('Save'); ?></button>
	</div>

</form>