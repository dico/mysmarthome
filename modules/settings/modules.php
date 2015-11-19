<?php
	$handler = URL . 'core/handlers/Core_handler.php';
?>

<h1><?php echo _('Modules'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Modules'); ?></li>
</ol>


<?php if ($core['modules']['user_modules_count'] == 0): ?>
	<div class="alert alert-warning">
		<i class="fa fa-info-circle"></i> <?php echo _('You haven\'t selected any modules!'); ?>

		<a style="margin-left:20px;" href="<?php echo URL; ?>modules/settings/modal/user_module_select.php" data-toggle="modal" data-target="#modal">
			<?php echo _('Select modules'); ?>
		</a>

	</div>
<?php endif; ?>



<?php if ($core['modules']['user_modules_count'] > 0): ?>

	<a class="btn btn-success" href="<?php echo URL; ?>modules/settings/modal/user_module_select.php" data-toggle="modal" data-target="#modal">
		<?php echo _('Select modules'); ?>
	</a>

	<br /><br />

	<table class="table table-hover table-striped">
		<thead>
			
		</thead>

		<tbody>
			
			<?php foreach ($core['modules']['user_modules'] as $mID => $mData): ?>
				<tr>
					
					<td style="width:100px;">
						<?php if ($mData['rang'] != 0): ?>
							<a href="<?php echo $handler; ?>?action=moveUserModule&module=<?php echo $mID; ?>&rang=<?php echo ($mData['rang'] - 1); ?>"><i class="fa fa-chevron-up"></i></a> &nbsp; 
						<?php endif ?>

						<?php if ($mData['rang'] != ($core['modules']['user_modules_count'] - 1)): ?>
							<a href="<?php echo $handler; ?>?action=moveUserModule&module=<?php echo $mID; ?>&rang=<?php echo ($mData['rang'] + 1); ?>"><i class="fa fa-chevron-down"></i></a>
						<?php endif ?>
					</td>

					<td>
						<?php echo $mData['icon'] . ' &nbsp; ' . $mData['name']; ?>
					</td>

				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

<?php endif; ?>


