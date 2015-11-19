<div style="text-align:center; margin-bottom:40px;">
	<a href="index.php">
		<img style="max-width:200px;" src="<?php echo URL; ?>core/images/logo.png" />
	</a>
</div>

<h1><?php echo _('Forgot password'); ?></h1>
<div style="font-size:18px;">
	<?php echo _('Enter resetcode below:'); ?>
</div>

<div style="font-size:14px; margin-top:10px;">
	<?php echo _('The resetcode was sent to your mail. Please check your spamfilter if you doesn\'t appear in your inbox.'); ?>
</div>


<div style="margin-top:25px;">
	<form action="<?php echo URL; ?>core/handlers/Auth_handler.php?action=confirmResetCode&mail=<?php echo $_GET['mail']; ?>" method="POST">

		<input class="form-control input-lg" type="tel" id="inputResetCode" name="inputResetCode" placeholder="<?php echo _('Resetcode'); ?>" value="" autofocus />

		<button class="btn btn-primary btn-block btn-lg" style="margin-top:8px;"><?php echo _('Confirm'); ?></button>

		<?php
			// Create a random key to secure the login from this form!
			$_SESSION['msh_form_token'] = "msh34i5".rand(1111,9999)."FsdfF36¤%&¤%&".time()."lkfjio".rand(1111,9999)."ut854";
			$x = hash('sha256', $_SESSION['msh_form_token']);
			echo "<input type='hidden' name='formToken' value='$x' />";
		?>

		<p style="margin-top:10px;"><a href="index.php"><i class="fa fa-arrow-left"></i> <?php echo _('Go back to login'); ?></a></p>
	</form>
</div>