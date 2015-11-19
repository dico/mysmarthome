<div style="text-align:center; margin-bottom:40px;">
	<a href="index.php">
		<img style="max-width:200px;" src="<?php echo URL; ?>core/images/logo.png" />
	</a>
</div>

<h1><?php echo $config['page_title']; ?></h1>
<div style="font-size:18px;">
	<?php echo _('Log in to get started with MySmartHome'); ?>
</div>


<div style="margin-top:25px;">

	<?php 
		if (isset($_GET['msg'])) {
			if ($_GET['msg'] == 01) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Wrong mail and/or password').'</div>';
			if ($_GET['msg'] == 02) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Form token expired. Please refresh and try again.').'</div>';
			if ($_GET['msg'] == 88) echo '<div class="alert alert-success"><i class="fa fa-check"></i> &nbsp; '._('You can now log in with your new password :-)').'</div>';
			if ($_GET['msg'] == 99) echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> &nbsp; '._('Session expired. Please start over.').'</div>';
		}
	?>


	<form action="<?php echo URL; ?>core/handlers/Auth_handler.php?action=doLogin" method="POST">

		<input class="form-control input-lg" type="email" id="inputLoginMail" name="mail" placeholder="<?php echo _('E-mail'); ?>" value="<?php echo $_GET['mail']; ?>" autofocus />
		<input class="form-control input-lg" style="margin-top:8px;" type="password" id="inputLoginPassword" name="password" placeholder="<?php echo _('Password'); ?>" />

		<?php
			// Create a random key to secure the login from this form!
			$_SESSION['msh_form_token'] = "msh34i5".rand(1111,9999)."FsdfF36¤%&¤%&".time()."lkfjio".rand(1111,9999)."ut854";
			$x = hash('sha256', $_SESSION['msh_form_token']);
			echo "<input type='hidden' name='formToken' value='$x' />";
		?>

		<button class="btn btn-primary btn-block btn-lg" style="margin-top:8px;"><?php echo _('Log in'); ?></button>

		<div style="margin-top:20px; text-align:center;">
			<label>
				<input type="checkbox" name="remember" id="remember" value="1" checked="checked" /> <?php echo _('Remember me'); ?>
			</label>

			<p style="margin-top:10px;"><a href="?page=forgot&mail=<?php echo $_GET['mail']; ?>"><?php echo _('Forgot password?'); ?></a></p>
		</div>
	</form>
</div>