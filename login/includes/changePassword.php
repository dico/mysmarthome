<div style="text-align:center; margin-bottom:40px;">
	<a href="index.php">
		<img style="max-width:200px;" src="<?php echo URL; ?>core/images/logo.png" />
	</a>
</div>

<h1><?php echo _('Enter new password'); ?></h1>
<div style="font-size:18px;">
	<?php echo _('Write a new password for your account'); ?>
</div>


<div style="margin-top:25px;">

	<?php 
		if (isset($_GET['msg'])) {
			if ($_GET['msg'] == 01) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Password does not match. Please try again.').'</div>';
			if ($_GET['msg'] == 02) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Password must be at least 5 characters').'</div>';
			if ($_GET['msg'] == 03) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Session expired. Please start over.').'</div>';
		}
	?>


	<form action="<?php echo URL; ?>core/handlers/Auth_handler.php?action=forgotChangePasswords&mail=<?php echo $_GET['mail']; ?>&token=<?php echo $_GET['token']; ?>" method="POST">

		<input class="form-control input-lg" type="password" id="password" name="password" placeholder="<?php echo _('Enter new password'); ?>" autofocus />
		<input class="form-control input-lg" style="margin-top:8px;" type="password" id="cpassword" name="cpassword" placeholder="<?php echo _('Re-enter new passord'); ?>" />

		<?php
			// Create a random key to secure the login from this form!
			$_SESSION['msh_form_token'] = "msh34i5".rand(1111,9999)."FsdfF36¤%&¤%&".time()."lkfjio".rand(1111,9999)."ut854";
			$x = hash('sha256', $_SESSION['msh_form_token']);
			echo "<input type='hidden' name='formToken' value='$x' />";
		?>

		<button class="btn btn-primary btn-block btn-lg" style="margin-top:8px;"><?php echo _('Change password'); ?></button>

		<p style="margin-top:10px;"><a href="index.php"><i class="fa fa-arrow-left"></i> <?php echo _('Go back to login'); ?></a></p>
	</form>
</div>