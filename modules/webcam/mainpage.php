<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}

	$p = array (
		'categories' => array(12),
	);

	$getDevices = $objDevices->getDevices($p);
?>

<script type="text/javascript">
	$(document).ready(function() {
		setInterval(function(){
			$('.webcam-refresh').each(function() {
				var url = $(this).attr('data-url');
				$(this).css("background-image", "url("+url+")");
				console.log('Refreshing image. URL: ' + url);
			});
		},30000);
	});
</script>



	
<h1><?php echo _('Webcam'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><?php echo _('Webcam'); ?></li>
</ol>


<a class="btn btn-success" href="?m=webcam&page=addWebcam">
	<?php echo _('Add webcam'); ?>
</a>

<div class="clearfix"></div>

<br />
<br />

<?php foreach ($getDevices as $dID => $dData): ?>

	<div class="webcam-box">
		<div class="webcam-box-inner">

			<?php if ($dData['type_desc'] == "url"): ?>
			<div class="webcam-box-image webcam-refresh" style="background:url(<?php echo $dData['url']; ?>) no-repeat center center;" data-url="<?php echo $dData['url']; ?>">
			<?php elseif ($dData['type_desc'] == "folder"): ?>
			<?php
				$matches = array();
				preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($dData['url']), $matches);
			?>
			<div class="webcam-box-image" style="background:url(<?php echo $dData['url'] . end($matches[2]); ?>);">
			<?php endif; ?>


				<!-- <a class="webcam-box-link" href="?m=webcam&page=image&id=<?php echo $dData['deviceIntID']; ?>"></a> -->
				<a class="webcam-box-link" href="<?php echo URL; ?>core/includes/modal/device.php?id=<?php echo $dData['deviceIntID']; ?>" data-toggle="modal" data-target="#modal"></a>


				<div class="webcam-box-footer">
					<div class="webcam-box-title">
						<?php echo $dData["name"]; ?>
					</div>

					<div class="webcam-box-tools">
						<?php if ($dData["dashboard"] == 0): ?>
							<a class="showTooltip btn btn-default btn-sm" style="margin-right:8px;" title="<?php echo _("Add this to dashboard"); ?>" href="core/handlers/Devices_handler.php?action=setDashboard&id=<?php echo $dData["deviceIntID"]; ?>&value=1">
								<i class="fa fa-desktop"></i>
							</a>
						<?php else: ?>
							<a class="showTooltip btn btn-success btn-sm" style="margin-right:8px;" title="<?php echo _("Remove from dashboard"); ?>" href="core/handlers/Devices_handler.php?action=setDashboard&id=<?php echo $dData["deviceIntID"]; ?>&value=0">
								<i class="fa fa-desktop"></i>
							</a>
						<?php endif; ?>


						<?php if ($dData["public"] == 0): ?>
							<a class="showTooltip btn btn-default btn-sm" style="margin-right:8px;" title="<?php echo _("Show on public page"); ?>" href="core/handlers/Devices_handler.php?action=setPublic&id=<?php echo $dData["deviceIntID"]; ?>&value=1">
								<i class="fa fa-group"></i>
							</a>
						<?php else: ?>
							<a class="showTooltip btn btn-success btn-sm" style="margin-right:8px;" title="<?php echo _("Remove from public page"); ?>" href="core/handlers/Devices_handler.php?action=setPublic&id=<?php echo $dData["deviceIntID"]; ?>&value=0">
								<i class="fa fa-group"></i>
							</a>
						<?php endif; ?>

						<button class="showTooltip btn btn-default btn-sm" data-toggle="modal" data-target="#confirmDelete" style="margin-right:8px;" title="<?php echo _("Delete webcam"); ?>" href="<?php echo URL; ?>modules/webcam/confirmDelete.php?id=<?php echo $dData["deviceIntID"]; ?>">
							<i class="fa fa-trash"></i>
						</button>
					</div>

					<div class="clearfix"></div>

				</div> <!-- end webcam-box-footer -->

				
			</div> <!-- end webcam-box-image -->

		</div> <!-- end webcam-box-inner -->
	</div> <!-- end webcam-box -->

<?php endforeach; ?>


<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div>
  </div>
</div>