<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>

<h1><?php echo _('Users'); ?></h1>


<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><i class="fa fa-group"></i> <?php echo _('Users'); ?></li>
</ol>


<?php

	// Check access
	if ($thisUser['role'] != 'admin') {
		header("Location: index.php");
		exit();
	}


	$getUsers = $objUsers->getUsers();
?>

<div class="tiles">
	<?php foreach ($getUsers as $userID => $uData): ?>
		<a class="tile bg-blue" href="?m=settings&page=userprofile&id=<?php echo $userID; ?>">
			<i class="fa fa-user"></i>
			<span class="tile-title"><?php echo $uData['displayname']; ?></span>
		</a>
	<?php endforeach; ?>
</div>
