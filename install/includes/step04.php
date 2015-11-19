<h2>Create a home and user</h2>

<?php
	
	if (isset($_GET['msg']))
	{
		if ($_GET['msg'] == 01) {
			echo '<div class="alert alert-danger">';

				echo '<i class="fa fa-warning"></i> <b>Oh, crap! Could not create your user in the database.</b><br /><br />';

				// If mysqli error exist
				if (isset($_GET['error']) && !empty($_GET['error'])) {
					echo '<b>DB error:</b> &nbsp;';
					echo $_GET['error'];

					echo '<br /><br />';

					// If user were already created
					if ($_GET['error'] == "Duplicate entry '1' for key 'PRIMARY'") {
						echo '<p>Looks like the user is already created. Maybe you went back a step?<br /><i class="fa fa-check"></i> You could probably just jump to the next step by clicking in the sidebar to the left :-)</p>';
					} else {
						echo '<p>Please fix the error above!<br />If your user were already created, you can just jump to the next step in the sidebar.</p>';
					}

				}

				// Else: Show possible errors
				else {

					echo '<b>Possible errors:</b><br />';
					echo '<ul>';
						// Check if SESSION variables is still there
						if (!isset($_SESSION['install']['db']['db_host'])) {
							echo '<li>DB credentials are stored in SESSION under installation. Looks like the SESSION is not set. Please go back to database setup and try again.</i>';
						} else {
							echo '<li>DB credentials are stored in SESSION under installation. Maybe the SESSION is outdated?</i>';
							echo '<li>Version mismatch. Maybe database has changed and something is not updated.</i>';
							echo '<li>Maybe your input has some weird characters?</i>';
							echo '<li>Maybe your DB user don\'t have permission to INSERT user?</i>';
						}
					echo '</ul><br />';

					echo '<p>Go back and try again. If it\'s still fails, you can try creating the user directly into the database yourself.</p>';
				}
			echo '</div>';
		}
	}



	if (isset($_SESSION['install']['user']['page_title'])) {
		$page_title = $_SESSION['install']['user']['page_title'];
	} else {
		$page_title = 'MySmartHome';
	}
?>

<form action="?page=step04_exec" method="POST">
	<div class="form-group">
		<label for="inputMail">Your mail</label>
		<input type="email" class="form-control" name="inputMail" id="inputMail" placeholder="my@mail.no" value="<?php echo $_SESSION['install']['user']['mail']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputPw">Password</label>
		<input type="text" class="form-control" name="inputPw" id="inputPw" placeholder="aSecretPassword" value="<?php echo $_SESSION['install']['user']['password']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputDisplayname">My name</label>
		<input type="text" class="form-control" name="inputDisplayname" id="inputDisplayname" placeholder="Firstname Lastname" value="<?php echo $_SESSION['install']['user']['displayname']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputHomeTitle">Home title</label>
		<input type="text" class="form-control" name="inputHomeTitle" id="inputHomeTitle" placeholder="My Smart Home" value="<?php echo $page_title; ?>">
	</div>


	<div style="text-align:right; margin-top:30px;">
		<button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-arrow-right"></i></button>
	</div>
</form>