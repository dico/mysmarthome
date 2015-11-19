<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	if (isset($_GET['id'])) {
		$getProvider = $objSms->getProviders($_GET['id']);
		$getProvider = $getProvider[$_GET['id']];

		$formAction = URL . "core/handlers/Sms_handler.php?action=editProvider&id={$_GET['id']}";
	} else {
		$formAction = URL . "core/handlers/Sms_handler.php?action=addProvider";
	}

?>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Add') . ' ' . _('SMS provider'); ?></h4>
</div>



<form class="form-horizontal" action="<?php echo $formAction; ?>" method="POST">
	<div class="modal-body">

		<div style="margin:15px;">
			<p>
				<i class="fa fa-info-circle"></i>
				<?php echo _('You only need to enter parameters required from you SMS provider. For example, most providers don\'t require the <i>"from number"</i> parameter, in this case leave it blank.'); ?>
			</p>

			<p>
				<?php echo _('To test, you can also paste the example URL from your provider directly in the URL input, without parameters (%%). But remember, if you set this as default, the messages will be the same for every SMS that will be sent.'); ?>
			</p>
		</div>




		<div style="margin:25px 15px;">

			<div class="form-group">
				<label for="inputTitle" class="col-sm-2 control-label"><?php echo _('Title'); ?></label>
				<div class="col-sm-10">
					<input style="display:inline-block; max-width:250px;" type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="<?php echo _('Title'); ?>" value="<?php echo $getProvider['title']; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label for="inputUsername" class="col-sm-2 control-label"><?php echo _('Username'); ?></label>
				<div class="col-sm-10">
					<input style="display:inline-block; max-width:150px;" type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="<?php echo _('Username'); ?>" value="<?php echo $getProvider['username']; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label"><?php echo _('Password'); ?></label>
				<div class="col-sm-10">
					<input style="display:inline-block; max-width:150px;" type="text" class="form-control" name="inputPassword" id="inputPassword" placeholder="<?php echo _('Password'); ?>" value="<?php echo $getProvider['password']; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label for="inputAPIcode" class="col-sm-2 control-label"><?php echo _('API code'); ?></label>
				<div class="col-sm-10">
					<input style="display:inline-block; max-width:150px;" type="text" class="form-control" name="inputAPIcode" id="inputAPIcode" placeholder="<?php echo _('API code'); ?>" value="<?php echo $getProvider['api_code']; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label for="inputFromNumber" class="col-sm-2 control-label"><?php echo _('From number'); ?></label>
				<div class="col-sm-10">
					<input style="display:inline-block; max-width:150px;" type="text" class="form-control" name="inputFromNumber" id="inputFromNumber" placeholder="<?php echo _('From number'); ?>" value="<?php echo $getProvider['from_number']; ?>" />
				</div>
			</div>


			<div class="form-group">
				<label for="inputURLAuth" class="col-sm-2 control-label"><?php echo _('URL Auth'); ?></label>
				<div class="col-sm-10">
					<textarea class="form-control" style="height:100px;" name="inputURLAuth" id="inputURLAuth" placeholder="<?php echo _('Example'); ?>: http://smsprovider.com/auth/?username=%%username%%&password=%%password%%"><?php echo $getProvider['url_auth']; ?></textarea>
					
					<div style="margin-top:10px; color:#666;">
						<?php echo _('Some providers needs you to authenticate before sending. 
											This URL will open before the sending with the URL below. 
											You can paste the auth URL above if needed. You can use the same parameters as listed below in this URL - 
											if the parameters are not the same, you can just write url without parameters.'); ?>
					</div>

				</div>
			</div>

			<div class="form-group">
				<label for="inputURL" class="col-sm-2 control-label"><?php echo _('URL'); ?></label>
				<div class="col-sm-10">
					<textarea class="form-control" style="height:100px;" name="inputURL" id="inputURL" placeholder="<?php echo _('Example'); ?>: http://smsprovider.com?username=%%username%%&password=%%password%%&to=%%number%%&message=%%message%%"><?php echo $getProvider['url']; ?></textarea>
					
					<div style="margin-top:10px; color:#666;">
						<b><?php echo _('Valid parameters'); ?>:</b><br />
						%%username%% = <?php echo _('Username'); ?><br />
						%%password%% = <?php echo _('Password'); ?><br />
						%%api_code%% = <?php echo _('API code'); ?><br />
						%%from_number%% = <?php echo _('Senders number'); ?><br />
						%%number%% = <?php echo _('Number the text-message will be sent to'); ?><br />
						%%message%% = <?php echo _('The text-message'); ?><br />
					</div>

				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
					<label>
						<?php
							if ($getProvider['default'] == 1) $defaultChecked = 'checked="checked"';
							else $defaultChecked = '';
						?>

						<input type="checkbox" name="inputDefault" value="1" <?php echo $defaultChecked; ?> /> <?php echo _('Set as default'); ?>
					</label>
					</div>
				</div>
			</div>


		</div>



		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
			<button type="submit" class="btn btn-primary"><?php echo _('Save'); ?></button>
		</div>

	</div>
</form>