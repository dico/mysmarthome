<script type="text/javascript" src="<?php echo MODULES_URL; ?>settings/js/events.js"></script>

<?php
	$myDevices = $objDevices->getDevices();	
?>

<h1><?php echo _('Events'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><a href="?m=settings&page=events"><i class="fa fa-cogs"></i> <?php echo _('Events'); ?></a></li>
</ol>

<p class="info">
	The minimum interval is defined by the cronjob on the server, and/or cronjob settings in MSH.<br />
	Each event check for matching values since last run, so the event will only trigger again if there are new logged values since last check.<br />
	Keep interval for event as high as possible/suitable for you, to lower server resources and to prevent spam if alert or notification alerts are set.
</p>

	
<a class="btn btn-success" href="<?php echo URL; ?>modules/settings/modal/event_add.php" data-toggle="modal" data-target="#modal">
	<i class="fa fa-plus"></i> <?php echo _('Create new event'); ?>
</a>

<br /><br /><br />

<?php
	$myEvents = $objEvents->getEvents();
	
	foreach ($myEvents as $eID => $eData) {
		echo '<div class="event-container" style="border-bottom:1px solid #eaeaea; padding-bottom:10px; margin-bottom:10px;">';

			if ($eData['disabled'] == 1) $eventIcon = "fa-close";
			else $eventIcon = "fa-cogs";


			echo '<h2 class="more" id="'.$eID.'" style="cursor:pointer;"><i class="fa fa-fw '.$eventIcon.'"></i> &nbsp; '.$eData['title'].'</h2>';


			// Status
			echo '<div style="color:#888; margin:10px;">';
				if ( $eData['last_run'] != '0000-00-00 00:00:00' )
					echo _('Last check') . ': ' . $eData['last_run'].' ('.ago(strtotime($eData['last_run'])).')<br />';
				else
					echo _('This event has not runned yet!') . '<br />';
				
				if ( $eData['last_success'] != '0000-00-00 00:00:00' )
					echo _('Last matching conditions') . ': ' . $eData['last_success'].' ('.ago(strtotime($eData['last_success'])).')<br />';
				else
					echo _('This event has not matched the conditions yet!') . '<br />';
			echo '</div>';



			// Event form
			if (isset($_GET['eventID']) && $_GET['eventID'] == $eID) {
				$showMoreDisplay = '';
			} else {
				$showMoreDisplay = 'none';
			}

			echo '<div class="show-more" style="display:'.$showMoreDisplay.';">';
				echo '<form action="'.URL.'core/handlers/Events_handler.php?action=updateEvent&eventID='.$eID.'" method="POST">';

					
					echo '<table class="table table-hover table-striped">';
						
						// Event Title
						echo '<tr>';
							echo '<td style="width:170px;">'._('Title').'</td>';
							echo '<td>';
								echo '<input class="form-control" type="text" name="event[conditions][title]" value="'.$eData['title'].'" />';
							echo '</td>';
						echo '</tr>';

						// Msg title
						echo '<tr>';
							echo '<td>';
								echo _('Message').' '.strtolower(_('Title'));

								$toolTip = _('This message will be used in mail, sms and alerts.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								echo '<input class="form-control" type="text" name="event[conditions][msg_title]" value="'.$eData['msg_title'].'" />';
							echo '</td>';
						echo '</tr>';

						// Msg description
						echo '<tr>';
							echo '<td>';
								echo _('Message').' '.strtolower(_('Description'));

								$toolTip = _('This message will be used in mail and alerts (Not SMS).');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								echo '<input class="form-control" type="text" name="event[conditions][msg_desc]" value="'.$eData['msg_desc'].'" />';
							echo '</td>';
						echo '</tr>';

						// Interval
						echo '<tr>';
							echo '<td>';
								echo _('Interval');

								$toolTip = _('How long to wait between each check. Min value is 1 minute. This is to prevent unnecessary messages and free up server resources. Recommended to set this as high as suitable for your needs.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								echo '<input class="form-control" style="display:inline-block; width:100px;" type="text" name="event[conditions][interval]" value="'.($eData['interval'] / 60).'" /> &nbsp; ' . _('minutes');
							echo '</td>';
						echo '</tr>';

						// Days
						echo '<tr>';
							echo '<td>';
								echo _('Days');

								$toolTip = _('Select days in week. No days selected = All days.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								// Put all week days in a array
								$days = array(
									1 => _('Monday'),
									2 => _('Tuesday'),
									3 => _('Wednesday'),
									4 => _('Thursday'),
									5 => _('Friday'),
									6 => _('Saturday'),
									7 => _('Sunday'),
								);

								// Split selected days into array
								$eventDays = str_split($eData['days']);

								// Loop days
								foreach ($days as $key => $name) {

									// If day in selected array, mark as checked
									if (in_array($key, $eventDays)) $dayChecked = 'checked="checked"';
									else $dayChecked = '';

									// Checkboxes
									echo '<label style="display:inling-block; min-width:110px;">';
										echo '<input type="checkbox" name="event[conditions][days][]" value="'.$key.'" '.$dayChecked.' /> ' . $name;
									echo '</label>';
								}
							echo '</td>';
						echo '</tr>';


						// Dates
						echo '<tr>';
							echo '<td>';
								echo _('Between dates');

								$toolTip = _('Format: yyyy-mm-dd. Condition will not be checked if empty or null.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								echo '<input class="form-control datepicker" style="display:inline-block; width:100px;" type="text" name="event[conditions][date_from]" value="'.$eData['date_from'].'" /> - ';
								echo '<input class="form-control datepicker" style="display:inline-block; width:100px;" type="text" name="event[conditions][date_to]" value="'.$eData['date_to'].'" />';								
							echo '</td>';
						echo '</tr>';

						// Time
						echo '<tr>';
							echo '<td>';
								echo _('Between time');

								$toolTip = _('Format: hh:mm:ss. Condition will not be checked if empty or null.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								echo '<input class="form-control" type="text" style="display:inline-block; width:100px;" name="event[conditions][time_from]" placeholder="hh:mm" value="'.$eData['time_from'].'" /> - ';
								echo '<input class="form-control" type="text" style="display:inline-block; width:100px;" name="event[conditions][time_to]" placeholder="hh:mm" value="'.$eData['time_to'].'" />';
							echo '</td>';
						echo '</tr>';

	
						// Alert
						echo '<tr>';
							echo '<td>';
								echo _('Alert');

								$toolTip = _('Trigger alert in alert/notification center.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								if ($eData['alert']['inapp'] == 1) $inAppChecked = 'checked="checked"';
								else $inAppChecked = '';

								echo '<label style="display:inline-block;">';
									echo '<input type="checkbox" name="event[conditions][inapp]" value="1" '.$inAppChecked.' /> ' . _('Set alert');
								echo '</label>';


								if ($eData['alert']['level'] == 'low') $alertLevelSelected['low'] = 'selected="selected"';
								elseif ($eData['alert']['level'] == 'medium') $alertLevelSelected['medium'] = 'selected="selected"';
								elseif ($eData['alert']['level'] == 'high') $alertLevelSelected['high'] = 'selected="selected"';

								echo '<select class="form-control" style="display:inline-block; margin-left:30px; width:100px;" name="event[conditions][inapp_level]">';
									echo '<option value="low" '.$alertLevelSelected['low'].'>'._('Low').'</option>';
									echo '<option value="medium" '.$alertLevelSelected['medium'].'>'._('Medium').'</option>';
									echo '<option value="high" '.$alertLevelSelected['high'].'>'._('High').'</option>';
								echo '</select>';

								unset($alertLevelSelected);

							echo '</td>';
						echo '</tr>';

						// If homestatus
						echo '<tr>';
							echo '<td>';
								echo _('If home status');

								$toolTip = _('Trigger only if selected homestatus. Leave blank for no check.');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								if (empty($eData['if_home_status'])) $homeStatuslSelected['none'] = 'selected="selected"';
								elseif ($eData['if_home_status'] == 'home') $homeStatuslSelected['home'] = 'selected="selected"';
								elseif ($eData['if_home_status'] == 'away') $homeStatuslSelected['away'] = 'selected="selected"';
								elseif ($eData['if_home_status'] == 'night') $homeStatuslSelected['night'] = 'selected="selected"';

								echo '<select class="form-control" style="display:inline-block; width:120px;" name="event[conditions][if_home_status]">';
									echo '<option value="" '.$homeStatuslSelected['none'].'>'._('Don\'t set').'</option>';
									echo '<option value="home" '.$homeStatuslSelected['home'].'>'._('Home').'</option>';
									echo '<option value="away" '.$homeStatuslSelected['away'].'>'._('Away').'</option>';
									echo '<option value="night" '.$homeStatuslSelected['night'].'>'._('Night').'</option>';
								echo '</select>';

								unset($homeStatuslSelected);

							echo '</td>';
						echo '</tr>';


						// Set homestatus
						echo '<tr>';
							echo '<td>';
								echo _('Set home status');

								$toolTip = _('Set home status if event triggers');
								echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
									echo '<i class="fa fa-question-circle"></i>';
								echo '</span>';
							echo '</td>';

							echo '<td>';
								if (empty($eData['set_home_status'])) $homeStatuslSelected['none'] = 'selected="selected"';
								elseif ($eData['set_home_status'] == 'home') $homeStatuslSelected['home'] = 'selected="selected"';
								elseif ($eData['set_home_status'] == 'away') $homeStatuslSelected['away'] = 'selected="selected"';
								elseif ($eData['set_home_status'] == 'night') $homeStatuslSelected['night'] = 'selected="selected"';

								echo '<select class="form-control" style="display:inline-block; width:120px;" name="event[conditions][set_home_status]">';
									echo '<option value="" '.$homeStatuslSelected['none'].'>'._('Don\'t set').'</option>';
									echo '<option value="home" '.$homeStatuslSelected['home'].'>'._('Home').'</option>';
									echo '<option value="away" '.$homeStatuslSelected['away'].'>'._('Away').'</option>';
									echo '<option value="night" '.$homeStatuslSelected['night'].'>'._('Night').'</option>';
								echo '</select>';

								unset($homeStatuslSelected);

							echo '</td>';
						echo '</tr>';

						// Disabled
						echo '<tr>';
							echo '<td>'._('Disabled').'</td>';
							echo '<td>';
								if ($eData['disabled'] == 1) $disabledChecked = 'checked="checked"';
								else $disabledChecked = '';

								echo '<label style="display:block;">';
									echo '<input type="checkbox" name="event[conditions][disabled]" value="1" '.$disabledChecked.' /> ' . _('Disable this event');
								echo '</label>';
							echo '</td>';
						echo '</tr>';

					echo '</table>';





					// TRIGGERS
					echo '<h3 style="margin-top:25px;">'._('Triggers').'</h3>';

					echo '<a href="'.URL.'core/handlers/Events_handler.php?action=addEventTrigger&eventID='.$eID.'" onclick="return confirm(\''._('This will refresh the page. Make sure everything is saved!').'\')">';
						echo '<i class="fa fa-plus"></i> ' . _('Add trigger');
					echo '</a>';

					$numTriggers = count($eData['triggers']);

					if ($numTriggers > 0) {
						echo '<table class="table table-hover table-striped">';

							echo '<thead>';
								echo '<tr>';
									//echo '<th>Trigger<br />operator</th>';
									echo '<th style="width:45%;">'._('Device').'</th>';
									echo '<th style="width:20%;">'._('Unit').'</th>';
									echo '<th style="width:15%;"></th>';
									echo '<th style="width:10%;">'._('Value').'</th>';
									echo '<th style="width:10%;">';
										echo _('Time range');
										$toolTip = _('If > 0 (minutes): Returns true if the value has been unchanged for the device in the given time range.');
										echo '<span style="margin-left:8px; font-size:16px; color:#888;" class="toolTip" title="'.$toolTip.'">';
											echo '<i class="fa fa-question-circle"></i>';
										echo '</span>';
									echo '</th>';
									echo '<th></th>';
								echo '</tr>';
							echo '</thead>';

							echo '<tbody>';
								foreach ($eData['triggers'] as $rang => $tData) {
									echo '<tr id="row-trigger-'.$tData['id'].'">';

										/*echo '<td>';
											echo $tData['trigger_operator'];
										echo '</td>';*/

										// Select device
										echo '<td>';
											$selectedDevice = 0;

											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-cube"></i></span>';

												echo '<select class="form-control" name="event[triggers]['.$tData['id'].'][device]" onChange="fetchDeviceUnits(this.value, \''.$tData['id'].'\');">';
													echo '<option value="">-- '._('Select device').'</option>';


													foreach ($myDevices as $intID => $dData) {
														$numUnits = count($myDevices[$intID]['units']);
														
														if ($numUnits >= 0) {

															// This device units
															$unitDesc = " &nbsp; (";
															foreach ($myDevices[$intID]['units'] as $unitID => $uData) {
																$unitDesc .= $uData['title'] . ' ';
															}
															$unitDesc .= ")";

															$optionTitle = $dData['name'].' (#'.$dData['deviceIntID'].') '.$unitDesc;

															if ($tData['device']['deviceIntID'] == $intID) {
																echo '<option value="'.$intID.'" selected="selected">'.$optionTitle.'</option>';
																$selectedDevice = $intID;
															}
															else {
																echo '<option value="'.$intID.'">'.$optionTitle.'</option>';
															}
														}
													
													}
												echo '</select>';
											echo '</div>';
										echo '</td>';

										// Unit
										echo '<td>';
											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-cube"></i></span>';

												echo '<select class="form-control" name="event[triggers]['.$tData['id'].'][unit]" id="trigger-units-'.$tData['id'].'">';
													//echo '<option value="">-- '._('Select unit').'</option>';

													foreach ($myDevices[$selectedDevice]['units'] as $unitID => $uData) {
														if ($tData['unit_id'] == $unitID) {
															echo '<option value="'.$unitID.'" selected="selected">'.$uData['title'].'</option>';
														}
														else
															echo '<option value="'.$unitID.'">'.$uData['title'].'</option>';
													
													}
												echo '</select>';
											echo '</div>';
										echo '</td>';


										echo '<td>';
											//echo $tData['value_operator'];

											if ($tData['value_operator'] == 'high') $voSelected['high'] = 'selected="selected"';
											if ($tData['value_operator'] == 'equal') $voSelected['equal'] = 'selected="selected"';
											if ($tData['value_operator'] == 'less') $voSelected['less'] = 'selected="selected"';

											echo '<select class="form-control" name="event[triggers]['.$tData['id'].'][value_operator]">';
												echo '<option value="high" '.$voSelected['high'].'>'._('Higher than').'</option>';
												echo '<option value="equal" '.$voSelected['equal'].'>'._('Equal').'</option>';
												echo '<option value="less" '.$voSelected['less'].'>'._('Lower than').'</option>';
											echo '</select>';

											unset($voSelected);
										echo '</td>';

										echo '<td>';
											//echo $tData['value'];
											echo '<input class="form-control" type="text" name="event[triggers]['.$tData['id'].'][value]" placeholder="0" value="'.$tData['value'].'" />';
										echo '</td>';

										echo '<td>';
											//echo $tData['value'];
											echo '<input class="form-control" type="text" name="event[triggers]['.$tData['id'].'][time_range]" placeholder="0" value="'.$tData['time_range'].'" />';
										echo '</td>';

										// Delete
										echo '<td style="text-align:right; padding-right:6px;">';
											echo '<a href="javascript:eventTriggerDelete(\''.$tData['id'].'\');"><i class="fa fa-trash"></i></a>';
										echo '</td>';

									echo '</tr>';
								}
							echo '</tbody>';
						echo '</table>';
					} else {
						echo '<div class="noResult">';
							echo _('No triggers added');
						echo '</div>';
					}






					// ACTION
					echo '<h3 style="margin-top:25px;">'._('Action').'</h3>';

					echo '<a href="'.URL.'core/handlers/Events_handler.php?action=addEventAction&eventID='.$eID.'" onclick="return confirm(\''._('This will refresh the page. Make sure everything is saved!').'\')">';
						echo '<i class="fa fa-plus"></i> ' . _('Add action');
					echo '</a>';


					$numActions = count($eData['action']);

					if ($numActions > 0) {
						echo '<table class="table table-hover table-striped">';

							echo '<thead>';
								echo '<tr>';
									echo '<th style="width:45%;">'._('Device').'</th>';
									echo '<th style="width:20%;">'._('Command').'</th>';
									echo '<th style="width:15%;"></th>';
									echo '<th></th>';
								echo '</tr>';
							echo '</thead>';

							echo '<tbody>';
								foreach ($eData['action'] as $aID => $aData) {
									echo '<tr id="row-action-'.$aID.'">';

										// Select device
										echo '<td>';
											$selectedDevice = 0;

											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-cube"></i></span>';

												echo '<select class="form-control" name="event[action]['.$aID.'][device]" onChange="fetchDeviceMethods(this.value, \''.$aID.'\');">';
													echo '<option value="">-- '._('Select device').'</option>';

													foreach ($myDevices as $intID => $dData) {
														$numMethods = count($dData['methods']);

														if ($numMethods > 0) {
															if ($aData['device']['deviceIntID'] == $intID) {
																echo '<option value="'.$intID.'" selected="selected">'.$dData['name'].'</option>';
																$selectedDevice = $intID;
															}
															else
																echo '<option value="'.$intID.'">'.$dData['name'].'</option>';
														}
													}
												echo '</select>';
											echo '</div>';
										echo '</td>';


										// Select command
										echo '<td>';
											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-cogs"></i></span>';
												
												echo '<select class="form-control" name="event[action]['.$aID.'][method]" id="action-methods-'.$aID.'" >';
													foreach ($myDevices[$selectedDevice]['methods'] as $key => $mValue) {
														if ($aData['cmd'] == $mValue['cmd'])
															echo '<option value="'.$mValue['cmd'].'" selected="selected">'.$mValue['title'].'</option>';
														else
															echo '<option value="'.$mValue['cmd'].'">'.$mValue['title'].'</option>';
													}
												echo '</select>';
											echo '</div>';
										echo '</td>';

										// Set value
										echo '<td>';
											echo '<input class="form-control" type="text" name="event[action]['.$aID.'][setValue]" id="action-setvalue-'.$aID.'" placeholder="70" value="'.$aData['set_value'].'" />';
										echo '</td>';

										// Delete
										echo '<td style="text-align:right; padding-right:6px;">';
											echo '<a href="javascript:eventActionDelete(\''.$aID.'\');"><i class="fa fa-trash"></i></a>';
										echo '</td>';

									echo '</tr>';
								}
							echo '</tbody>';
						echo '</table>';
					} else {
						echo '<div class="noResult">';
							echo _('No actions added');
						echo '</div>';
					}





					// NOTIFY
					echo '<h3 style="margin-top:25px;">'._('Notify').'</h3>';

					echo '<a href="'.URL.'core/handlers/Events_handler.php?action=addEventNotify&eventID='.$eID.'" onclick="return confirm(\''._('This will refresh the page. Make sure everything is saved!').'\')">';
						echo '<i class="fa fa-plus"></i> ' . _('Add notify recipient');
					echo '</a>';


					$numNotify = count($eData['notify']);

					if ($numNotify > 0) {
						echo '<table class="table table-hover table-striped">';

							echo '<thead>';
								echo '<tr>';
									echo '<th>'._('Mail').'</th>';
									echo '<th>'._('Mobile').'</th>';
								echo '</tr>';
							echo '</thead>';

							echo '<tbody>';
								foreach ($eData['notify'] as $nID => $nData) {
									echo '<tr>';

										echo '<td>';
											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope"></i></span>';
												echo '<input class="form-control" type="text" name="event[notify]['.$nID.'][mail]" placeholder="my@mail.com" value="'.$nData['mail'].'" />';
											echo '</div>';
										echo '</td>';

										echo '<td>';
											echo '<div class="input-group">';
												echo '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-mobile"></i></span>';
												echo '<input class="form-control" type="text" name="event[notify]['.$nID.'][mobile]" placeholder="+4712345789" value="'.$nData['sms'].'" />';
											echo '</div>';
										echo '</td>';

									echo '</tr>';
								}
							echo '</tbody>';
						echo '</table>';
					} else {
						echo '<div class="noResult">';
							echo _('No notify recipient added');
						echo '</div>';
					}



					// SUBMIT
					echo '<div style="text-align:right;">';
						echo '<a class="btn btn-danger" style="margin-right:15px;" href="'.URL.'core/handlers/Events_handler.php?action=deleteEvent&id='.$eID.'" onclick="return confirm(\''._('Are you sure').'?\')">';
							echo _('Delete event');
						echo '</a>';

						echo '<button class="btn btn-primary" type="submit">'._('Save').' '.strtolower(_('Event')).'</button>';
					echo '</div>';
				



				echo '</form>';
			echo '</div>'; // end read-more
		echo '</div>'; //end event-container
	}
?>

<br /><br />

<a href="<?php echo URL; ?>modules/settings/cron/events.php?debug=true" target="_blank">
	<i class="fa fa-cogs"></i>
	<?php echo _('Run and debug events'); ?>
</a>

<br /><br />