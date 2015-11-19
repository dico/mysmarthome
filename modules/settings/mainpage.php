<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>

<h1><?php echo _('Settings'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></li>
</ol>



<div class="tiles font-white-link">
	<a class="ajax tile bg-grayDark" href="?m=settings&page=userprofile"><i class="fa fa-user"></i><span class="tile-title"><?php echo _('My profile'); ?></span></a>
	
	<a class="ajax tile bg-grayDark" href="?m=settings&page=locations"><i class="fa fa-home"></i><span class="tile-title"><?php echo _('Locations'); ?></span></a>
	<a class="ajax tile bg-grayDark" href="?m=settings&page=alerts"><i class="fa fa-warning"></i><span class="tile-title"><?php echo _('Alerts'); ?></span></a>
	<a class="ajax tile bg-grayDark" href="?m=settings&page=devices"><i class="fa fa-cogs"></i><span class="tile-title"><?php echo _('Devicemanager'); ?></span></a>
</div>






<h3 style="margin-top:30px;"><?php echo _('Advanced'); ?></h3>

<div class="tiles font-white-link">
	<a class="ajax tile bg-grayDark" href="?m=settings&page=events"><i class="fa fa-cogs"></i><span class="tile-title"><?php echo _('Events'); ?></span></a>
	<a class="ajax tile bg-grayDark" href="?m=settings&page=url_triggers"><i class="fa fa-globe"></i><span class="tile-title"><?php echo _('URL triggers'); ?></span></a>
	<a class="ajax tile bg-grayDark" href="?m=settings&page=sms_providers"><i class="fa fa-comments-o"></i><span class="tile-title"><?php echo _('SMS'); ?></span></a>	
</div>








<h3 style="margin-top:30px;"><?php echo _('System'); ?></h3>

<div class="tiles font-white-link">

	<a class="ajax tile bg-grayDark" href="?m=settings&page=themes">
		<i class="fa fa-image"></i><span class="tile-title"><?php echo _('Themes'); ?></span>
	</a>

	<a class="ajax tile bg-grayDark" href="?m=settings&page=modules">
		<i class="fa fa-cubes"></i><span class="tile-title"><?php echo _('Modules'); ?></span>
	</a>

	<?php if ($thisUser['role'] == 'admin'): ?>
		<a class="ajax tile bg-orangeDark" href="?m=settings&page=cronjobs"><i class="fa fa-cog"></i><span class="tile-title"><?php echo _('System schedules'); ?></span></a>
	<?php endif; ?>

</div>






<?php if ($thisUser['role'] == 'admin'): ?>
	<h3 style="margin-top:30px;"><?php echo _('System admin'); ?></h3>

	<div class="tiles font-white-link">
		<a class="ajax tile bg-orangeDark" href="?m=settings&page=bindings"><i class="fa fa-cube"></i><span class="tile-title"><?php echo _('Bindings'); ?></span></a>
		<a class="ajax tile bg-orangeDark" href="?m=settings&page=users"><i class="fa fa-group"></i><span class="tile-title"><?php echo _('Users'); ?></span></a>
		<!-- <a class="ajax tile bg-orangeDark" href="?m=settings&page=languages"><i class="fa fa-flag"></i><span class="tile-title"><?php echo _('Languages'); ?></span></a> -->
	</div>
<?php endif; ?>