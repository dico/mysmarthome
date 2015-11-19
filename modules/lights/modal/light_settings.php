<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	$getID = clean($_GET['id']);
	$dData = $objDevices->getDevice($getID);
	
?>



<div class="modal-header">
	
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel"><?php echo $dData['name']; ?></h4>
	</div>

	<div class="modal-body">
		<a class="btn btn-default btn-block" href="<?php echo BINDINGS_URL . $dData['binding']; ?>/execute.php?action=turnOn&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
			Turn ON
		</a>
		<a class="btn btn-default btn-block" href="<?php echo BINDINGS_URL . $dData['binding']; ?>/execute.php?action=turnOff&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
			Turn OFF
		</a>

		<?php if (in_array('dim', $dData['methods'])): ?>
			<br /><br />
			<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=25');">
				<?php echo _('Dim'); ?> 25%
			</a>
			<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=50');">
				<?php echo _('Dim'); ?> 50%
			</a>
			<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=70');">
				<?php echo _('Dim'); ?> 70%
			</a>
		<?php endif ?>

	</div>

</div>