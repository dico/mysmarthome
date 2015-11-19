<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}


	$getBindings = $objCore->getBindings();
	$numBindings = count($getBindings);

	$getBindingsNew = $objCore->getBindingsNew();
	$numNewBindings = count($getBindingsNew);



	if (isset($_GET['binding'])) {
		$appData = $getBindings[$_GET['binding']];

		$breadcrumbs = '<li><a href="?m=settings&page=bindings"><i class="fa fa-cubes"></i> '. _('Bindings') .'</a></li>';
		$breadcrumbs .= '<li class="active">' . $appData['name'] .'</li>';
	} else {
		$breadcrumbs = '<li class="active">' . _('Bindings') .'</li>';
	}
?>


<h1><?php echo _('Bindings'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<?php echo $breadcrumbs; ?>
</ol>




<div style="float:right; margin-top:-70px;">
	<!-- <a class="btn btn-success btn-lg" href="http://mysmarthome.no/web/bindings/" target="_blank"><?php echo _('Browse') . " " . _('bindings'); ?></a> -->
	
	<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalUploadBinding">
		<i class="fa fa-upload"></i> <?php echo _('Upload') . " " . _('binding'); ?>
	</button>
</div>


<?php if (isset($_GET['msg'])): ?>
	<?php if ($_GET['msg'] == 1): ?>
		<div class="alert alert-success">
			<i class="fa fa-check"></i> <?php echo _('Binding uploaded. You can now install it!'); ?>
		</div>
	<?php elseif ($_GET['msg'] == 2): ?>
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i> <?php echo _('Could not upload binding! Please check permissions on /bindings folder. You can also upload the binding manually with FTP.'); ?>
		</div>
	<?php elseif ($_GET['msg'] == 3): ?>
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i> <?php echo _('The file was to large... (This limit is hardcoded!)'); ?>
		</div>
	<?php elseif ($_GET['msg'] == 4): ?>
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i> <?php echo _('The filetype is not allowed. Must be .zip!'); ?>
		</div>
	<?php elseif ($_GET['msg'] == 5): ?>
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i> <?php echo _('Unknown error. Return code: '); ?> <?php echo $_GET['error']; ?>
		</div>
	<?php elseif ($_GET['msg'] == 10): ?>
		<div class="alert alert-success">
			<i class="fa fa-check"></i> <?php echo _('Binding successfully installed'); ?>
		</div>
	<?php elseif ($_GET['msg'] == 11): ?>
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i> <?php echo _('Error occured while installing binding. Return code: ' . $_GET['error']); ?>
		</div>
	<?php endif ?>
<?php endif ?>


<?php
	

	// Show all binding if none selected
	if (!isset($_GET['binding'])) {

		// New bindings
		$numNewBinding = count($bindings_new);

		if ($numNewBindings > 0) {
			foreach ($getBindingsNew as $key => $bindingName) {
				echo "<div class='alert alert-info'>";
					echo _('Binding') . ": <b>$bindingName</b> " . _('found') . ".";
					echo "<a style='margin-left:25px;' class='btn btn-danger btn-xs' href='modules/settings/bindings_exec.php?action=deletePackage&binding=$bindingName'>" . _('Delete') . "</a>";
					echo "<a style='margin-left:10px;' class='btn btn-success btn-xs' href='modules/settings/bindings_exec.php?action=install&binding=$bindingName'>" . _('Install') . "</a>";
				echo "</div>";
			}
		}


		// Installed bindings
		if ($numBindings > 0) {
			echo '<div class="tiles">';
				foreach ($getBindings as $key => $bData) {
					echo '<a class="tile bg-green" href="?m=settings&page=bindings&binding='.$bData['folder'].'">';
						echo '<i class="fa fa-cube"></i>';
						echo '<span class="tile-title">'.$bData['name'].'</span>';
					echo '</a>';
				}
			echo '</div>';
		}




		// If no installed bindings
		else {
			echo _('No installed bindings found... Install some bindings to get started with your smarthome!');
		}



	}


	else {
		$bindingIndex = ABSPATH . 'bindings/' . $_GET['binding'] . '/index.php';

		if (file_exists($bindingIndex)) {
			include($bindingIndex);
		} else {
			echo _('Could not find index-file for this binding...');
		}
	}




?>



<!-- Modal -->
<div class="modal fade" id="modalUploadBinding" tabindex="-1" role="dialog" aria-labelledby="modalUploadBindingLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width:450px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="modalUploadBindingLabel"><?php echo _('Upload') . " " . _('binding'); ?></h4>
			</div>

			<form action="modules/settings/bindings_exec.php?action=uploadBinding" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<label for="file"><?php echo _('File'); ?>:</label>
					<input class="form-control" type="file" name="file" id="file"><br>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><?php echo _('Upload'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>