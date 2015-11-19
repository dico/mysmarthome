<script type="text/javascript">
	$(document).ready(function() {
		$('#nextLoading').on('click', function () {
			var $btn = $(this).button('loading')
			// business logic...
			// $btn.button('reset')
		});
	});
</script>

<h2>Enter database credentials</h2>

<?php
	
	if (isset($_GET['msg']))
	{
		if ($_GET['msg'] == 01) echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> Could not connect to the database. Maybe you forgot to create <b>'.$_SESSION['install']['db_name'].'</b> ?</div>';
	}


	if (isset($_SESSION['install']['db_host'])) {
		$db_host = $_SESSION['install']['db']['db_host'];
	} else {
		$db_host = 'localhost';
	}

?>

<form action="?page=step03_exec" method="POST">
	<div class="form-group">
		<label for="inputDBname">Database name</label>
		<input type="text" class="form-control" name="inputDBname" id="inputDBname" placeholder="msh" value="<?php echo $_SESSION['install']['db']['db_name']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputDBuser">User</label>
		<input type="text" class="form-control" name="inputDBuser" id="inputDBuser" placeholder="root" value="<?php echo $_SESSION['install']['db']['db_user']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputDBpw">Password</label>
		<input type="text" class="form-control" name="inputDBpw" id="inputDBpw" placeholder="mySecretPassword" value="<?php echo $_SESSION['install']['db']['db_password']; ?>" required>
	</div>

	<div class="form-group">
		<label for="inputDBhost">Host</label>
		<input type="text" class="form-control" name="inputDBhost" id="inputDBhost" placeholder="localhost" value="<?php echo $db_host; ?>" required>
	</div>


	<div style="text-align:right; margin-top:30px;">
		<button type="submit" class="btn btn-primary btn-lg" id="nextLoading">Next <i class="fa fa-arrow-right"></i></button>
	</div>
</form>