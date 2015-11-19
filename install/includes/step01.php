<script type="text/javascript">
	$(document).ready(function() {

		// Disable NEXT button if license not checked
		$('#nextBtn').click(function(){
			alert('You must check the license-agreement');
			return false;
		});
		
		$('#check-license').click(function() {
			
			if(!$(this).is(':checked')){
				$('#nextBtn').bind('click', function(){ 
					alert('You must check the license-agreement');
					return false;
				});
			} else {
				$('#nextBtn').unbind('click');
			}
		});


	});
</script>





<h2>Welcome</h2>

<p>
	Welcome to mySmartHome installation.<br>
	Please see online documentation if any problems occurs.
</p>

<br />




<h2>Requirements</h2>

<ul>
	<li>PHP 5.3+</li>
	<li>MySQL / MariaDB</li>
</ul>


<div style="color:red;">
	Bindings and/or other packages you install on MSH can have their own requirements!<br />
	Bindings often <b>requires oAuth</b> on the this server to authenticate with third-parties.
</div>


<br /><br />





<h2>Read / write permissions</h2>

<table class="table table-striped table-hover">

	<tr>
		<td style="width:100px; text-align:center;">
			<?php
				$path = ABSPATH . 'data/';
				if (is_writable($path)) {
					echo '<i style="color:green;" class="fa fa-check"></i>';
				} else {
					echo '<i style="color:red;" class="fa fa-close"></i>';
				}
			?>
		</td>

		<td>
			<?php
				$path = ABSPATH . 'data/';
				if (is_writable($path)) {
					echo $path . ' is writable';
				} else {
					echo $path . ' is NOT writable';
				}
			?>
			<br />
			./data path is used for uploading files. Some features in MSH-core and added packages may not work as intended if this folder is not writable.
		</td>
	</tr>
</table>



<br /><br />

<h2>License Agreement</h2>

<div class="license-agreement">
	<?php require(ABSPATH . 'install/includes/license.php'); ?>
</div>



<div style="text-align:right; margin-top:30px;">

	<div style="float:left;">
		<label for="check-license">
			<input type="checkbox" id="check-license" /> I have read and agree with the license agreement
		</label>
	</div>

	<a class="btn btn-primary btn-lg" id="nextBtn" href="?page=step02">Start installation <i class="fa fa-arrow-right"></i></a>
</div>