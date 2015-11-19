<div style="text-align:center; margin-bottom:40px;">
	<a href="index.php">
		<img style="max-width:200px;" src="<?php echo URL; ?>core/images/logo.png" />
	</a>
</div>

<h1><?php echo _('Forgot password'); ?></h1>
<div style="font-size:18px;">
	<?php echo _('Insert your account email address'); ?>
</div>


<div style="margin-top:25px;">
	<?php 
		if (isset($_GET['msg'])) {
			if ($_GET['msg'] == 01) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('User not found').'</div>';
		}
	?>

	<form action="<?php echo URL; ?>core/handlers/Auth_handler.php?action=sendResetCode" method="POST">

		<input class="form-control input-lg" type="email" id="inputLoginMail" name="mail" placeholder="<?php echo _('E-mail'); ?>" value="<?php echo $_GET['mail']; ?>" autofocus />

		<button class="btn btn-primary btn-block btn-lg" style="margin-top:8px;"><?php echo _('Send reset code'); ?></button>

		<?php
			// Create a random key to secure the login from this form!
			$_SESSION['msh_form_token'] = "msh34i5".rand(1111,9999)."FsdfF36¤%&¤%&".time()."lkfjio".rand(1111,9999)."ut854";
			$x = hash('sha256', $_SESSION['msh_form_token']);
			echo "<input type='hidden' name='formToken' value='$x' />";
		?>

		<p style="margin-top:10px;"><a href="index.php?mail=<?php echo $_GET['mail']; ?>"><i class="fa fa-arrow-left"></i> <?php echo _('Go back'); ?></a></p>
	</form>
</div>