<?php if (!isset($_SESSION['install']['timezone'])): ?>
<script type="text/javascript">
	$(document).ready(function() {
		var tz = jstz.determine(); // Determines the time zone of the browser client
		tz.name(); // Returns the name of the time zone eg "Europe/Berlin"

		$('#selectTimezone option[value="'+tz.name()+'"]').attr("selected", "selected");
    });
</script>
<?php endif ?>


<h2>Configuration</h2>

<?php
	$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
?>

<form action="?page=step02_exec" method="POST">
	<div class="form-group">
		<label for="selectTimezone">Select timezone</label>
		<select class="form-control" id="selectTimezone" name="selectTimezone">
			<?php foreach ($tzlist as $key => $value): ?>
				<?php if (isset($_SESSION['install']['timezone']) && $_SESSION['install']['timezone'] == $value): ?>
					<option value="<?php echo $value; ?>" selected="selected"><?php echo $value; ?></option>
				<?php else: ?>
					<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
				<?php endif ?>
			<?php endforeach ?>
		</select>
	</div>


	<div class="form-group">
		<label for="inputURL">URL</label>
		<input class="form-control" type="text" name="inputURL" id="inputURL" placeholder="http://someUrl.com/msh/" value="<?php echo URL; ?>" />
	</div>

	<div class="form-group">
		<label for="inputAbsPath">Absolute server path</label>
		<input class="form-control" type="text" name="inputAbsPath" id="inputAbsPath" placeholder="/var/www/msh/" value="<?php echo ABSPATH; ?>" />
	</div>


	<div style="text-align:right; margin-top:30px;">
		<button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-arrow-right"></i></button>
	</div>
</form>