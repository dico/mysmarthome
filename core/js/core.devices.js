function lights_loader_start(intID) {
	$('#light_loader-' + intID).fadeIn();
}

function lights_loader_stop(intID) {
	$('#light_loader-' + intID).hide();
}

function lights(binding, deviceIntID, deviceID, action, param) {

	//console.log("onoff triggered");
	//console.log("deviceID: " + deviceID);
	//console.log("action: " + action);

	lights_loader_start(deviceIntID);

	$.ajax({
		type: "GET",
		url: "bindings/" + binding + "/execute.php?ajax=true" + param,
		data: {
			action: action, 
			deviceID: deviceID
		},
		cache: false,
		success: function(data){
			//console.log("Success");
			//console.log(data);
			//$('#ajaxFeedback').html(data);


			lights_loader_stop(deviceIntID);


			// Change btn classes/color
			/*if (action == "turnOn") {
				$('#icon-' + deviceIntID).css("color", "yellow");

				$('#btn-on-' + deviceIntID).removeClass('btn-default');
				$('#btn-off-' + deviceIntID).removeClass('btn-danger');

				$('#btn-on-' + deviceIntID).addClass('btn-success');
				$('#btn-off-' + deviceIntID).addClass('btn-default');
			}

			else if (action == "turnOff") {
				$('#icon-' + deviceIntID).css("color", "white");

				$('#btn-on-' + deviceIntID).removeClass('btn-success');
				$('#btn-off-' + deviceIntID).removeClass('btn-default');

				$('#btn-on-' + deviceIntID).addClass('btn-default');
				$('#btn-off-' + deviceIntID).addClass('btn-danger');
			}*/


			/* OLD CLEAN BOOTSRAP-BUTTONS
			if (state == "on") {
				$('#deviceSwitchOff_' + deviceID).removeClass('btn-danger');

				$('#deviceSwitchOff_' + deviceID).addClass('btn-default');
				$('#deviceSwitchOn_' + deviceID).addClass('btn-success');
			};

			if (state == "off") {
				$('#deviceSwitchOn_' + deviceID).removeClass('btn-success');

				$('#deviceSwitchOff_' + deviceID).addClass('btn-danger');
				$('#deviceSwitchOn_' + deviceID).addClass('btn-default');
			}
			*/

			/*
			if (state == "on") {
				$('#toggle_' + deviceID).attr('onChange',"deviceOnOff('" + deviceID + "', 'off');");
			}

			if (state == "off") {
				$('#toggle_' + deviceID).attr('onChange',"deviceOnOff('" + deviceID + "', 'on');");
			}




			$('#deviceLoader_' + deviceID).html("");
			*/
		},
		error: function() {
			console.log("AJAX Error switch on/off");
		},
		dataType: "html"
	});

}


function deviceLog(deviceIntID, value) {
	ajaxLoader('start');

	$.ajax({
		type: "POST",
		url: "core/core.devices.handler.php?action=deviceLog",
		data: {
			deviceIntID: deviceIntID,
			value: value
		},
		cache: false,
		success: function(data) {
			var response = JSON.parse(data); // Parse to JSON

			if (response.db_result == true) {
				if (value == 0) {
					deviceOnOffBtn('off', 'btn-monitor');
					$.notify(response.feedback_text, "warn");
				}

				else if (value == 1) {
					deviceOnOffBtn('on', 'btn-monitor');
					$.notify(response.feedback_text, "success");
				}
			} else {
				$.notify("An error occured with write/read from database or feedback from class...", "error");
			}

			ajaxLoader('end');
		},
		error: function() {
			alert("An error occured with the AJAX-request... Please try again.");
		},
		dataType: "html"
	});
}


function devicePublic(deviceIntID, value) {
	ajaxLoader('start');

	$.ajax({
		type: "POST",
		url: "core/core.devices.handler.php?action=devicePublic",
		data: {
			deviceIntID: deviceIntID,
			value: value
		},
		cache: false,
		success: function(data) {
			var response = JSON.parse(data); // Parse to JSON

			if (response.db_result == true) {
				if (value == 0) {
					deviceOnOffBtn('off', 'btn-public');
					$.notify(response.feedback_text, "warn");
				}

				else if (value == 1) {
					deviceOnOffBtn('on', 'btn-public');
					$.notify(response.feedback_text, "success");
				}
			} else {
				$.notify("An error occured with write/read from database or feedback from class...", "error");
			}

			ajaxLoader('end');
		},
		error: function() {
			alert("An error occured with the AJAX-request... Please try again.");
		},
		dataType: "html"
	});
}


function deviceDashboard(deviceIntID, value) {
	ajaxLoader('start');

	$.ajax({
		type: "POST",
		url: "core/core.devices.handler.php?action=deviceDashboard",
		data: {
			deviceIntID: deviceIntID,
			value: value
		},
		cache: false,
		success: function(data) {
			var response = JSON.parse(data); // Parse to JSON

			if (response.db_result == true) {
				if (value == 0) {
					deviceOnOffBtn('off', 'btn-dashboard');
					$.notify(response.feedback_text, "warn");
				}

				else if (value == 1) {
					deviceOnOffBtn('on', 'btn-dashboard');
					$.notify(response.feedback_text, "success");
				}
			} else {
				$.notify("An error occured with write/read from database or feedback from class...", "error");
			}

			ajaxLoader('end');
		},
		error: function() {
			alert("An error occured with the AJAX-request... Please try again.");
		},
		dataType: "html"
	});
}



function deviceOnOffBtn(value, id) {
	// Turn ON
	if (value == 'on') {
		$('#' + id + '-off').removeClass('btn-danger');
		$('#' + id + '-off').addClass('btn-default');
		$('#' + id + '-on').removeClass('btn-default');
		$('#' + id + '-on').addClass('btn-success');
	}

	// Turn OFF
	else {
		$('#' + id + '-off').removeClass('btn-default');
		$('#' + id + '-off').addClass('btn-danger');
		$('#' + id + '-on').removeClass('btn-success');
		$('#' + id + '-on').addClass('btn-default');
	}
}