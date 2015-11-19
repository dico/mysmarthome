<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}

	if (isset($_GET['id'])) {
		$getUser = $objUsers->getUser($_GET['id']);
	}

	else {
		$getUser = $thisUser;
	}

	$languages = $objCore->getLanguages();
	$themes = $objCore->getThemes();
	
?>

<h1><?php echo $getUser['displayname']; ?></h1>


<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li><a class="ajax" href="?m=settings&page=users"><i class="fa fa-group"></i> <?php echo _('Users'); ?></a></li>
	<li class="active"><?php echo $getUser['displayname']; ?></li>
</ol>


<div class="row">
	<div class="col-md-2">
		<div style="text-align:center; font-size:100px;">
			<i class="fa fa-user"></i>
		</div>

		<a href=""><?php echo _('Change password'); ?></a>
		<a href=""><?php echo _('Generate API key'); ?></a>

	</div>

	<div class="col-md-10">
		<div style="max-width:600px;">
			<form action="<?php echo URL; ?>core/handlers/Users_handler.php?action=editUser&id=<?php echo $getUser['user_id']; ?>" method="POST">

				<h3 style="border-bottom:1px solid #eaeaea;"><?php echo _('Userdata'); ?></h3>

				<div class="form-group">
					<label for="inputName"><?php echo _('Name'); ?></label>
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="<?php echo _('Displayname'); ?>" value="<?php echo $getUser['displayname']; ?>">
				</div>

				<div class="form-group">
					<label for="inputMail"><?php echo _('Mail'); ?></label>
					<input type="email" class="form-control" name="inputMail" id="inputMail" placeholder="<?php echo _('E-mail'); ?>" value="<?php echo $getUser['mail']; ?>">
				</div>

				<div class="form-group">
					<label for="inputMobile"><?php echo _('Mobile'); ?></label>
					<input type="tel" class="form-control" name="inputMobile" id="inputMobile" placeholder="<?php echo _('Mobile'); ?>" value="<?php echo $getUser['mobile']; ?>">
				</div>




				<h3 style="margin-top:60px; border-bottom:1px solid #eaeaea;"><?php echo _('Application'); ?></h3>

				<div class="form-group">
					<label for="inputHomeTitle"><?php echo _('Home title'); ?></label>
					<input type="text" class="form-control" name="inputHomeTitle" id="inputHomeTitle" placeholder="<?php echo _('Home title'); ?>" value="<?php echo $config['page_title']; ?>">
				</div>

				<div class="form-group">
					<label for="selectLanguage"><?php echo _('Language'); ?></label>
					<select class="form-control" id="selectLanguage" name="selectLanguage">
						<?php
							foreach ($languages as $langID => $langData) {
								if ($getUser['language'] == $langID)
									echo '<option value="'.$langID.'" selected="selected">'.$langData['name'].'</option>';
								else
									echo '<option value="'.$langID.'">'.$langData['name'].'</option>';
							}
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="selectTheme"><?php echo _('Theme'); ?></label>
					<select class="form-control" id="selectTheme" name="selectTheme">
						<?php
							foreach ($themes as $foldername => $themeData) {
								if ($getUser['theme'] == $foldername)
									echo '<option value="'.$foldername.'" selected="selected">'.$themeData['name'].'</option>';
								else
									echo '<option value="'.$foldername.'">'.$themeData['name'].'</option>';
							}
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="inputPageRefreshTime"><?php echo _('Page refresh time'); ?></label><br />
					<input class="form-control" style="display:inline-block; width:60px;" type="number" name="inputPageRefreshTime" id="inputPageRefreshTime" value="<?php echo $getUser['page_refresh_time']; ?>" min="1" max="60" />
					<?php echo _('minutes'); ?>
				</div>






				<h3 style="margin-top:60px; border-bottom:1px solid #eaeaea;"><?php echo _('Public'); ?></h3>

				<div class="form-group">
					<label for="inputPublicName"><?php echo _('Public name'); ?></label>
					<input type="text" class="form-control" name="inputPublicName" id="inputPublicName" placeholder="<?php echo _('Public name'); ?>" value="<?php echo $getUser['public_name']; ?>">
				</div>

				<div class="checkbox">
					<?php
						if ($getUser['public_allow'] == 1) $publicAllowChecked = 'checked="checked"';
						else $publicAllowChecked = '';
					?>
					<label>
						<input type="checkbox" name="checkboxPublic" id="checkboxPublic" <?php echo $publicAllowChecked; ?> value="1" /> <?php echo _('Allow public display'); ?>
					</label>
				</div>







				<h3 style="margin-top:60px; border-bottom:1px solid #eaeaea;"><?php echo _('Access'); ?></h3>

				<div class="form-group">
					<label for="selectRole"><?php echo _('Role'); ?></label>
					<select class="form-control" id="selectRole" name="selectRole">
						<?php
							if ($getUser['role'] == 'public') 	$role_public = 'selected="selected"';
							if ($getUser['role'] == 'user') 	$user_public = 'selected="selected"';
							if ($getUser['role'] == 'admin') 	$admin_public = 'selected="selected"';

							echo '<option value="public" '.$role_public.'>'._('Public').'</option>';
							echo '<option value="user" '.$user_public.'>'._('User').'</option>';
							echo '<option value="admin" '.$admin_public.'>'._('Admin').'</option>';
						?>
					</select>
				</div>

				<div class="checkbox">
					<?php
						if ($getUser['deactive'] == 1) $deactiveChecked = 'checked="checked"';
						else $deactiveChecked = '';
					?>
					<label>
						<input type="checkbox" name="checkboxDeactive" id="checkboxDeactive" <?php echo $deactiveChecked; ?>> <?php echo _('Deactive'); ?>
					</label>
				</div>


				
				<button type="submit" class="btn btn-primary"><?php echo _('Save'); ?></button>
			</form>
		</div>
	</div>
</div>