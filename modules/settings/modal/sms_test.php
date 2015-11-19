<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	$getProvider = $objSms->getProviders($_GET['id']);

	$compileAuthURL = $objSms->compileAuthURL($_GET['id']);
	$compileURL = $objSms->compileURL($_GET['id']);



	$message = _('This is a test SMS ÆØÅ æøå');
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo _('Test') . ' ' . _('SMS Provider'); ?></h4>
</div>

<div class="modal-body">
	<?php
		
		if (!empty($thisUser['mobile'])) {

			echo '<h3>' . _('Sending SMS') . '</h3>';

			echo _('To') . ': ' . $thisUser['mobile'] . '<br />';
			echo _('Message') . ': ' . $message . '<br />';

			echo '<br />';


			
			$sendSMS = $objSms->sendSMS($thisUser['mobile'], $message, $_GET['id']);	

			echo '<b>' . _('Response') . '</b><br />';
			echo "<pre>";
				print_r($sendSMS);
			echo "</pre>";
		}




		else {
			echo _('You don\'t have a mobilenumber registered to your profile!');
		}

	?>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
</div>
